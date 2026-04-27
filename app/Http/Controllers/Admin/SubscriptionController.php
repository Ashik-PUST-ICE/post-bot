<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\OrderService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;


class SubscriptionController extends Controller
{
    use ResponseTrait;
    public $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService;
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

    public function index(Request $request)
    {
        $data['activeSubscription'] = 'active';
        $data['pageTitle'] = __('My Subscription');
        $data['title'] = __('My Subscription');

        $subscriptionService = new \App\Http\Services\SubscriptionService();
        $data['userPackage'] = $subscriptionService->getCurrentPackage();
        $data['packageHistories'] = $subscriptionService->getAllUserPackageByUserId(auth()->id(), 10);
        $data['orderHistories'] = $subscriptionService->getAllOrderByUserId(auth()->id(), 10);

        if (!is_null($request->id)) {
            $request->merge(['duration_type' => 1]);
            $gatewayService = new \App\Http\Services\GatewayService();
            $data['gateways'] = $gatewayService->getActiveAll(auth()->user()->tenant_id);
        } else {
            $data['gateways'] = null;
        }

        $data['orders'] = \App\Models\SubscriptionOrder::where('user_id', auth()->id())->get();
        $count = 0;
        foreach ($data['orders'] as $item) {
            if ($item->payment_status == 0) {
                $count++;
            }
        }
        $data['pendingData'] = $count;

        return view('admin.subscriptions.index', $data);
    }
}
