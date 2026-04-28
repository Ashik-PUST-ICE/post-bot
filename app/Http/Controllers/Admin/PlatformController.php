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
                $class = $row->status == STATUS_ACTIVE ? 'success' : 'danger';
                $label = $row->status == STATUS_ACTIVE ? __('Active') : __('Inactive');
                return '<span class="zBadge zBadge-' . $class . '">' . $label . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-flex align-items-center cg-8">'
                    . '<button class="zBtn-icon-outline edit-platform-btn" data-id="' . $row->id . '">'
                    . '<i class="fa-solid fa-pen"></i></button>'
                    . '<button class="zBtn-icon-outline delete-platform-btn" data-route="' . route('admin.platforms.destroy', $row->id) . '">'
                    . '<i class="fa-solid fa-trash text-red"></i></button>'
                    . '</div>';
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

            DB::commit();
            return response()->json(['status' => true, 'message' => __('Platform connected successfully.')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => getErrorMessage($e, $e->getMessage())]);
        }
    }

    /**
     * Return platform info for edit modal (AJAX).
     */
    public function getInfo(Request $request)
    {
        $connection = PlatformConnection::where('user_id', auth()->id())
            ->findOrFail($request->id);
        return response()->json(['status' => true, 'data' => $connection]);
    }

    /**
     * Update an existing platform connection.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'platform_name' => 'required|string|max:255',
            'access_token'  => 'nullable|string',
            'phone_number'  => 'nullable|string|max:50',
        ]);

        try {
            DB::beginTransaction();

            $connection = PlatformConnection::where('user_id', auth()->id())->findOrFail($id);
            $connection->update([
                'platform_name'     => $request->platform_name,
                'access_token'      => $request->access_token ?? $connection->access_token,
                'phone_number'      => $request->phone_number,
                'auto_reply_status' => $request->input('auto_reply_status', DEACTIVATE),
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
