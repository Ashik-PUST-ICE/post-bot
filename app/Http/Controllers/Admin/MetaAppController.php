<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetaAppConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MetaAppController extends Controller
{
    /**
     * Show the Meta App configuration page.
     */
    public function index()
    {
        $data['title']        = __('Meta App Configuration');
        $data['activeMetaApp'] = 'active';
        $data['config']       = MetaAppConfig::forUser(auth()->id());

        // Webhook URL = /webhook/meta/{userId} — one unique URL per admin account
        $data['webhookUrl'] = route('webhook.meta.verify', ['userId' => auth()->id()]);

        // OAuth Callback URL — must be registered in Meta App → Facebook Login → Valid OAuth Redirect URIs
        $data['oauthCallbackUrl'] = route('admin.meta-oauth.callback');

        return view('admin.meta-app.index', $data);
    }

    /**
     * Save Meta App credentials — section-aware (tab-based).
     *
     * The blade sends a hidden `section` field identifying which tab's Save was clicked:
     *   'app'       → Meta App ID + Secret
     *   'facebook'  → Facebook Page ID + Page Access Token
     *   'whatsapp'  → WA Phone Number ID + WABA ID + System User Token
     *   'instagram' → IG Business Account ID + Token
     *
     * Only that section's fields are updated — other columns are never touched.
     */
    public function update(Request $request)
    {
        $section = $request->input('section', 'app');

        $request->validate([
            'section'               => 'nullable|string|in:app,facebook,whatsapp,instagram',
            'fb_app_id'             => 'nullable|string|max:50',
            'fb_app_secret'         => 'nullable|string|max:255',
            'fb_page_access_token'  => 'nullable|string',
            'fb_page_id'            => 'nullable|string|max:50',
            'wa_phone_number_id'    => 'nullable|string|max:50',
            'wa_business_account_id'=> 'nullable|string|max:50',
            'wa_access_token'       => 'nullable|string',
            'ig_access_token'       => 'nullable|string',
            'ig_user_id'            => 'nullable|string|max:50',
        ]);

        try {
            DB::beginTransaction();

            $config     = MetaAppConfig::forUser(auth()->id());
            $updateData = [];

            if ($section === 'app') {
                $updateData['fb_app_id'] = $request->fb_app_id;
                if ($request->filled('fb_app_secret')) {
                    $updateData['fb_app_secret'] = $request->fb_app_secret;
                }
            }

            if ($section === 'facebook') {
                $updateData['fb_page_id'] = $request->fb_page_id;
                if ($request->filled('fb_page_access_token')) {
                    $updateData['fb_page_access_token'] = $request->fb_page_access_token;
                }
            }

            if ($section === 'whatsapp') {
                $updateData['wa_phone_number_id']     = $request->wa_phone_number_id;
                $updateData['wa_business_account_id'] = $request->wa_business_account_id;
                if ($request->filled('wa_access_token')) {
                    $updateData['wa_access_token'] = $request->wa_access_token;
                }
            }

            if ($section === 'instagram') {
                $updateData['ig_user_id'] = $request->ig_user_id;
                if ($request->filled('ig_access_token')) {
                    $updateData['ig_access_token'] = $request->ig_access_token;
                }
            }

            if (!empty($updateData)) {
                $config->update($updateData);
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => __(UPDATED_SUCCESSFULLY)]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('[MetaAppConfig] Update failed: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => getErrorMessage($e, $e->getMessage())]);
        }
    }


    /**
     * Regenerate the webhook verify token.
     */
    public function regenerateVerifyToken()
    {
        try {
            $config = MetaAppConfig::forUser(auth()->id());
            $config->update(['webhook_verify_token' => \Illuminate\Support\Str::random(40)]);
            return response()->json([
                'status'  => true,
                'message' => __('Verify token regenerated.'),
                'token'   => $config->fresh()->webhook_verify_token,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Live-check each platform connection using MetaService.
     * Called via AJAX from the Meta App Config page.
     */
    public function checkConnection()
    {
        $config  = MetaAppConfig::forUser(auth()->id());
        $service = new \App\Services\MetaService($config);

        return response()->json([
            'status'    => true,
            'facebook'  => $service->checkFacebookConnection(),
            'whatsapp'  => $service->checkWhatsAppConnection(),
            'instagram' => $service->checkInstagramConnection(),
        ]);
    }
}
