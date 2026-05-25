<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PlatformController extends Controller
{
    /**
     * Show all connected platforms for the logged-in admin.
     */
    public function index()
    {
        $data['title']           = __('Platform Connections');
        $data['activePlatforms'] = 'active';
        $data['connections']     = PlatformConnection::where('user_id', auth()->id())
            ->orderByDesc('id')
            ->get();

        return view('admin.platforms.index', $data);
    }

    /**
     * DataTables server-side data for platforms.
     */
    public function getData()
    {
        $connections = PlatformConnection::where('user_id', auth()->id())
            ->select(['id', 'platform_type', 'platform_name', 'platform_id', 'auto_reply_status', 'status'])
            ->orderByDesc('id');

        return DataTables::of($connections)
            ->addIndexColumn()
            ->addColumn('platform_type', function ($row) {
                $icon  = platformIcons($row->platform_type);
                $color = platformColors($row->platform_type);
                $label = platformTypes($row->platform_type);
                return '<span class="d-flex align-items-center cg-8">'
                    . '<i class="' . $icon . ' fs-16" style="color:' . $color . '"></i>'
                    . '<span class="fs-13 fw-500">' . $label . '</span></span>';
            })
            ->addColumn('platform_id', function ($row) {
                return '<span class="fs-12 text-para-text">' . ($row->platform_id ?: '—') . '</span>';
            })
            ->addColumn('auto_reply', function ($row) {
                return '<div class="zCheck form-check form-switch">'
                    . '<input class="form-check-input platform-auto-reply-toggle" type="checkbox" role="switch"'
                    . ' data-route="' . route('admin.platforms.toggle-auto-reply', $row->id) . '"'
                    . ($row->auto_reply_status == STATUS_ACTIVE ? ' checked' : '') . '>'
                    . '</div>';
            })
            ->addColumn('status', function ($row) {
                $class = $row->status == STATUS_ACTIVE ? 'active' : 'deactivate';
                $label = $row->status == STATUS_ACTIVE ? __('Active') : __('Inactive');
                return '<span class="zBadge zBadge-' . $class . '">' . $label . '</span>';
            })
            ->addColumn('action', function ($row) {
                $resubRoute = route('admin.platforms.resubscribe', $row->id);
                return '<div class="dropdown dropdown-one">
                             <button class="dropdown-toggle p-0 bg-transparent w-22 h-22 ms-auto bd-one bd-c-light-border rounded-circle fs-13 text-textBlack d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></button>
                             <ul class="dropdown-menu dropdownItem-one">
                                <li>
                                   <button type="button" class="w-100 d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10 resubscribe-platform-btn" data-route="' . $resubRoute . '">
                                      <div class="d-flex"><i class="fa-solid fa-rotate text-para-text fs-14"></i></div>
                                      <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap mb-0">' . __("Re-subscribe Webhook") . '</p>
                                   </button>
                                </li>
                                <li>
                                   <button type="button" class="w-100 d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10 edit-platform-btn" data-id="' . $row->id . '">
                                      <div class="d-flex"><i class="fa-solid fa-pen-to-square text-para-text fs-14"></i></div>
                                      <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap mb-0">' . __("Edit") . '</p>
                                   </button>
                                </li>
                                <li>
                                   <button type="button" class="w-100 d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10 delete-platform-btn" data-route="' . route('admin.platforms.destroy', $row->id) . '">
                                      <div class="d-flex"><i class="fa-solid fa-trash text-para-text fs-14"></i></div>
                                      <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap mb-0">' . __("Delete") . '</p>
                                   </button>
                                </li>
                             </ul>
                          </div>';
            })
            ->rawColumns(['platform_type', 'platform_id', 'auto_reply', 'status', 'action'])
            ->make(true);
    }

    /**
     * Store a new platform connection.
     */
    public function store(Request $request)
    {
        $request->validate([
            'platform_type' => 'required|integer|in:1,2,3,4',
            'platform_name' => 'required|string|max:255',
            'platform_id'   => 'nullable|string|max:255',
            'access_token'  => 'nullable|string',
            'phone_number'  => 'nullable|string|max:50',
            'waba_id'       => 'nullable|string|max:255',
        ]);

        // ── Package limit check ────────────────────────────────────────────────
        $pageLimit = getAdminLimit(RULES_PAGE_LIMIT);
        if ($pageLimit === false) {
            return response()->json([
                'status'  => 'error',
                'message' => __('You do not have an active subscription. Please purchase a package to connect platforms.'),
            ]);
        }
        if ($pageLimit !== true && $pageLimit <= 0) {
            return response()->json([
                'status'  => 'error',
                'message' => __('You have reached your package platform limit. Please upgrade your plan to connect more platforms.'),
            ]);
        }
        // ──────────────────────────────────────────────────────────────────────

        try {
            DB::beginTransaction();

            PlatformConnection::create([
                'user_id'           => auth()->id(),
                'tenant_id'         => auth()->user()->tenant_id,
                'platform_type'     => $request->platform_type,
                'platform_name'     => $request->platform_name,
                'platform_id'       => $request->platform_id,
                'access_token'      => $request->access_token,
                'phone_number'      => $request->phone_number,
                'waba_id'           => $request->waba_id,
                'verify_token'      => \Illuminate\Support\Str::random(32),
                'auto_reply_status' => $request->input('auto_reply_status', DEACTIVATE),
                'status'            => STATUS_ACTIVE,
            ]);

            // Attempt to subscribe page to webhooks if manual connection is Facebook/Instagram
            if (in_array((int) $request->platform_type, [PLATFORM_FACEBOOK_PAGE, PLATFORM_MESSENGER, PLATFORM_INSTAGRAM])) {
                if ($request->platform_id && $request->access_token) {
                    $metaConfig = \App\Models\MetaAppConfig::forUser(auth()->id());
                    $oauthService = new \App\Services\MetaOAuthService($metaConfig);
                    $oauthService->subscribePage($request->platform_id, $request->access_token);
                }
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => __('Platform connected successfully.')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => getErrorMessage($e, $e->getMessage())]);
        }
    }


    public function getInfo(Request $request)
    {
        $connection = PlatformConnection::where('user_id', auth()->id())
            ->findOrFail($request->id);
        
        // Make the access_token visible for the edit modal
        $connection->makeVisible('access_token');
            
        return response()->json(['status' => true, 'data' => $connection]);
    }

    /**
     * Update an existing platform connection.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'platform_name' => 'required|string|max:255',
            'platform_id'   => 'nullable|string|max:255',
            'access_token'  => 'nullable|string',
            'phone_number'  => 'nullable|string|max:50',
            'status'        => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            $connection = PlatformConnection::where('user_id', auth()->id())->findOrFail($id);
            $connection->update([
                'platform_name'     => $request->platform_name,
                'platform_id'       => $request->platform_id,
                'access_token'      => $request->access_token ?? $connection->access_token,
                'phone_number'      => $request->phone_number,
                'auto_reply_status' => $request->input('auto_reply_status', DEACTIVATE),
                'status'            => $request->input('status', $connection->status),
            ]);

            DB::commit();
            return response()->json(['status' => true, 'message' => __(UPDATED_SUCCESSFULLY)]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => getErrorMessage($e, $e->getMessage())]);
        }
    }

    /**
     * Toggle auto-reply status.
     */
    public function toggleAutoReply($id)
    {
        try {
            $connection = PlatformConnection::where('user_id', auth()->id())->findOrFail($id);
            $connection->auto_reply_status = $connection->auto_reply_status == STATUS_ACTIVE
                ? DEACTIVATE
                : STATUS_ACTIVE;
            $connection->save();

            return response()->json(['status' => true, 'message' => __('Auto-reply status updated.')]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Re-subscribe a platform connection to Meta webhooks.
     * Useful when the subscription failed previously (e.g. invalid fields).
     * Uses the stored access_token — no need to redo the full OAuth flow.
     */
    public function resubscribe($id)
    {
        try {
            $connection = PlatformConnection::where('user_id', auth()->id())->findOrFail($id);

            // Make access_token visible for use
            $connection->makeVisible('access_token');

            if (empty($connection->access_token) || empty($connection->platform_id)) {
                return response()->json([
                    'status'  => false,
                    'message' => __('Missing access token or platform ID. Please reconnect this platform via OAuth.'),
                ]);
            }

            $metaConfig   = \App\Models\MetaAppConfig::forUser(auth()->id());
            $oauthService = new \App\Services\MetaOAuthService($metaConfig);

            $success = false;
            if ((int) $connection->platform_type === PLATFORM_INSTAGRAM) {
                $success = $oauthService->subscribeInstagram($connection->platform_id, $connection->access_token);
            } else {
                $success = $oauthService->subscribePage($connection->platform_id, $connection->access_token);
            }

            if ($success) {
                return response()->json([
                    'status'  => true,
                    'message' => __('Webhook re-subscribed successfully! Messages will now be delivered to your inbox.'),
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => __('Re-subscription failed. Check your Meta App credentials and try again.'),
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PlatformController::resubscribe failed', [
                'platform_id' => $id,
                'error'       => $e->getMessage(),
            ]);
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Delete a platform connection.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            PlatformConnection::where('user_id', auth()->id())->findOrFail($id)->delete();
            DB::commit();
            return response()->json(['status' => true, 'message' => __(DELETED_SUCCESSFULLY)]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => getErrorMessage($e, $e->getMessage())]);
        }
    }
}
