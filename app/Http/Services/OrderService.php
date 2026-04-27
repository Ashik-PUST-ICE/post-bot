<?php

namespace App\Http\Services;

use App\Models\Package;
use App\Models\SubscriptionOrder;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class OrderService
{
    use ResponseTrait;

    public function orderAmount()
    {
        return SubscriptionOrder::sum('amount');
    }

    public function allOrder($request)
    {
        $status = 0;
        if ($request->status == 'Paid') {
            $status = PAYMENT_STATUS_PAID;
        } else if ($request->status == 'Pending') {
            $status = PAYMENT_STATUS_PENDING;
        } else if ($request->status == 'Bank') {
            $status = PAYMENT_STATUS_PENDING;
        } else if ($request->status == 'Cancelled') {
            $status = PAYMENT_STATUS_CANCELLED;
        }

        $orders = SubscriptionOrder::query()
            ->leftJoin('packages', 'subscription_orders.package_id', '=', 'packages.id')
            ->leftJoin('gateways', 'subscription_orders.gateway_id', '=', 'gateways.id')
            ->leftJoin('users', 'subscription_orders.user_id', '=', 'users.id')
            ->leftJoin('file_managers', ['subscription_orders.bank_deposit_slip_id' => 'file_managers.id'])
            ->orderByDesc('subscription_orders.id')
            ->select([
                'subscription_orders.*',
                'packages.name as packageName',
                'gateways.title as gatewayTitle',
                'gateways.slug as gatewaySlug'
            ]);

        if ($request->status == 'Bank') {
            $orders->whereNotNull('subscription_orders.bank_deposit_slip_id');
        }
        if ($request->status == 'All') {
            $orders = $orders;
        } else {
            $orders = $orders->where('subscription_orders.payment_status', $status);
        }

        return datatables($orders)
            ->addIndexColumn()
            ->addColumn('package', function ($order) {
                return '<h6>' . $order->packageName . '</h6>';
            })
            ->addColumn('amount', function ($order) {
                return showPrice($order->total);
            })
            ->addColumn('gateway', function ($order) {
                if ($order->gatewaySlug == 'bank') {
                    return '<a href="' . getFileUrl($order->folder_name, $order->file_name) . '" title="' . __('Bank slip download') . '" download>' . $order->gatewayTitle . '</a>';
                }
                return $order->gatewayTitle;
            })
            ->addColumn('status', function ($order) {
                if ($order->payment_status == PAYMENT_STATUS_PAID) {
                    return '<div class="zBadge zBadge-paid">Paid</div>';
                } elseif ($order->payment_status == PAYMENT_STATUS_PENDING) {
                    return '<div class="zBadge zBadge-pending">Pending</div>';
                } else {
                    return '<div class="zBadge zBadge-cancel">Cancelled</div>';
                }
            })
            ->addColumn('action', function ($data) {
                $html = '<div class="d-flex justify-content-end align-items-center g-10">';

                $html .= '<button onclick="getEditModal(\'' . route('super-admin.subscriptions.order-details', $data->id) . '\'' . ', \'#edit-modal\')"      class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke rtl-button" title="Show">
                <img src="' . asset('assets/images/icon/eye.svg') . '" alt="edit" />
                    </button>';

                if ($data->payment_status == PAYMENT_STATUS_PENDING) {
                    $html .= "<button type='button' class='d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke rtl-button orderPayStatus' title='Status' data-id='$data->id'><img src='" . asset('assets/images/icon/settings.svg') . "'></button>";
                }
                if ($data->gatewaySlug == PAYMENT_STATUS_BANK) {
                    $html .= '<a href="' . getFileUrl($data->bank_deposit_slip_id) . '"  class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke rtl-button" title="Bank slip download" download><img src="' . asset("assets/images/icon/download.svg") . '"></a>';
                }
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['package', 'status', 'gateway', 'action'])
            ->make(true);
    }


    public function orderList()
    {
        $order = SubscriptionOrder::leftJoin('plans', 'subscription_orders.plan_id', '=', 'plans.id')
            ->leftJoin('gateways', 'subscription_orders.gateway_id', '=', 'gateways.id')
            ->leftJoin('users', 'subscription_orders.customer_id', '=', 'users.id')
            ->leftJoin('subscriptions', 'subscription_orders.subscription_id', '=', 'subscriptions.id')
            ->leftJoin('products', 'subscription_orders.product_id', '=', 'products.id')
            ->leftJoin('invoices', 'subscription_orders.invoice_id', '=', 'invoices.id')
            ->where('subscription_orders.user_id', auth()->id())
            ->where('subscription_orders.payment_status', PAYMENT_STATUS_PAID)
            ->select(
                'users.email',
                'subscription_orders.created_at',
                'subscription_orders.id',
                'subscription_orders.total as amount',
                'plans.name as plan_name',
                'gateways.title as gateway_name',
                'products.name as product_name',
                'invoices.invoice_id as invoiceId',
                'subscriptions.subscription_id as subscriptionId',
            )
            ->orderBy('subscription_orders.id', 'desc');

        return datatables($order)
            ->addIndexColumn()
            ->addColumn('planName', function ($data) {
                return $data->plan_name;
            })
            ->addColumn('created_at', function ($data) {
                return $data->created_at?->format('Y-m-d');
            })
            ->addColumn('amount', function ($data) {
                return showPrice($data->amount);
            })
            ->addColumn('gateway', function ($data) {
                return $data->gateway_name;
            })
            ->addColumn('customer', function ($data) {
                return $data->email;
            })
            ->addColumn('productName', function ($data) {
                return $data->product_name;
            })
            ->addColumn('invoice', function ($data) {
                return $data->invoiceId;
            })
            ->addColumn('subscription', function ($data) {
                return $data->subscriptionId;
            })
            ->rawColumns(['created_at', 'amount'])
            ->make(true);
    }


    public function getOrder($id)
    {
        try {
            $data['order'] = SubscriptionOrder::with('gateway')->find($id);
            if (is_null($data['order'])) {
                return $this->error([], getMessage(SOMETHING_WENT_WRONG));
            }
            return $data['order'];
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function paymentStatusUpdate($request)
    {
        DB::beginTransaction();
        try {
            $subscriptionOrder = SubscriptionOrder::findOrFail($request->id);
            if ($request->payment_status == PAYMENT_STATUS_PAID) {
                $subscriptionOrder->payment_status = PAYMENT_STATUS_PAID;
                $subscriptionOrder->transaction_id = str_replace("-", "", uuid_create(UUID_TYPE_RANDOM));
                $duration = 0;
                if ($subscriptionOrder->duration_type == DURATION_MONTH) {
                    $duration = 30;
                } elseif ($subscriptionOrder->duration_type == DURATION_YEAR) {
                    $duration = 365;
                }
                $package = Package::findOrFail($subscriptionOrder->package_id);
                setUserPackage($subscriptionOrder->user_id, $package, $duration, $subscriptionOrder->id);
            } elseif ($request->payment_status == PAYMENT_STATUS_CANCELLED) {
                $subscriptionOrder->payment_status = PAYMENT_STATUS_CANCELLED;
            } else {
                $subscriptionOrder->payment_status = PAYMENT_STATUS_PENDING;
            }
            $subscriptionOrder->save();

            DB::commit();
            return $this->success([], getMessage(__(STATUS_UPDATED_SUCCESSFULLY)));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], getMessage(__(SOMETHING_WENT_WRONG)));
        }
    }
}
