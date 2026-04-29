<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\DashboardService;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\PlatformConnection;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    use ResponseTrait;
    public $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index(Request $request)
    {
        $userId = auth()->id();

        $data['title']           = __('Dashboard');
        $data['activeDashboard'] = 'active';

        // ── Summary cards ──────────────────────────────────────────────────
        $data['totalMessages']   = Message::where('user_id', $userId)->count();
        $data['totalReplied']    = Message::where('user_id', $userId)
            ->where('direction', MESSAGE_DIRECTION_OUTBOUND)->count();
        $data['aiReplied']       = Message::where('user_id', $userId)
            ->where('sender_type', MESSAGE_SENDER_AI)->count();
        $data['humanReplied']    = Message::where('user_id', $userId)
            ->where('sender_type', MESSAGE_SENDER_HUMAN_ADMIN)->count();
        $data['totalConversations'] = Conversation::where('user_id', $userId)->count();
        $data['openConversations']  = Conversation::where('user_id', $userId)
            ->where('status', CONVERSATION_STATUS_OPEN)->count();
        $data['resolvedConversations'] = Conversation::where('user_id', $userId)
            ->where('status', CONVERSATION_STATUS_RESOLVED)->count();
        $data['escalatedConversations'] = Conversation::where('user_id', $userId)
            ->where('status', CONVERSATION_STATUS_ESCALATED)->count();
        $data['connectedPlatforms'] = PlatformConnection::where('user_id', $userId)
            ->where('status', STATUS_ACTIVE)->count();

        // ── Reply rate ─────────────────────────────────────────────────────
        $inbound = Message::where('user_id', $userId)
            ->where('direction', MESSAGE_DIRECTION_INBOUND)->count();
        $data['replyRate'] = $inbound > 0
            ? round(($data['totalReplied'] / $inbound) * 100, 1)
            : 0;

        // ── Last 7 days chart data ─────────────────────────────────────────
        $data['chartLabels']   = [];
        $data['chartInbound']  = [];
        $data['chartOutbound'] = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $data['chartLabels'][]   = now()->subDays($i)->format('M d');
            $data['chartInbound'][]  = Message::where('user_id', $userId)
                ->where('direction', MESSAGE_DIRECTION_INBOUND)
                ->whereDate('created_at', $date)->count();
            $data['chartOutbound'][] = Message::where('user_id', $userId)
                ->where('direction', MESSAGE_DIRECTION_OUTBOUND)
                ->whereDate('created_at', $date)->count();
        }

        // ── Platform breakdown ─────────────────────────────────────────────
        $data['platformBreakdown'] = Conversation::where('user_id', $userId)
            ->selectRaw('platform_type, COUNT(*) as total')
            ->groupBy('platform_type')
            ->get();

        return view('admin.dashboard', $data);
    }

    public function userOverviewChartData(Request $request)
    {
        return response()->json([]);
    }

}
