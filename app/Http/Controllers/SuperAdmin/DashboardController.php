<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Services\DashboardService;
use App\Models\Package;
use App\Models\PlatformConnection;
use App\Models\SubscriptionOrder;
use App\Models\User;
use App\Models\UserPackage;
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
        $data['title'] = __('Dashboard');
        $data['activeDashboard'] = 'active';

        $data['totalOrganizations'] = User::query()
            ->whereNotNull('tenant_id')
            ->distinct()
            ->count('tenant_id');

        $data['businessUsers'] = User::query()
            ->where('role', USER_ROLE_ADMIN)
            ->count();

        $data['connectedPlatforms'] = PlatformConnection::query()
            ->where('status', STATUS_ACTIVE)
            ->count();

        $data['subscriptionPlans'] = Package::query()->count();
        $data['activeSubscriptions'] = UserPackage::query()
            ->where('status', ACTIVE)
            ->count();

        $data['paidRevenue'] = (float) SubscriptionOrder::query()
            ->where('payment_status', PAYMENT_STATUS_PAID)
            ->sum('total');

        $data['paidOrdersTotal'] = SubscriptionOrder::query()
            ->where('payment_status', PAYMENT_STATUS_PAID)
            ->count();

        $data['revenueThisMonth'] = (float) SubscriptionOrder::query()
            ->where('payment_status', PAYMENT_STATUS_PAID)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total');

        $data['pendingOrders'] = SubscriptionOrder::query()
            ->where('payment_status', PAYMENT_STATUS_PENDING)
            ->count();

        $data['newBusinessUsersWeek'] = User::query()
            ->where('role', USER_ROLE_ADMIN)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        // ── Chart labels (last 7 days) + signups only ───────────────────────
        $data['chartLabels'] = [];
        $data['chartSignups'] = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $data['chartLabels'][] = now()->subDays($i)->format('M d');
            $data['chartSignups'][] = User::query()
                ->where('role', USER_ROLE_ADMIN)
                ->whereDate('created_at', $date)
                ->count();
        }

        // ── Per-plan: active users + paid revenue + order count ─────────────
        $revenueByPackage = SubscriptionOrder::query()
            ->where('payment_status', PAYMENT_STATUS_PAID)
            ->selectRaw('package_id, SUM(COALESCE(total,0)) as total_revenue, COUNT(*) as paid_orders')
            ->groupBy('package_id')
            ->get()
            ->keyBy('package_id');

        $activeUsersByPackage = UserPackage::query()
            ->where('status', ACTIVE)
            ->selectRaw('package_id, COUNT(DISTINCT user_id) as user_count')
            ->groupBy('package_id')
            ->pluck('user_count', 'package_id');

        $planRows = Package::query()
            ->orderBy('name')
            ->get()
            ->map(function (Package $p) use ($revenueByPackage, $activeUsersByPackage) {
                $row = $revenueByPackage->get($p->id);

                return (object) [
                    'id' => $p->id,
                    'name' => $p->name,
                    'active_users' => (int) ($activeUsersByPackage[$p->id] ?? 0),
                    'paid_orders' => $row ? (int) $row->paid_orders : 0,
                    'paid_revenue' => $row ? (float) $row->total_revenue : 0.0,
                ];
            });

        $data['planRows'] = $planRows;
        $data['planRevenueLabels'] = $planRows->pluck('name')->values()->all();
        $data['planRevenueValues'] = $planRows->pluck('paid_revenue')->map(fn ($v) => round((float) $v, 2))->values()->all();

        return view('sadmin.dashboard', $data);
    }

    public function userOverviewChartData(Request $request)
    {
        return response()->json([]);
    }
}
