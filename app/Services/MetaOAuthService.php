<?php

namespace App\Services;

use App\Models\MetaAppConfig;
use App\Models\PlatformConnection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * MetaOAuthService handles the full Meta OAuth 2.0 code flow:
 *
 *  1. buildAuthUrl()   — Build the FB Login dialog URL (Step 1)
 *  2. exchangeCode()   — Code → Short-lived user token (Step 2)
 *  3. getLongLivedToken() — Short → Long-lived user token (Step 3)
 *  4. getPages()       — Fetch pages the user manages with their page tokens (Step 4)
 *  5. getInstagramAccounts() — IG business accounts linked to pages (Step 5)
 *  6. getWhatsAppPhoneNumbers() — WA phone numbers under WABA (Step 5)
 *
 * References:
 *  https://developers.facebook.com/docs/facebook-login/guides/advanced/manual-flow
 *  https://developers.facebook.com/docs/graph-api/reference/page/
 */
class MetaOAuthService
{
    protected string $appId;
    protected string $appSecret;
    protected string $graphVersion = 'v20.0';
    protected string $graphBase    = 'https://graph.facebook.com';

    public function __construct(MetaAppConfig $config)
    {
        $this->appId     = $config->fb_app_id;
        $this->appSecret = $config->fb_app_secret;
    }

    // ─── Step 1: Build OAuth Dialog URL ───────────────────────────────────────

    /**
     * Build the Facebook Login dialog URL for the given platform type.
     * Different platforms need different permission scopes.
     */
    public function buildAuthUrl(string $redirectUri, string $platformType, string $state): string
    {
        $scopes = $this->getScopesFor($platformType);

        return 'https://www.facebook.com/' . $this->graphVersion . '/dialog/oauth?' . http_build_query([
            'client_id'     => $this->appId,
            'redirect_uri'  => $redirectUri,
            'scope'         => implode(',', $scopes),
            'response_type' => 'code',
            'state'         => $state,
        ]);
    }

    /**
     * Return the required OAuth scopes for each platform type.
     */
    protected function getScopesFor(string $platformType): array
    {
        $base = ['public_profile', 'email'];

        return match ($platformType) {
            'unified', 'all' => array_merge($base, [
                'pages_show_list',
                'pages_read_engagement',
                'pages_manage_metadata',
                'pages_messaging',
                'pages_read_user_content',
                'instagram_basic',
                'instagram_manage_messages',
                'whatsapp_business_management',
                'whatsapp_business_messaging',
            ]),
            'facebook', 'messenger' => array_merge($base, [
                'pages_show_list',
                'pages_read_engagement',
                'pages_manage_metadata',
                'pages_messaging',
                'pages_read_user_content',
            ]),
            'instagram' => array_merge($base, [
                'pages_show_list',
                'instagram_basic',
                'instagram_manage_messages',
                'pages_read_engagement',
            ]),
            'whatsapp' => array_merge($base, [
                'whatsapp_business_management',
                'whatsapp_business_messaging',
            ]),
            default => $this->getScopesFor('unified'),
        };
    }

    // ─── Step 2: Exchange Code for Short-Lived User Token ──────────────────────

    /**
     * Exchange the authorization code (from callback URL) for a short-lived
     * user access token (valid ~1 hour).
     */
    public function exchangeCode(string $code, string $redirectUri): string
    {
        $response = Http::get("{$this->graphBase}/{$this->graphVersion}/oauth/access_token", [
            'client_id'     => $this->appId,
            'client_secret' => $this->appSecret,
            'redirect_uri'  => $redirectUri,
            'code'          => $code,
        ]);

        if ($response->failed() || !$response->json('access_token')) {
            throw new \RuntimeException(
                'Failed to exchange code: ' . ($response->json('error.message') ?? $response->body())
            );
        }

        return $response->json('access_token');
    }

    // ─── Step 3: Extend to Long-Lived Token ───────────────────────────────────

    /**
     * Exchange a short-lived user token for a long-lived one (~60 days).
     */
    public function getLongLivedToken(string $shortLivedToken): string
    {
        $response = Http::get("{$this->graphBase}/{$this->graphVersion}/oauth/access_token", [
            'grant_type'        => 'fb_exchange_token',
            'client_id'         => $this->appId,
            'client_secret'     => $this->appSecret,
            'fb_exchange_token' => $shortLivedToken,
        ]);

        if ($response->failed() || !$response->json('access_token')) {
            throw new \RuntimeException(
                'Failed to get long-lived token: ' . ($response->json('error.message') ?? $response->body())
            );
        }

        return $response->json('access_token');
    }

    // ─── Step 4a: Get Facebook Pages (with per-page tokens) ───────────────────

    /**
     * Fetch all Facebook Pages managed by the user.
     * Each page comes with its own permanent Page Access Token.
     *
     * @return array [['id' => ..., 'name' => ..., 'access_token' => ..., 'category' => ...], ...]
     */
    public function getPages(string $userToken): array
    {
        $response = Http::get("{$this->graphBase}/{$this->graphVersion}/me/accounts", [
            'access_token' => $userToken,
            'fields'       => 'id,name,category,picture{url},access_token,tasks,instagram_business_account{id,name,username,profile_picture_url}',
        ]);

        if ($response->failed()) {
            throw new \RuntimeException(
                'Failed to fetch pages: ' . ($response->json('error.message') ?? $response->body())
            );
        }

        return $response->json('data', []);
    }

    // ─── Step 4b: Get Instagram Accounts linked to pages ──────────────────────

    /**
     * Get the Instagram Business Account linked to a Facebook Page.
     */
    public function getInstagramAccount(string $pageId, string $pageToken): ?array
    {
        $response = Http::get("{$this->graphBase}/{$this->graphVersion}/{$pageId}", [
            'access_token' => $pageToken,
            'fields'       => 'instagram_business_account{id,name,username,profile_picture_url}',
        ]);

        if ($response->failed()) {
            return null;
        }

        return $response->json('instagram_business_account');
    }

    // ─── Step 4c: Get WhatsApp Phone Numbers under WABA ───────────────────────

    /**
     * Fetch WhatsApp phone numbers under a WABA using the user token.
     */
    public function getWhatsAppPhoneNumbers(string $wabaId, string $userToken): array
    {
        $response = Http::withToken($userToken)
            ->get("{$this->graphBase}/{$this->graphVersion}/{$wabaId}/phone_numbers", [
                'fields' => 'id,display_phone_number,verified_name,quality_rating',
            ]);

        if ($response->failed()) {
            return [];
        }

        return $response->json('data', []);
    }

    /**
     * Fetch all WhatsApp Business Accounts (WABAs) managed by the user.
     */
    public function getWhatsAppBusinessAccounts(string $userToken): array
    {
        $response = Http::withToken($userToken)
            ->get("{$this->graphBase}/{$this->graphVersion}/me/whatsapp_business_accounts");

        if ($response->failed()) {
            return [];
        }

        return $response->json('data', []);
    }

    // ─── Helper: Verify Token Validity ────────────────────────────────────────

    /**
     * Debug/inspect a token using the app token (appId|appSecret).
     */
    public function inspectToken(string $token): array
    {
        $appToken = $this->appId . '|' . $this->appSecret;
        $response = Http::get("{$this->graphBase}/debug_token", [
            'input_token'  => $token,
            'access_token' => $appToken,
        ]);

        return $response->json('data', []);
    }

    // ─── Step 6: Subscribe Page to Webhooks ───────────────────────────────────

    /**
     * Subscribe the Facebook Page to the Meta App so it sends webhooks for messages and comments.
     */
    public function subscribePage(string $pageId, string $pageToken): bool
    {
        $response = Http::post("{$this->graphBase}/{$this->graphVersion}/{$pageId}/subscribed_apps", [
            'access_token'      => $pageToken,
            'subscribed_fields' => 'messages,messaging_postbacks,messaging_optins,message_reads,message_reactions,feed'
        ]);

        if ($response->failed()) {
            Log::error('[MetaOAuthService] Failed to subscribe page: ' . $response->body());
            return false;
        }

        return true;
    }

    /**
     * Subscribe the Instagram Business Account to the Meta App.
     */
    public function subscribeInstagram(string $igAccountId, string $pageToken): bool
    {
        $response = Http::post("{$this->graphBase}/{$this->graphVersion}/{$igAccountId}/subscribed_apps", [
            'access_token'      => $pageToken,
            'subscribed_fields' => 'messages,messaging_postbacks,messaging_optins,messaging_seen,comments,mentions'
        ]);

        if ($response->failed()) {
            Log::error('[MetaOAuthService] Failed to subscribe Instagram: ' . $response->body());
            return false;
        }

        return true;
    }
}

