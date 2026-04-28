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

        return view('admin.meta-app.index', $data);
    }

    /**
     * Save Meta App credentials.
     * Tokens are validated before saving; secrets are stored encrypted server-side only.
     */
    public function update(Request $request)
    {
        $request->validate([
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

            $config = MetaAppConfig::forUser(auth()->id());

            $updateData = [
                'fb_app_id'              => $request->fb_app_id,
                'fb_page_id'             => $request->fb_page_id,
                'wa_phone_number_id'     => $request->wa_phone_number_id,
                'wa_business_account_id' => $request->wa_business_account_id,
                'ig_user_id'             => $request->ig_user_id,
            ];

            // Only update secrets/tokens if explicitly provided (non-empty)
            if ($request->filled('fb_app_secret')) {
                $updateData['fb_app_secret'] = $request->fb_app_secret;
            }
            if ($request->filled('fb_page_access_token')) {
                $updateData['fb_page_access_token'] = $request->fb_page_access_token;
            }
            if ($request->filled('wa_access_token')) {
                $updateData['wa_access_token'] = $request->wa_access_token;
            }
            if ($request->filled('ig_access_token')) {
                $updateData['ig_access_token'] = $request->ig_access_token;
            }

            $config->update($updateData);

            DB::commit();
            return response()->json(['status' => true, 'message' => __(UPDATED_SUCCESSFULLY)]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => getErrorMessage($e, $e->getMessage())]);
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
