<?php

namespace App\Services;

use App\Models\MetaAppConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * MetaService handles:
 * - Webhook verification (GET challenge handshake)
 * - Incoming payload signature verification (X-Hub-Signature-256)
 * - Connection status checks (validates the stored access token)
 * - Sending messages via Graph API (FB, WA, IG)
 */
class MetaService
{
    protected MetaAppConfig $config;

    public function __construct(MetaAppConfig $config)
    {
        $this->config = $config;
    }

    // ─── Webhook ──────────────────────────────────────────────────────────────

    /**
     * Handle Meta's webhook verification GET request.
     * Returns the hub.challenge value if the verify token matches.
     */
    public function verifyWebhook(Request $request): mixed
    {
        $mode      = $request->query('hub_mode');
        $token     = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode === 'subscribe' && $token === $this->config->webhook_verify_token) {
            return $challenge; // 200 OK + echo challenge
        }

        return null; // 403
    }

    /**
     * Verify the X-Hub-Signature-256 header of an incoming webhook POST.
     * Always verify before processing any payload.
     */
    public function verifySignature(string $rawBody, string $signature): bool
    {
        if (empty($this->config->fb_app_secret)) {
            Log::warning('MetaService: App Secret not set — skipping signature check.');
            return true; // allow in dev; enforce in prod
        }

        $expected = 'sha256=' . hash_hmac('sha256', $rawBody, $this->config->fb_app_secret);
        return hash_equals($expected, $signature);
    }

    // ─── Connection Status ────────────────────────────────────────────────────

    /**
     * Check if the Facebook Page Access Token is still valid.
     */
    public function checkFacebookConnection(): array
    {
        if (!$this->config->hasFacebook()) {
            return ['ok' => false, 'message' => __('Facebook credentials not configured.')];
        }

        $response = Http::get('https://graph.facebook.com/v20.0/me', [
            'access_token' => $this->config->fb_page_access_token,
            'fields'       => 'id,name',
        ]);

        if ($response->ok()) {
            $data = $response->json();
            return [
                'ok'      => true,
                'message' => __('Connected as: ') . ($data['name'] ?? $data['id']),
                'data'    => $data,
            ];
        }

        return [
            'ok'      => false,
            'message' => $response->json('error.message', __('Token invalid or expired.')),
        ];
    }

    /**
     * Check if the WhatsApp System User token is valid.
     */
    public function checkWhatsAppConnection(): array
    {
        if (!$this->config->hasWhatsApp()) {
            return ['ok' => false, 'message' => __('WhatsApp credentials not configured.')];
        }

        $response = Http::withToken($this->config->wa_access_token)
            ->get("https://graph.facebook.com/v20.0/{$this->config->wa_phone_number_id}");

        if ($response->ok()) {
            $data = $response->json();
            return [
                'ok'      => true,
                'message' => __('WhatsApp connected: ') . ($data['display_phone_number'] ?? $data['id']),
                'data'    => $data,
            ];
        }

        return [
            'ok'      => false,
            'message' => $response->json('error.message', __('WhatsApp token invalid or expired.')),
        ];
    }

    /**
     * Check Instagram access token validity.
     */
    public function checkInstagramConnection(): array
    {
        if (!$this->config->hasInstagram()) {
            return ['ok' => false, 'message' => __('Instagram credentials not configured.')];
        }

        $response = Http::get("https://graph.facebook.com/v20.0/{$this->config->ig_user_id}", [
            'access_token' => $this->config->ig_access_token,
            'fields'       => 'id,name,username',
        ]);

        if ($response->ok()) {
            $data = $response->json();
            return [
                'ok'      => true,
                'message' => __('Instagram connected: @') . ($data['username'] ?? $data['id']),
                'data'    => $data,
            ];
        }

        return [
            'ok'      => false,
            'message' => $response->json('error.message', __('Instagram token invalid or expired.')),
        ];
    }

    // ─── Sending Messages ──────────────────────────────────────────────────────

    /**
     * Send a text message via Facebook Messenger.
     */
    public function sendFacebookMessage(string $recipientPsid, string $text, ?string $overrideToken = null, ?string $overridePageId = null): bool
    {
        $pageId = $overridePageId ?: $this->config->fb_page_id;
        $response = Http::post(
            "https://graph.facebook.com/v20.0/{$pageId}/messages",
            [
                'access_token' => $overrideToken ?: $this->config->fb_page_access_token,
                'recipient'    => ['id' => $recipientPsid],
                'message'      => ['text' => $text],
                'messaging_type' => 'RESPONSE',
            ]
        );

        if ($response->failed()) {
            Log::error('MetaService sendFacebookMessage failed', $response->json());
            return false;
        }
        return true;
    }

    /**
     * Send a text message via WhatsApp Business API.
     */
    public function sendWhatsAppMessage(string $to, string $text, ?string $overrideToken = null, ?string $overridePhoneNumberId = null): bool
    {
        $token = $overrideToken ?: $this->config->wa_access_token;
        $phoneId = $overridePhoneNumberId ?: $this->config->wa_phone_number_id;
        
        $response = Http::withToken($token)
            ->post("https://graph.facebook.com/v20.0/{$phoneId}/messages", [
                'messaging_product' => 'whatsapp',
                'recipient_type'    => 'individual',
                'to'                => $to,
                'type'              => 'text',
                'text'              => ['body' => $text],
            ]);

        if ($response->failed()) {
            Log::error('MetaService sendWhatsAppMessage failed', $response->json());
            return false;
        }
        return true;
    }

    /**
     * Reply to a Facebook Page post comment (NOT Messenger — different endpoint).
     * Uses /comment_id/comments to post a public reply.
     */
    public function sendFbCommentReply(string $commentId, string $text, ?string $overrideToken = null): bool
    {
        $response = Http::post(
            "https://graph.facebook.com/v20.0/{$commentId}/comments",
            [
                'access_token' => $overrideToken ?: $this->config->fb_page_access_token,
                'message'      => $text,
            ]
        );

        if ($response->failed()) {
            Log::error('MetaService sendFbCommentReply failed', [
                'comment_id' => $commentId,
                'error'      => $response->json(),
            ]);
            return false;
        }
        return true;
    }

    /**
     * Send a text DM via Instagram Messaging API.
     */
    public function sendInstagramMessage(string $recipientId, string $text, ?string $overrideToken = null, ?string $overrideIgUserId = null): bool
    {
        $igUserId = $overrideIgUserId ?: $this->config->ig_user_id;
        $response = Http::post(
            "https://graph.facebook.com/v20.0/{$igUserId}/messages",
            [
                'access_token'   => $overrideToken ?: $this->config->ig_access_token,
                'recipient'      => ['id' => $recipientId],
                'message'        => ['text' => $text],
                'messaging_type' => 'RESPONSE',
            ]
        );

        if ($response->failed()) {
            Log::error('MetaService sendInstagramMessage failed', $response->json());
            return false;
        }
        return true;
    }

    /**
     * Reply to an Instagram post comment publicly.
     * Uses /comment_id/replies endpoint.
     */
    public function sendIgCommentReply(string $commentId, string $text, ?string $overrideToken = null): bool
    {
        $response = Http::post(
            "https://graph.facebook.com/v20.0/{$commentId}/replies",
            [
                'access_token' => $overrideToken ?: $this->config->ig_access_token,
                'message'      => $text,
            ]
        );

        if ($response->failed()) {
            Log::error('MetaService sendIgCommentReply failed', [
                'comment_id' => $commentId,
                'error'      => $response->json(),
            ]);
            return false;
        }
        return true;
    }

    /**
     * Send a WhatsApp message using a pre-approved template.
     * Required for initiating conversations (user hasn't messaged in 24h).
     */
    public function sendWhatsAppTemplate(string $to, string $templateName, string $langCode = 'en'): bool
    {
        $response = Http::withToken($this->config->wa_access_token)
            ->post("https://graph.facebook.com/v20.0/{$this->config->wa_phone_number_id}/messages", [
                'messaging_product' => 'whatsapp',
                'to'                => $to,
                'type'              => 'template',
                'template'          => [
                    'name'     => $templateName,
                    'language' => ['code' => $langCode],
                ],
            ]);

        if ($response->failed()) {
            Log::error('MetaService sendWhatsAppTemplate failed', $response->json());
            return false;
        }
        return true;
    }

    /**
     * Mark a WhatsApp message as read.
     */
    public function markWhatsAppRead(string $messageId): void
    {
        Http::withToken($this->config->wa_access_token)
            ->post("https://graph.facebook.com/v20.0/{$this->config->wa_phone_number_id}/messages", [
                'messaging_product' => 'whatsapp',
                'status'            => 'read',
                'message_id'        => $messageId,
            ]);
    }
}
