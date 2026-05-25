<?php

namespace App\Jobs;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\AiAgentSetting;
use App\Models\KeywordRule;
use App\Models\MetaAppConfig;
use App\Models\PlatformConnection;
use App\Services\Ai\AiServiceFactory;
use App\Services\MetaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * ProcessIncomingMessage
 *
 * Processes a normalised incoming message event from any Meta platform:
 *
 *  1. Find or create the PlatformConnection for this admin's page/number
 *  2. Find or create the Conversation thread
 *  3. Save the incoming Message record
 *  4. Decide reply strategy:
 *     a. Check KeywordRules (highest priority)
 *     b. If no keyword match and AI is enabled → call AI and send reply
 *     c. If confidence < threshold → escalate to human (don't reply)
 *  5. Send the reply back via MetaService
 *  6. Save the outbound Message record
 *
 * Supported platforms:
 *   messenger | fb_comment | whatsapp | instagram | ig_comment | ig_mention
 */
class ProcessIncomingMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $data;

    // Retry config
    public int $tries   = 3;
    public int $timeout = 60;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        $data     = $this->data;
        $userId   = $data['user_id'];
        $platform = $data['platform'];
        $text     = $data['text'] ?? null;

        Log::info("ProcessIncomingMessage started", [
            'user_id' => $userId,
            'platform' => $platform,
            'text' => substr($text ?? '', 0, 100),
            'job_id' => $this->job ? $this->job->getJobId() : null
        ]);

        // ── 1. Resolve platform type constant ─────────────────────────────────
        $platformType = $this->resolvePlatformType($platform);

        // ── 2. Find the matching PlatformConnection ────────────────────────────
        $connection = $this->findPlatformConnection($userId, $platformType, $data);
        if (!$connection) {
            Log::warning("ProcessIncomingMessage: No active PlatformConnection found", [
                'user_id'  => $userId,
                'platform' => $platform,
            ]);
            return;
        }

        Log::info("ProcessIncomingMessage: Found connection", [
            'connection_id' => $connection->id,
            'platform_type' => $platformType
        ]);

        // ── 3. Resolve contact identifier ──────────────────────────────────────
        $contactId   = $data['sender_id']    ?? $data['sender_phone'] ?? 'unknown';
        $contactName = $data['sender_name']  ?? $contactId;

        // ── 4. Find or create Conversation ────────────────────────────────────
        $conversation = Conversation::firstOrCreate(
            [
                'user_id'               => $userId,
                'platform_connection_id'=> $connection->id,
                'contact_id'            => $contactId,
                'platform_type'         => $platformType,
            ],
            [
                'tenant_id'         => $connection->tenant_id,
                'contact_name'      => $contactName,
                'status'            => CONVERSATION_STATUS_OPEN,
                'last_message'      => \Illuminate\Support\Str::limit($text ?? '[media]', 100),
                'last_message_at'   => now(),
                'ai_replied_count'  => 0,
            ]
        );

        // Update last message
        $conversation->update([
            'last_message'    => \Illuminate\Support\Str::limit($text ?? '[media]', 100),
            'last_message_at' => now(),
            'status'          => $conversation->status === CONVERSATION_STATUS_RESOLVED
                ? CONVERSATION_STATUS_OPEN
                : $conversation->status,
        ]);

        // ── 5. Save incoming message ───────────────────────────────────────────
        // For comment platforms store extra context (post_id, comment_id, sender_name)
        // inside ai_metadata so the inbox view can show "Commented on post #xxx"
        $commentMeta = null;
        if (in_array($platform, ['fb_comment', 'ig_comment', 'ig_mention'])) {
            $commentMeta = array_filter([
                'post_id'     => $data['post_id']     ?? null,
                'comment_id'  => $data['comment_id']  ?? null,
                'parent_id'   => $data['parent_id']   ?? null,
                'media_id'    => $data['media_id']    ?? null,
                'sender_name' => $data['sender_name'] ?? null,
                'platform'    => $platform,
            ]);
        }

        $incomingMsg = Message::create([
            'conversation_id' => $conversation->id,
            'user_id'         => $userId,
            'tenant_id'       => $connection->tenant_id,
            'direction'       => MESSAGE_DIRECTION_INBOUND,
            'sender_type'     => MESSAGE_SENDER_CUSTOMER,
            'body'            => $text ?? '[non-text message]',
            'message_type'    => $data['type'] ?? 'text',
            'meta_type'       => $platform,
            'external_id'     => $data['mid'] ?? $data['message_id'] ?? $data['comment_id'] ?? null,
            'ai_metadata'     => $commentMeta ?: null,
            'status'          => MESSAGE_STATUS_DELIVERED,
            'sent_at'         => now(),
        ]);

        Log::info("ProcessIncomingMessage: Saved incoming message", [
            'message_id' => $incomingMsg->id,
            'conversation_id' => $conversation->id
        ]);

        // ── 6. Don't auto-reply if human has taken over ────────────────────────
        if ($conversation->human_taken_over) {
            Log::info("Skipping AI reply — human agent active", ['conversation' => $conversation->id]);
            return;
        }

        // ── 7. Check auto-reply is enabled for this connection ─────────────────
        if ($connection->auto_reply_status !== STATUS_ACTIVE) {
            return;
        }

        // ── 8. Check message limit before sending any auto-reply ──────────────
        $msgLimit = getAdminLimit(RULES_MESSAGE_LIMIT, $userId);
        if ($msgLimit === false || ($msgLimit !== true && $msgLimit <= 0)) {
            Log::info("Auto-reply skipped — message limit reached or no active package", [
                'user_id'         => $userId,
                'conversation_id' => $conversation->id,
                'limit_remaining' => $msgLimit,
            ]);
            return;
        }


        // ── 9. Text-only auto-reply for now ───────────────────────────────────
        if (empty($text)) {
            return;
        }

        // ── 10. Check keyword rules first (highest priority) ──────────────────
        $keywordMatch = $this->matchKeyword($userId, $connection->id, $text);
        if ($keywordMatch !== null) {
            if ($keywordMatch['action'] === KEYWORD_ACTION_ESCALATE) {
                $conversation->update([
                    'status'           => CONVERSATION_STATUS_ESCALATED,
                    'human_taken_over' => 1,
                ]);
                Log::info("Keyword rule escalated conversation", ['id' => $conversation->id]);
                return;
            }

            if ($keywordMatch['action'] === KEYWORD_ACTION_IGNORE) {
                Log::info("Keyword rule ignored message", ['id' => $conversation->id]);
                return;
            }

            // ACTION_REPLY — resolve variables then send
            $replyText = resolveTemplateVariables($keywordMatch['text'], $conversation);
            $this->sendReply($conversation, $connection, $userId, $replyText, MESSAGE_SENDER_AI, $data);
            return;
        }

        // ── 11. AI reply ───────────────────────────────────────────────────────
        $aiSettings = AiAgentSetting::forUser($userId);

        if ($aiSettings->auto_reply_enabled !== STATUS_ACTIVE) {
            Log::info("ProcessIncomingMessage: AI auto-reply disabled for user {$userId}");
            return;
        }

        $apiKey = $aiSettings->getActiveApiKey();
        if (empty($apiKey)) {
            Log::warning("No AI API key configured for user {$userId}");
            return;
        }

        Log::info("ProcessIncomingMessage: Starting AI processing", [
            'conversation_id' => $conversation->id,
            'ai_provider' => $aiSettings->ai_provider
        ]);

        try {
            $aiService = AiServiceFactory::makeForProvider(
                $aiSettings->ai_provider,
                $apiKey,
                $aiSettings->ai_model
            );

            // Build conversation history for context window
            $history = $this->buildHistory($conversation, $aiSettings);

            $systemPrompt = trim(($aiSettings->system_prompt ?? '') . "\n\n" . ($aiSettings->business_context ?? ''));

            $replyText = $aiService->chat($history, $systemPrompt, [
                'max_tokens' => $aiSettings->max_tokens ?? 512,
            ]);

            if (empty($replyText)) {
                Log::warning("AI returned empty reply", ['conversation' => $conversation->id]);
                return;
            }

            Log::info("ProcessIncomingMessage: AI generated reply", [
                'conversation_id' => $conversation->id,
                'reply_length' => strlen($replyText),
                'reply_preview' => substr($replyText, 0, 100)
            ]);

            // Apply reply delay
            if ($aiSettings->reply_delay_seconds > 0) {
                Log::info("ProcessIncomingMessage: Applying reply delay", [
                    'delay_seconds' => $aiSettings->reply_delay_seconds
                ]);
                sleep($aiSettings->reply_delay_seconds);
            }

            $this->sendReply($conversation, $connection, $userId, $replyText, MESSAGE_SENDER_AI, $data);

            // Increment AI replied count
            $conversation->increment('ai_replied_count');

        } catch (\Exception $e) {
            Log::error("AI reply failed for conversation {$conversation->id}", [
                'error' => $e->getMessage(),
            ]);
        }

        Log::info("ProcessIncomingMessage completed", [
            'job_id' => $this->job ? $this->job->getJobId() : null,
            'conversation_id' => $conversation->id
        ]);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    protected function resolvePlatformType(string $platform): int
    {
        return match ($platform) {
            'messenger'  => PLATFORM_MESSENGER,
            'fb_comment' => PLATFORM_FACEBOOK_PAGE,
            'whatsapp'   => PLATFORM_WHATSAPP,
            'instagram'  => PLATFORM_INSTAGRAM,
            'ig_comment' => PLATFORM_INSTAGRAM,
            'ig_mention' => PLATFORM_INSTAGRAM,
            default      => PLATFORM_MESSENGER,
        };
    }

    protected function findPlatformConnection(int $userId, int $platformType, array $data): ?PlatformConnection
    {
        $query = PlatformConnection::where('user_id', $userId)
            ->where('platform_type', $platformType)
            ->where('status', STATUS_ACTIVE);

        // Try to narrow by page/phone ID
        $platformId = $data['page_id']         // FB/IG
            ?? $data['phone_number_id']         // WA
            ?? $data['ig_account_id']           // IG
            ?? null;

        if ($platformId) {
            $specific = (clone $query)->where('platform_id', $platformId)->first();
            if ($specific) return $specific;
        }

        // ── Fallback: Messenger ↔ Facebook Page cross-lookup ──────────────────
        // Both PLATFORM_MESSENGER and PLATFORM_FACEBOOK_PAGE share the same
        // Facebook Page ID. If the admin connected their page as one type but
        // messages arrive for the other type, we still find the connection.
        if ($platformId && in_array($platformType, [PLATFORM_MESSENGER, PLATFORM_FACEBOOK_PAGE])) {
            $fallbackType = ($platformType === PLATFORM_MESSENGER)
                ? PLATFORM_FACEBOOK_PAGE
                : PLATFORM_MESSENGER;

            $fallback = PlatformConnection::where('user_id', $userId)
                ->where('platform_type', $fallbackType)
                ->where('platform_id', $platformId)
                ->where('status', STATUS_ACTIVE)
                ->first();

            if ($fallback) {
                Log::info('ProcessIncomingMessage: Used cross-type FB fallback connection', [
                    'requested_type' => $platformType,
                    'found_type'     => $fallbackType,
                    'platform_id'    => $platformId,
                ]);
                return $fallback;
            }
        }

        // Last resort: any active connection of the right type for this user
        return $query->first();
    }

    /**
     * Check keyword rules for an exact/contains/starts-with match.
     * Returns ['action' => string, 'text' => string] or null if no match.
     */
    protected function matchKeyword(int $userId, int $connectionId, string $text): ?array
    {
        $rules = KeywordRule::where('user_id', $userId)
            ->where('status', STATUS_ACTIVE)
            ->where(fn($q) => $q->whereNull('platform_connection_id')
                ->orWhere('platform_connection_id', $connectionId))
            ->orderByDesc('priority')
            ->get();

        $textLower = mb_strtolower(trim($text));

        foreach ($rules as $rule) {
            $keyword = mb_strtolower(trim($rule->keyword));
            $matched = match ((int) $rule->match_type) {
                KEYWORD_MATCH_EXACT       => $textLower === $keyword,
                KEYWORD_MATCH_STARTS_WITH => str_starts_with($textLower, $keyword),
                KEYWORD_MATCH_CONTAINS    => str_contains($textLower, $keyword),
                default                   => false,
            };

            if ($matched && !$rule->use_ai) {
                return [
                    'action' => $rule->action ?? KEYWORD_ACTION_REPLY,
                    'text'   => $rule->reply_template ?? '',
                ];
            }
        }

        return null;
    }

    /**
     * Build message history array for AI context window.
     */
    protected function buildHistory(Conversation $conversation, AiAgentSetting $settings): array
    {
        $useMemory = $settings->conversation_memory === STATUS_ACTIVE;

        if (!$useMemory) {
            // Only the latest customer message
            $last = $conversation->messages()->latest()->first();
            return [['role' => 'user', 'content' => $last?->body ?? '']];
        }

        // Last N messages as context
        $messages = $conversation->messages()
            ->orderBy('id')
            ->take(20)
            ->get();

        return $messages->map(fn($m) => [
            'role'    => $m->sender_type === MESSAGE_SENDER_CUSTOMER ? 'user' : 'assistant',
            'content' => $m->body,
        ])->toArray();
    }

    /**
     * Send a reply via the appropriate Meta platform API and save the outbound message.
     */
    protected function sendReply(
        Conversation $conversation,
        PlatformConnection $connection,
        int $userId,
        string $text,
        int $senderType,
        array $incomingData
    ): void {
        Log::info("ProcessIncomingMessage: Attempting to send reply", [
            'conversation_id' => $conversation->id,
            'platform' => $incomingData['platform'],
            'sender_type' => $senderType,
            'reply_length' => strlen($text)
        ]);

        $config  = MetaAppConfig::where('user_id', $userId)->first();
        if (!$config) {
            Log::warning("ProcessIncomingMessage: No MetaAppConfig found for user {$userId}");
            return;
        }

        $service  = new MetaService($config);
        $platform = $incomingData['platform'];
        $sent     = false;

        try {
            $sent = match ($platform) {
                // Messenger DM → PSID-based message
                'messenger' =>
                    $service->sendFacebookMessage($incomingData['sender_id'], $text, $connection->access_token, $connection->platform_id),

                // Facebook post comment → reply publicly on the comment thread
                'fb_comment' =>
                    !empty($incomingData['comment_id'])
                        ? $service->sendFbCommentReply($incomingData['comment_id'], $text, $connection->access_token)
                        : false,

                // WhatsApp text message
                'whatsapp' =>
                    $service->sendWhatsAppMessage($incomingData['sender_phone'], $text, $connection->access_token, $connection->platform_id),

                // Instagram DM
                'instagram' =>
                    $service->sendInstagramMessage($incomingData['sender_id'], $text, $connection->access_token, $connection->platform_id),

                // Instagram post comment → public reply on comment
                'ig_comment' =>
                    !empty($incomingData['comment_id'])
                        ? $service->sendIgCommentReply($incomingData['comment_id'], $text, $connection->access_token)
                        : false,

                // Instagram @mention → reply on the mention comment
                'ig_mention' =>
                    !empty($incomingData['comment_id'])
                        ? $service->sendIgCommentReply($incomingData['comment_id'], $text, $connection->access_token)
                        : false,

                default => false,
            };
        } catch (\Exception $e) {
            Log::error("Send reply failed", [
                'error' => $e->getMessage(),
                'platform' => $platform,
                'conversation_id' => $conversation->id
            ]);
        }

        Log::info("ProcessIncomingMessage: Reply send result", [
            'conversation_id' => $conversation->id,
            'sent' => $sent,
            'platform' => $platform
        ]);

        // Save outbound message record
        $outboundMsg = Message::create([
            'conversation_id' => $conversation->id,
            'user_id'         => $userId,
            'tenant_id'       => $connection->tenant_id,
            'direction'       => MESSAGE_DIRECTION_OUTBOUND,
            'sender_type'     => $senderType,
            'body'            => $text,
            'message_type'    => 'text',
            'status'          => $sent ? MESSAGE_STATUS_SENT : MESSAGE_STATUS_FAILED,
            'is_approved'     => 1,
            'sent_at'         => now(),
        ]);

        Log::info("ProcessIncomingMessage: Saved outbound message", [
            'message_id' => $outboundMsg->id,
            'status' => $outboundMsg->status
        ]);

        if ($sent) {
            $conversation->update([
                'last_message'    => \Illuminate\Support\Str::limit($text, 100),
                'last_message_at' => now(),
            ]);
            Log::info("ProcessIncomingMessage: Updated conversation last message");
        }
    }
}
