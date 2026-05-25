<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetaAppConfig;
use App\Models\PlatformConnection;
use App\Services\MetaOAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

/**
 * MetaOAuthController
 *
 * Handles the complete Meta OAuth 2.0 flow:
 *
 *   GET  /admin/meta-oauth/redirect?platform={facebook|instagram|whatsapp}
 *        → builds the Meta Login dialog URL and redirects the browser
 *
 *   GET  /admin/meta-oauth/callback?code=...&state=...
 *        → exchanges code for tokens, fetches pages/accounts, stores in session
 *        → redirects to page-picker view
 *
 *   POST /admin/meta-oauth/save-page
 *        → saves the chosen page/account as a PlatformConnection record
 */
class MetaOAuthController extends Controller
{
    // ─── Step 1: Start OAuth ──────────────────────────────────────────────────

    /**
     * Build the Facebook Login dialog URL and redirect.
     * Requires App ID + App Secret to be saved in MetaAppConfig.
     */
    public function redirect(Request $request)
    {
        $platform = $request->query('platform', 'facebook');
        $config   = MetaAppConfig::forUser(auth()->id());

        if (!$config->hasFacebook()) {
            return redirect()->route('admin.meta-app.index')
                ->with('error', __('Please save your Meta App ID and App Secret first.'));
        }

        // CSRF-style state token: platform|randomString — stored in session
        $state = $platform . '|' . Str::random(32);
        Session::put('meta_oauth_state', $state);

        $oauthService = new MetaOAuthService($config);

        $authUrl = $oauthService->buildAuthUrl(
            redirectUri:  route('admin.meta-oauth.callback'),
            platformType: 'unified',
            state:        $state
        );

        return redirect()->away($authUrl);
    }

    // ─── Step 2: Handle Callback ──────────────────────────────────────────────

    /**
     * Meta calls this URL after the user approves (or denies) permissions.
     * Exchanges code → short token → long token → fetches pages → redirects to picker.
     */
    public function callback(Request $request)
    {
        // User denied access
        if ($request->filled('error')) {
            return redirect()->route('admin.platforms.index')
                ->with('error', __('OAuth denied: ') . $request->query('error_description', 'Unknown error'));
        }

        // Validate state (CSRF protection)
        $storedState = Session::get('meta_oauth_state', '');
        $returnedState = $request->query('state', '');

        if (empty($storedState) || !hash_equals($storedState, $returnedState)) {
            return redirect()->route('admin.platforms.index')
                ->with('error', __('Invalid OAuth state. Please try again.'));
        }

        // Extract platform from state
        $platform = explode('|', $storedState)[0];
        Session::forget('meta_oauth_state');

        $config = MetaAppConfig::forUser(auth()->id());
        $oauthService = new MetaOAuthService($config);

        try {
            // Step 2: Code → Short-lived token
            $shortToken = $oauthService->exchangeCode(
                code:        $request->query('code'),
                redirectUri: route('admin.meta-oauth.callback')
            );

            // Step 3: Short-lived → Long-lived user token (~60 days)
            $longToken = $oauthService->getLongLivedToken($shortToken);

            // Step 4: Fetch ALL assets
            // 4a. Pages (now includes IG info)
            $pages = $oauthService->getPages($longToken);

            Log::info('[MetaOAuth] Pages fetched:', [
                'count' => count($pages),
                'pages' => collect($pages)->map(fn($p) => [
                    'id' => $p['id'],
                    'name' => $p['name'],
                    'has_ig' => isset($p['instagram_business_account']) ? 'yes' : 'no'
                ])
            ]);

            // 4b. Enrich Pages with Instagram info (legacy loop if needed, but redundant now)
            foreach ($pages as &$page) {
                if (!isset($page['instagram_account']) && isset($page['instagram_business_account'])) {
                    $page['instagram_account'] = $page['instagram_business_account'];
                }
            }
            unset($page);

            // 4c. WhatsApp Phone Numbers: Fetch all WABAs first, then phones for each
            $waPhones = [];
            try {
                $wabas = $oauthService->getWhatsAppBusinessAccounts($longToken);
                foreach ($wabas as $waba) {
                    $phones = $oauthService->getWhatsAppPhoneNumbers($waba['id'], $longToken);
                    foreach ($phones as $p) {
                        $p['waba_id'] = $waba['id']; // tag with WABA ID for saving later
                        $waPhones[] = $p;
                    }
                }
            } catch (\Exception $e) {
                Log::warning('[MetaOAuth] Failed to fetch WhatsApp assets: ' . $e->getMessage());
            }

            // Store data in session for the picker view
            Session::put('meta_oauth_data', [
                'platform'   => 'unified', // Tell picker to show everything
                'long_token' => $longToken,
                'pages'      => $pages,
                'wa_phones'  => $waPhones,
            ]);

            return redirect()->route('admin.meta-oauth.picker');
        } catch (\Exception $e) {
            return redirect()->route('admin.platforms.index')
                ->with('error', __('OAuth failed: ') . $e->getMessage());
        }
    }

    // ─── Step 3: Page / Account Picker ───────────────────────────────────────

    /**
     * Show the page/account picker view so the admin selects which
     * Facebook Page, Instagram account, or WhatsApp number to connect.
     */
    public function picker()
    {
        $oauthData = Session::get('meta_oauth_data');

        if (!$oauthData) {
            return redirect()->route('admin.platforms.index')
                ->with('error', __('OAuth session expired. Please try again.'));
        }

        $existingPlatformIds = PlatformConnection::where('user_id', auth()->id())
            ->pluck('platform_id')
            ->toArray();

        $data['title']               = __('Select Account to Connect');
        $data['activeMetaPicker']    = 'active';
        $data['oauthData']           = $oauthData;
        $data['existingPlatformIds'] = $existingPlatformIds;

        return view('admin.platforms.oauth-picker', $data);
    }

    // ─── Step 4: Save Selected Page/Account ──────────────────────────────────

    /**
     * Save the chosen page or account as a PlatformConnection record.
     * Called from the picker view form submission.
     */
    public function savePage(Request $request)
    {
        // ── Debug logging — helps trace 419 / validation failures ──────────────
        Log::info('[MetaOAuth] savePage called', [
            'user_id'        => auth()->id(),
            'ip'             => $request->ip(),
            'page_id'        => $request->input('page_id'),
            'page_name'      => $request->input('page_name'),
            'platform_type'  => $request->input('platform_type'),
            'phone_number_id'=> $request->input('phone_number_id'),
            'ig_user_id'     => $request->input('ig_user_id'),
            'fb_page_id'     => $request->input('fb_page_id'),
            'has_token'      => $request->input('access_token') ? 'yes' : 'no',
            'has_session'    => session()->has('meta_oauth_data') ? 'yes' : 'no',
        ]);
        // ──────────────────────────────────────────────────────────────────────

        $request->validate([
            'page_id'         => 'required|string',
            'page_name'       => 'required|string|max:255',
            'access_token'    => 'required|string',
            'platform_type'   => 'required|integer',
            'phone_number_id' => 'nullable|string',
            'ig_user_id'      => 'nullable|string',
            'fb_page_id'      => 'nullable|string',
        ]);

        $oauthData = Session::get('meta_oauth_data');
        if (!$oauthData) {
            return response()->json(['status' => false, 'message' => __('OAuth session expired.')]);
        }

        // ── Package limit check ────────────────────────────────────────────────
        $pageLimit = getAdminLimit(RULES_PAGE_LIMIT);
        if ($pageLimit === false) {
            return response()->json([
                'status'  => false,
                'message' => __('You do not have an active subscription. Please purchase a package to connect platforms.'),
            ]);
        }
        if ($pageLimit !== true && $pageLimit <= 0) {
            return response()->json([
                'status'  => false,
                'message' => __('You have reached your package platform limit. Please upgrade your plan to connect more platforms.'),
            ]);
        }
        // ──────────────────────────────────────────────────────────────────────

        try {
            DB::beginTransaction();

            $metaConfig = MetaAppConfig::forUser(auth()->id());

            // Facebook Page / Messenger: save page ID + page access token into MetaAppConfig.
            // Page access tokens are long-lived (never expire unless revoked).
            if (in_array((int) $request->platform_type, [PLATFORM_FACEBOOK_PAGE, PLATFORM_MESSENGER])) {
                $metaConfig->update([
                    'fb_page_id'           => $request->page_id,
                    'fb_page_access_token' => $request->access_token,
                ]);

                // Subscribe the page to webhooks automatically
                $oauthService = new MetaOAuthService($metaConfig);
                $oauthService->subscribePage($request->page_id, $request->access_token);
            }

            // Instagram: save IG user ID + page token into MetaAppConfig.
            // The page token is used to reply to Instagram DMs via the Messenger API.
            if ((int) $request->platform_type === PLATFORM_INSTAGRAM) {
                $metaConfig->update([
                    'ig_user_id'      => $request->ig_user_id ?: $request->page_id,
                    'ig_access_token' => $request->access_token,
                ]);

                $oauthService = new MetaOAuthService($metaConfig);
                // Subscribe the Instagram Account using Instagram-specific fields
                $oauthService->subscribeInstagram($request->page_id, $request->access_token);

                // Also subscribe the underlying Facebook Page if provided
                if ($request->filled('fb_page_id')) {
                    $oauthService->subscribePage($request->fb_page_id, $request->access_token);
                }
            }

            // WhatsApp: only save the Phone Number ID from OAuth.
            // ⚠️ We intentionally do NOT save the OAuth user token as wa_access_token here.
            // The OAuth token is a ~60-day user token; WhatsApp messaging requires
            // a permanent System User token. Admins must set that manually in Meta App Config.
            if ((int) $request->platform_type === PLATFORM_WHATSAPP) {
                $metaConfig->update([
                    'wa_phone_number_id' => $request->phone_number_id,
                ]);
            }

            // Create or update the PlatformConnection record
            PlatformConnection::updateOrCreate(
                [
                    'user_id'       => auth()->id(),
                    'platform_id'   => $request->page_id,
                    'platform_type' => $request->platform_type,
                ],
                [
                    'tenant_id'         => auth()->user()->tenant_id,
                    'platform_name'     => $request->page_name,
                    'platform_id'       => $request->page_id,
                    'access_token'      => $request->access_token,
                    'phone_number'      => $request->phone_number_id,
                    'verify_token'      => Str::random(32),
                    'auto_reply_status' => DEACTIVATE,
                    'status'            => STATUS_ACTIVE,
                ]
            );

            // Removed Session::forget('meta_oauth_data') so user can connect multiple accounts at once.

            DB::commit();

            $redirectRoute = route('admin.platforms.index');

            // WhatsApp: remind admin to set a permanent System User token
            if ((int) $request->platform_type === PLATFORM_WHATSAPP) {
                return response()->json([
                    'status'   => true,
                    'message'  => __('WhatsApp number connected! Please set a permanent System User Token in Meta App Config to enable messaging.'),
                    'redirect' => route('admin.meta-app.index'),
                ]);
            }

            return response()->json([
                'status'   => true,
                'message'  => __('Platform connected successfully via OAuth!'),
                'redirect' => $redirectRoute,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[MetaOAuth] savePage exception', [
                'user_id' => auth()->id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
