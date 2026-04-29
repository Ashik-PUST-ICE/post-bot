<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReplyTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReplyTemplateController extends Controller
{
    public function index()
    {
        $data['title']                 = __('Quick Reply Templates');
        $data['activeReplyTemplates']  = 'active';

        return view('admin.reply-templates.index', $data);
    }

    public function getData()
    {
        $query = ReplyTemplate::where('user_id', auth()->id())
            ->select(['id', 'title', 'content', 'platform', 'usage_count', 'status', 'created_at']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('platform_badge', function ($row) {
                $colors = [
                    'all'       => '#6366f1',
                    'facebook'  => '#1877f2',
                    'whatsapp'  => '#25d366',
                    'instagram' => '#e1306c',
                ];
                $color = $colors[$row->platform] ?? '#6b7280';
                return '<span class="py-3 px-10 bd-ra-50 fs-11 fw-600 text-white"
                    style="background:' . $color . '">'
                    . ucfirst($row->platform) . '</span>';
            })
            ->addColumn('preview', function ($row) {
                return '<span class="fs-13 text-para-text text-truncate d-block" style="max-width:300px;">'
                    . e(\Illuminate\Support\Str::limit($row->content, 80)) . '</span>';
            })
            ->addColumn('status_badge', function ($row) {
                return $row->status
                    ? '<span class="zBadge zBadge-active">' . __('Active') . '</span>'
                    : '<span class="zBadge zBadge-inactive">' . __('Inactive') . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-flex align-items-center cg-8">
                    <button class="btn-icon edit-template" data-id="' . $row->id . '" title="' . __('Edit') . '">
                        <i class="fa-solid fa-pen-to-square fs-14 text-para-text"></i>
                    </button>
                    <button class="btn-icon delete-template" data-id="' . $row->id . '"
                        data-route="' . route('admin.reply-templates.destroy', $row->id) . '"
                        title="' . __('Delete') . '">
                        <i class="fa-solid fa-trash fs-14 text-danger"></i>
                    </button>
                </div>';
            })
            ->rawColumns(['platform_badge', 'preview', 'status_badge', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required|string|max:4000',
            'platform' => 'required|string|in:all,facebook,whatsapp,instagram',
        ]);

        try {
            DB::beginTransaction();

            $id = $request->input('id');

            if ($id) {
                $template = ReplyTemplate::where('user_id', auth()->id())->findOrFail($id);
                $template->update([
                    'title'    => $request->title,
                    'content'  => $request->content,
                    'platform' => $request->platform,
                    'status'   => $request->boolean('status') ? 1 : 0,
                ]);
                $message = __(UPDATED_SUCCESSFULLY);
            } else {
                ReplyTemplate::create([
                    'user_id'   => auth()->id(),
                    'tenant_id' => auth()->user()->tenant_id,
                    'title'     => $request->title,
                    'content'   => $request->content,
                    'platform'  => $request->platform,
                    'status'    => 1,
                ]);
                $message = __(CREATED_SUCCESSFULLY);
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => getErrorMessage($e, $e->getMessage())]);
        }
    }

    public function getInfo(Request $request)
    {
        $template = ReplyTemplate::where('user_id', auth()->id())
            ->findOrFail($request->id);

        return response()->json(['status' => true, 'data' => $template]);
    }

    public function destroy($id)
    {
        try {
            ReplyTemplate::where('user_id', auth()->id())->findOrFail($id)->delete();
            return response()->json(['status' => true, 'message' => __(DELETED_SUCCESSFULLY)]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Called from the inbox — returns templates for the picker dropdown.
     */
    public function forInbox()
    {
        $templates = ReplyTemplate::where('user_id', auth()->id())
            ->where('status', 1)
            ->orderBy('title')
            ->get(['id', 'title', 'content', 'platform']);

        return response()->json(['status' => true, 'data' => $templates]);
    }
}
