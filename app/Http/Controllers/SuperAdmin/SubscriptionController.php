<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Services\SubscriptionService;
use App\Http\Services\GatewayService;
use App\Http\Services\OrderService;
use App\Models\Bank;
use App\Models\SubscriptionOrder;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use ResponseTrait;

    public $subscriptionService;
    public $orderService;

    public function __construct()
    {
        $this->subscriptionService = new SubscriptionService;
        $this->orderService = new OrderService;
    }

    public function index(Request $request)
    {
        $data['activeSubscription'] = 'active';
        $data['pageTitle'] = __('My Subscription');
        $data['title'] = __('My Subscription');
        $data['userPackage'] = $this->subscriptionService->getCurrentPackage();
        $data['packageHistories'] = $this->subscriptionService->getAllUserPackageByUserId(auth()->id(), 10);
        $data['orderHistories'] = $this->subscriptionService->getAllOrderByUserId(auth()->id(), 10);
        if (!is_null($request->id)) {
            $request->merge(['duration_type' => 1]);
            $data['gateways'] = $this->getGateway($request);
        }
        return view('sadmin.subscriptions.index', $data);
    }

    public function getGateway(Request $request)
    {
        try {
            $user = User::where('role', USER_ROLE_SUPER_ADMIN)->first();
            if (is_null($user)) {
                throw new Exception(__(SOMETHING_WENT_WRONG));
            }

            $gatewayService = new GatewayService;
            $data['gateways'] = $gatewayService->getActiveAll($user->tenant_id);
            $data['package'] = $this->subscriptionService->getById($request->id);
            $data['durationType'] = $request->duration_type;
            $data['banks'] = Bank::where('tenant_id', null)->where('status', ACTIVE)->get();
            $data['startDate'] = now();
            if ($request->duration_type == DURATION_MONTH) {
                $data['endDate'] = Carbon::now()->addMonth();
            } else {
                $data['endDate'] = Carbon::now()->addYear();
            }
            return view('sadmin.subscriptions.partials.gateway-list', $data)->render();
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function getPackage()
    {
        $data['packages'] = $this->subscriptionService->getAllPackages();
        $data['currentPackage'] = $this->subscriptionService->getCurrentPackage();
        return view('sadmin.subscriptions.partials.package-list', $data)->render();
    }

    public function getCurrencyByGateway(Request $request)
    {
        $data = $this->subscriptionService->getCurrencyByGatewayId($request->id, 'subscription');
        return $this->success($data);
    }

    public function cancel()
    {
        $this->subscriptionService->cancel();
        return back()->with('success', __('Canceled Successful!'));
    }

    public function orders(Request $request)
    {
        if ($request->ajax()) {
            return $this->orderService->allOrder($request);
        } else {
            $data['title'] = __('All Orders');
            $data['activeSubscriptionIndex'] = 'active';
            return view('sadmin.order.orders', $data);
        }
    }

    public function ordersStatus(Request $request)
    {
        if ($request->ajax()) {
            return $this->orderService->allOrder($request);
        }
    }

    public function orderDetails($id)
    {
        $data['order'] = SubscriptionOrder::with('gateway')->find($id);
        return view('sadmin.order.single-order', $data);
    }

    public function orderGetInfo(Request $request)
    {
        $data = $this->orderService->getOrder($request->id);
        return $this->success($data);
    }

    public function orderPaymentStatusChange(Request $request)
    {
        return $this->orderService->paymentStatusUpdate($request);
    }
}
