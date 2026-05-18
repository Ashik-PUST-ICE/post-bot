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
                return '<div class="dropdown dropdown-one">
                             <button class="dropdown-toggle p-0 bg-transparent w-22 h-22 ms-auto bd-one bd-c-light-border rounded-circle fs-13 text-textBlack d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></button>
                             <ul class="dropdown-menu dropdownItem-one">
                                <li>
                                   <button type="button" class="w-100 d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10 edit-template" data-id="' . $row->id . '">
                                      <div class="d-flex"><i class="fa-solid fa-pen-to-square text-para-text fs-14"></i></div>
                                      <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap mb-0">' . __("Edit") . '</p>
                                   </button>
                                </li>
                                <li>
                                   <button type="button" class="w-100 d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10 delete-template" data-route="' . route('admin.reply-templates.destroy', $row->id) . '" data-id="' . $row->id . '">
                                      <div class="d-flex"><i class="fa-solid fa-trash text-para-text fs-14"></i></div>
                                      <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap mb-0">' . __("Delete") . '</p>
                                   </button>
                                </li>
                             </ul>
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
