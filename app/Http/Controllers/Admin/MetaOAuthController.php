<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetaAppConfig;
use App\Models\PlatformConnection;
use App\Services\MetaOAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            redirect_uri: route('admin.meta-oauth.callback'),
            platformType: $platform,
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

            // Step 4: Fetch assets based on platform
            $pages    = $oauthService->getPages($longToken);
            $wabaId   = $config->wa_business_account_id;
            $waPhones = [];

            if ($platform === 'whatsapp' && $wabaId) {
                $waPhones = $oauthService->getWhatsAppPhoneNumbers($wabaId, $longToken);
            }

            // Enrich pages with IG account info when connecting Instagram
            if ($platform === 'instagram') {
                foreach ($pages as &$page) {
                    $page['instagram_account'] = $oauthService->getInstagramAccount(
                        $page['id'],
                        $page['access_token']
                    );
                }
                unset($page);
            }

            // Store data in session for the picker view
            Session::put('meta_oauth_data', [
                'platform'   => $platform,
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

        $data['title']          = __('Select Account to Connect');
        $data['activePlatforms'] = 'active';
        $data['oauthData']       = $oauthData;

        return view('admin.platforms.oauth-picker', $data);
    }

    // ─── Step 4: Save Selected Page/Account ──────────────────────────────────

    /**
     * Save the chosen page or account as a PlatformConnection record.
     * Called from the picker view form submission.
     */
    public function savePage(Request $request)
    {
        $request->validate([
            'page_id'         => 'required|string',
            'page_name'       => 'required|string|max:255',
            'access_token'    => 'required|string',
            'platform_type'   => 'required|integer',
            'phone_number_id' => 'nullable|string',
            'ig_user_id'      => 'nullable|string',
        ]);

        $oauthData = Session::get('meta_oauth_data');
        if (!$oauthData) {
            return response()->json(['status' => false, 'message' => __('OAuth session expired.')]);
        }

        try {
            DB::beginTransaction();

            // For WA: also update the access token in MetaAppConfig for future API calls
            if ((int) $request->platform_type === PLATFORM_WHATSAPP) {
                MetaAppConfig::forUser(auth()->id())->update([
                    'wa_phone_number_id' => $request->phone_number_id,
                    'wa_access_token'    => $request->access_token,
                ]);
            }

            // For Instagram: also save IG token in MetaAppConfig
            if ((int) $request->platform_type === PLATFORM_INSTAGRAM) {
                MetaAppConfig::forUser(auth()->id())->update([
                    'ig_user_id'     => $request->ig_user_id ?: $request->page_id,
                    'ig_access_token' => $request->access_token,
                ]);
            }

            // For Facebook Page: save page token in MetaAppConfig too
            if ((int) $request->platform_type === PLATFORM_FACEBOOK_PAGE) {
                MetaAppConfig::forUser(auth()->id())->update([
                    'fb_page_id'           => $request->page_id,
                    'fb_page_access_token' => $request->access_token,
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

            Session::forget('meta_oauth_data');

            DB::commit();
            return response()->json([
                'status'   => true,
                'message'  => __('Platform connected successfully via OAuth!'),
                'redirect' => route('admin.platforms.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
