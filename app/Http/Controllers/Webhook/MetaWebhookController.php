<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessIncomingMessage;
use App\Models\MetaAppConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * MetaWebhookController
 *
 * Single endpoint for ALL Meta platform webhooks:
 *
 *   GET  /webhook/meta/{userId}  → Verify challenge handshake (hub.challenge)
 *   POST /webhook/meta/{userId}  → Receive incoming events and dispatch jobs
 *
 * ──────────────────────────────────────────────────────────────────────────────
 * Meta sends all events to the SAME URL. The `object` field tells us which
 * platform sent it:
 *
 *   object = "page"
 *     entry[].messaging[]          → Facebook Messenger DMs
 *     entry[].changes[].field=feed → Facebook Page post/comment events
 *
 *   object = "whatsapp_business_account"
 *     entry[].changes[].value.messages[] → WhatsApp incoming messages
 *     entry[].changes[].value.statuses[] → WhatsApp delivery receipts
 *
 *   object = "instagram"
 *     entry[].messaging[]          → Instagram DMs
 *     entry[].changes[].field=comments  → Instagram post comment events
 *     entry[].changes[].field=mentions  → Instagram @mention events
 *
 * ──────────────────────────────────────────────────────────────────────────────
 * Webhook URL format:  https://yourdomain.com/webhook/meta/{userId}
 *   userId is the admin's user_id. This allows multi-tenant isolation:
 *   each admin registers their own webhook URL in their Meta App.
 */
class MetaWebhookController extends Controller
{
    // ─── Step 1: Webhook Verification (GET) ───────────────────────────────────

    /**
     * Meta calls this when you first register the webhook URL.
     * It sends hub.mode=subscribe + hub.verify_token + hub.challenge.
     * We verify the token and echo back the challenge.
     */
    public function verify(Request $request, int $userId)
    {
        $mode      = $request->query('hub_mode');
        $token     = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        $config = MetaAppConfig::where('user_id', $userId)->first();

        if (!$config) {
            Log::warning("Webhook verify: no MetaAppConfig for user {$userId}");
            return response('User not found', 403);
        }

        if ($mode === 'subscribe' && hash_equals($config->webhook_verify_token, $token)) {
            Log::info("Webhook verified for user {$userId}");
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        Log::warning("Webhook verify failed for user {$userId} — token mismatch");
        return response('Forbidden', 403);
    }

    // ─── Step 2: Receive Events (POST) ────────────────────────────────────────

    /**
     * Meta sends ALL incoming events (Messenger, WhatsApp, FB Comments,
     * Instagram DMs, Instagram Comments) as POST requests to this URL.
     *
     * We verify the HMAC signature, then dispatch a job per message.
     * Always return 200 immediately — Meta will retry if we don't respond fast.
     */
    public function receive(Request $request, int $userId)
    {
        // ── 1. HMAC Signature Verification ────────────────────────────────────
        $config = MetaAppConfig::where('user_id', $userId)->first();

        if (!$config) {
            return response('OK', 200); // Silently ignore unknown users
        }

        if (!$this->verifySignature($request, $config->fb_app_secret)) {
            Log::error("Webhook HMAC signature mismatch for user {$userId}", [
                'has_app_secret' => !empty($config->fb_app_secret),
                'header_sig'     => $request->header('X-Hub-Signature-256'),
            ]);
            return response('Invalid signature', 403);
        }

        // ── 2. Parse Payload ───────────────────────────────────────────────────
        $payload = $request->json()->all();
        $object  = $payload['object'] ?? null;

        Log::info("Meta webhook received", ['user_id' => $userId, 'object' => $object]);

        // ── 3. Route to correct dispatcher ────────────────────────────────────
        match ($object) {
            'page'                       => $this->handlePageEvents($payload, $userId),
            'whatsapp_business_account'  => $this->handleWhatsAppEvents($payload, $userId),
            'instagram'                  => $this->handleInstagramEvents($payload, $userId),
            default => Log::warning("Unknown webhook object: {$object}")
        };

        // Always return 200 immediately
        return response('EVENT_RECEIVED', 200);
    }

    // ─── Page Object Handler (Messenger + FB Comments) ───────────────────────

    /**
     * object = "page"
     * Handles both:
     *  - Messenger DMs  (entry.messaging[])
     *  - FB Page Feed   (entry.changes[].field = 'feed') → post comments
     */
    protected function handlePageEvents(array $payload, int $userId): void
    {
        foreach ($payload['entry'] ?? [] as $entry) {
            $pageId = $entry['id'] ?? null;

            // ── Messenger DMs ──────────────────────────────────────────────────
            foreach ($entry['messaging'] ?? [] as $event) {
                if (isset($event['message'])) {
                    // Ignore echo-back of our own messages
                    if ($event['message']['is_echo'] ?? false) continue;

                    $this->dispatchMessage([
                        'platform'    => 'messenger',
                        'user_id'     => $userId,
                        'page_id'     => $pageId,
                        'sender_id'   => $event['sender']['id'],
                        'recipient_id'=> $event['recipient']['id'],
                        'text'        => $event['message']['text'] ?? null,
                        'attachments' => $event['message']['attachments'] ?? [],
                        'mid'         => $event['message']['mid'] ?? null,
                        'timestamp'   => $event['timestamp'] ?? now()->timestamp,
                        'raw'         => $event,
                    ]);
                }

                // Read receipts / delivery — log only, no reply needed
                if (isset($event['read']) || isset($event['delivery'])) {
                    Log::debug('Messenger delivery/read receipt', ['page' => $pageId]);
                }
            }

            // ── Facebook Page Feed (Post Comments) ─────────────────────────────
            foreach ($entry['changes'] ?? [] as $change) {
                if (($change['field'] ?? '') === 'feed') {
                    $value = $change['value'];
                    $item  = $value['item'] ?? null;   // 'comment', 'post', 'like'
                    $verb  = $value['verb'] ?? null;   // 'add', 'edited', 'remove'

                    // Only handle new comments (not edits/deletions/likes)
                    if ($item === 'comment' && $verb === 'add') {
                        $this->dispatchMessage([
                            'platform'      => 'fb_comment',
                            'user_id'       => $userId,
                            'page_id'       => $pageId,
                            'sender_id'     => $value['from']['id'] ?? null,
                            'sender_name'   => $value['from']['name'] ?? null,
                            'comment_id'    => $value['comment_id'] ?? null,
                            'post_id'       => $value['post_id'] ?? null,
                            'parent_id'     => $value['parent_id'] ?? null,
                            'text'          => $value['message'] ?? null,
                            'timestamp'     => $value['created_time'] ?? now()->timestamp,
                            'raw'           => $change,
                        ]);
                    }
                }
            }
        }
    }

    // ─── WhatsApp Business Account Handler ───────────────────────────────────

    /**
     * object = "whatsapp_business_account"
     * Handles:
     *  - Incoming messages (text, image, audio, document, template)
     *  - Delivery status updates (sent, delivered, read, failed)
     */
    protected function handleWhatsAppEvents(array $payload, int $userId): void
    {
        foreach ($payload['entry'] ?? [] as $entry) {
            foreach ($entry['changes'] ?? [] as $change) {
                $value = $change['value'];

                // ── Incoming Messages ──────────────────────────────────────────
                foreach ($value['messages'] ?? [] as $msg) {
                    $msgType = $msg['type'] ?? 'text';

                    $this->dispatchMessage([
                        'platform'       => 'whatsapp',
                        'user_id'        => $userId,
                        'waba_id'        => $entry['id'] ?? null,
                        'phone_number_id'=> $value['metadata']['phone_number_id'] ?? null,
                        'sender_phone'   => $msg['from'],
                        'message_id'     => $msg['id'],
                        'type'           => $msgType,
                        'text'           => $msg['text']['body']           ?? null,
                        'image'          => $msg['image']['id']            ?? null,
                        'audio'          => $msg['audio']['id']            ?? null,
                        'document'       => $msg['document']['id']         ?? null,
                        'location'       => $msg['location']               ?? null,
                        'contact'        => $msg['contacts'][0]            ?? null,
                        'timestamp'      => $msg['timestamp']              ?? now()->timestamp,
                        'sender_name'    => $value['contacts'][0]['profile']['name'] ?? null,
                        'raw'            => $msg,
                    ]);
                }

                // ── Status Updates (delivery receipts) ─────────────────────────
                foreach ($value['statuses'] ?? [] as $status) {
                    Log::info('WhatsApp status update', [
                        'id'        => $status['id'],
                        'status'    => $status['status'],   // sent|delivered|read|failed
                        'recipient' => $status['recipient_id'],
                    ]);
                    // TODO: Update Message record status in DB
                }
            }
        }
    }

    // ─── Instagram Handler (DMs + Comments + Mentions) ───────────────────────

    /**
     * object = "instagram"
     * Handles:
     *  - Instagram DMs    (entry.messaging[])
     *  - Post Comments    (entry.changes[].field = 'comments')
     *  - @Mentions        (entry.changes[].field = 'mentions')
     */
    protected function handleInstagramEvents(array $payload, int $userId): void
    {
        foreach ($payload['entry'] ?? [] as $entry) {
            $igAccountId = $entry['id'] ?? null;

            // ── Instagram DMs (Messenger API for Instagram) ────────────────────
            foreach ($entry['messaging'] ?? [] as $event) {
                if (isset($event['message'])) {
                    if ($event['message']['is_echo'] ?? false) continue;

                    $this->dispatchMessage([
                        'platform'     => 'instagram',
                        'user_id'      => $userId,
                        'ig_account_id'=> $igAccountId,
                        'sender_id'    => $event['sender']['id'],
                        'recipient_id' => $event['recipient']['id'],
                        'text'         => $event['message']['text'] ?? null,
                        'attachments'  => $event['message']['attachments'] ?? [],
                        'mid'          => $event['message']['mid'] ?? null,
                        'timestamp'    => $event['timestamp'] ?? now()->timestamp,
                        'raw'          => $event,
                    ]);
                }
            }

            // ── Instagram Post Comments & @Mentions ────────────────────────────
            foreach ($entry['changes'] ?? [] as $change) {
                $field = $change['field'] ?? '';
                $value = $change['value'] ?? [];

                if ($field === 'comments') {
                    $this->dispatchMessage([
                        'platform'      => 'ig_comment',
                        'user_id'       => $userId,
                        'ig_account_id' => $igAccountId,
                        'comment_id'    => $value['id'] ?? null,
                        'media_id'      => $value['media']['id'] ?? null,
                        'sender_id'     => $value['from']['id'] ?? null,
                        'sender_name'   => $value['from']['username'] ?? null,
                        'text'          => $value['text'] ?? null,
                        'timestamp'     => now()->timestamp,
                        'raw'           => $change,
                    ]);
                }

                if ($field === 'mentions') {
                    $this->dispatchMessage([
                        'platform'      => 'ig_mention',
                        'user_id'       => $userId,
                        'ig_account_id' => $igAccountId,
                        'media_id'      => $value['media_id'] ?? null,
                        'comment_id'    => $value['comment_id'] ?? null,
                        'text'          => $value['mentioned_comment']['text'] ?? null,
                        'timestamp'     => now()->timestamp,
                        'raw'           => $change,
                    ]);
                }
            }
        }
    }

    // ─── Signature Verification ───────────────────────────────────────────────

    /**
     * Verify Meta's X-Hub-Signature-256 header using the App Secret.
     * MUST call this before processing any payload.
     */
    protected function verifySignature(Request $request, ?string $appSecret): bool
    {
        if (empty($appSecret)) {
            Log::warning('verifySignature: fb_app_secret is empty or not configured.');
            // In local/dev you may skip — never skip in production
            if (app()->isLocal()) return true;
            return false;
        }

        $signature = $request->header('X-Hub-Signature-256', '');
        if (empty($signature)) return false;

        $expected = 'sha256=' . hash_hmac('sha256', $request->getContent(), $appSecret);
        return hash_equals($expected, $signature);
    }

    // ─── Job Dispatcher ───────────────────────────────────────────────────────

    /**
     * Dispatch the normalised event data to the processing queue.
     * The job handles: find/create Conversation, save Message, AI reply decision.
     */
    protected function dispatchMessage(array $data): void
    {
        try {
            Log::info('Dispatching ProcessIncomingMessage to queue', [
                'platform' => $data['platform'] ?? 'unknown',
                'user_id' => $data['user_id'] ?? null,
                'sender_id' => $data['sender_id'] ?? null,
                'text_length' => isset($data['text']) ? strlen($data['text']) : 0,
            ]);

            ProcessIncomingMessage::dispatch($data);

            Log::info('ProcessIncomingMessage dispatched successfully');
        } catch (\Exception $e) {
            Log::error('Failed to dispatch ProcessIncomingMessage', [
                'error'    => $e->getMessage(),
                'platform' => $data['platform'] ?? 'unknown',
                'user_id'  => $data['user_id'] ?? null,
            ]);
        }
    }
}
