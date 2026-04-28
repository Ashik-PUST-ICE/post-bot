<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\PlatformConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InboxController extends Controller
{
    /**
     * Show the inbox page (view shell only — data loaded via DataTables AJAX).
     */
    public function index()
    {
        $data['title']       = __('Inbox');
        $data['activeInbox'] = 'active';

        $data['platforms'] = PlatformConnection::where('user_id', auth()->id())
            ->where('status', STATUS_ACTIVE)->get();

        $data['counts'] = [
            'all'       => Conversation::where('user_id', auth()->id())->count(),
            'open'      => Conversation::where('user_id', auth()->id())->where('status', CONVERSATION_STATUS_OPEN)->count(),
            'pending'   => Conversation::where('user_id', auth()->id())->where('status', CONVERSATION_STATUS_PENDING)->count(),
            'resolved'  => Conversation::where('user_id', auth()->id())->where('status', CONVERSATION_STATUS_RESOLVED)->count(),
            'escalated' => Conversation::where('user_id', auth()->id())->where('status', CONVERSATION_STATUS_ESCALATED)->count(),
        ];

        return view('admin.inbox.index', $data);
    }

    /**
     * DataTables server-side AJAX for inbox conversations.
     * Supports ?status=, ?platform= query filters passed by the JS module.
     */
    public function getData(Request $request)
    {
        $query = Conversation::where('user_id', auth()->id())
            ->with('platformConnection')
            ->select([
                'id', 'platform_type', 'contact_name', 'contact_id',
                'last_message', 'status', 'ai_replied_count',
                'last_message_at', 'platform_connection_id',
            ]);

        // Status tab filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Platform dropdown filter
        if ($request->filled('platform')) {
            $query->where('platform_type', $request->platform);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('platform', function ($row) {
                $icon  = platformIcons($row->platform_type);
                $color = platformColors($row->platform_type);
                $label = platformTypes($row->platform_type);
                return '<span class="d-flex align-items-center cg-6">'
                    . '<i class="' . $icon . ' fs-16" style="color:' . $color . '"></i>'
                    . '<span class="fs-12 fw-500">' . $label . '</span></span>';
            })
            ->addColumn('contact', function ($row) {
                $initials = strtoupper(substr($row->contact_name ?: 'U', 0, 2));
                return '<div class="d-flex align-items-center cg-10">'
                    . '<div class="wh-36 bd-ra-50 d-flex align-items-center justify-content-center text-white fs-13 fw-700 flex-shrink-0" style="background:#6366f1">'
                    . $initials . '</div>'
                    . '<div><p class="fs-13 fw-600 text-textBlack">' . e($row->contact_name ?: __('Unknown')) . '</p>'
                    . '<p class="fs-11 text-para-text">' . e($row->contact_id) . '</p></div></div>';
            })
            ->addColumn('last_message', function ($row) {
                return '<span class="fs-13 text-para-text text-truncate d-block" style="max-width:220px;">'
                    . e($row->last_message ?: '—') . '</span>';
            })
            ->addColumn('status_badge', function ($row) {
                return conversationStatusBadge($row->status);
            })
            ->addColumn('ai_replied', function ($row) {
                if ($row->ai_replied_count > 0) {
                    return '<span class="py-4 px-10 bd-ra-50 fs-11 fw-600" style="background:#6366f11a;color:#6366f1;">'
                        . '<i class="fa-solid fa-robot me-3"></i>' . $row->ai_replied_count . '</span>';
                }
                return '<span class="fs-12 text-para-text">—</span>';
            })
            ->addColumn('last_at', function ($row) {
                return $row->last_message_at
                    ? '<span class="fs-12 text-para-text">' . $row->last_message_at->diffForHumans() . '</span>'
                    : '—';
            })
            ->addColumn('action', function ($row) {
                return '<a href="' . route('admin.inbox.show', $row->id) . '" '
                    . 'class="py-7 px-14 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-12 fw-500">'
                    . '<i class="fa-solid fa-eye me-4"></i>' . __('View') . '</a>';
            })
            ->filterColumn('contact_name', function ($query, $keyword) {
                $query->where('contact_name', 'like', "%{$keyword}%");
            })
            ->orderColumn('last_message_at', 'last_message_at $1')
            ->rawColumns(['platform', 'contact', 'last_message', 'status_badge', 'ai_replied', 'last_at', 'action'])
            ->make(true);
    }

    /**
     * Show single conversation thread.
     */
    public function show($id)
    {
        $conversation = Conversation::where('user_id', auth()->id())
            ->with(['platformConnection', 'messages' => fn($q) => $q->orderBy('id')])
            ->findOrFail($id);

        $data['title']        = __('Conversation #') . $id;
        $data['activeInbox']  = 'active';
        $data['conversation'] = $conversation;
        $data['messages']     = $conversation->messages;

        return view('admin.inbox.show', $data);
    }

    /**
     * Send a manual reply from the human admin.
     */
    public function reply(Request $request, $id)
    {
        $request->validate(['body' => 'required|string|max:4096']);

        try {
            DB::beginTransaction();

            $conversation = Conversation::where('user_id', auth()->id())->findOrFail($id);

            Message::create([
                'conversation_id' => $conversation->id,
                'user_id'         => auth()->id(),
                'tenant_id'       => auth()->user()->tenant_id,
                'direction'       => MESSAGE_DIRECTION_OUTBOUND,
                'sender_type'     => MESSAGE_SENDER_HUMAN_ADMIN,
                'body'            => $request->body,
                'message_type'    => 'text',
                'status'          => MESSAGE_STATUS_SENT,
                'is_approved'     => 1,
                'sent_at'         => now(),
            ]);

            $conversation->update([
                'last_message'     => \Illuminate\Support\Str::limit($request->body, 100),
                'last_message_at'  => now(),
                'human_taken_over' => 1,
            ]);

            DB::commit();
            return response()->json(['status' => true, 'message' => __('Reply sent.')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Update conversation status (resolve, escalate, etc.).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|integer|in:1,2,3,4']);

        try {
            $conversation = Conversation::where('user_id', auth()->id())->findOrFail($id);
            $conversation->update(['status' => $request->status]);
            return response()->json(['status' => true, 'message' => __('Status updated.')]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
