<?php

namespace App\Http\Services;

use App\Models\GatewayCurrency;
use App\Models\Package;
use App\Models\SubscriptionOrder;
use App\Models\User;
use App\Models\UserPackage;

class SubscriptionService
{
    /**
     * Active subscription row for the user (snapshot: page_limit, message_limit, name, etc.).
     */
    public function getCurrentPackage($userId = null)
    {
        $userId = $userId == null ? auth()->id() : $userId;
        $ownerPackage = UserPackage::query()
            ->leftJoin('subscription_orders', 'subscription_orders.id', '=', 'user_packages.order_id')
            ->where('user_packages.user_id', $userId)
            ->whereIn('user_packages.status', [ACTIVE])
            ->whereDate('user_packages.end_date', '>=', now())
            ->select('user_packages.*', 'subscription_orders.duration_type')
            ->first();
        return $ownerPackage?->makeHidden(['created_at', 'updated_at', 'deleted_at', 'is_trail', 'order_id', 'package_id', 'user_id']);
    }

    public function getAllPackages()
    {
        return Package::where('status', ACTIVE)->where('is_trail', '!=', ACTIVE)->get();
    }

    public function getAllUserPackageByUserId($userId = null, $limit = null)
    {
        $userId = !is_null($userId) ? $userId : auth()->id();
        $orders = UserPackage::query()
            ->join('packages', 'user_packages.package_id', '=', 'packages.id')
            ->join('subscription_orders', 'subscription_orders.id', '=', 'user_packages.order_id')
            ->where('user_packages.user_id', $userId)
            ->select([
                'user_packages.*',
                'packages.name as packageName',
                'subscription_orders.total'
            ])
            ->orderByDesc('user_packages.id')
            ->when($limit, function ($q) use ($limit) {
                $q->limit($limit);
            })
            ->get();
        return $orders;
    }

    public function getAllOrderByUserId($userId = null, $limit = null)
    {
        $userId = !is_null($userId) ? $userId : auth()->id();
        return SubscriptionOrder::query()
            ->leftJoin('packages', 'subscription_orders.package_id', '=', 'packages.id')
            ->leftJoin('gateways', 'subscription_orders.gateway_id', '=', 'gateways.id')
            ->leftJoin('users', 'subscription_orders.user_id', '=', 'users.id')
            ->leftJoin('file_managers', ['subscription_orders.bank_deposit_slip_id' => 'file_managers.id'])
            ->select([
                'subscription_orders.*',
                'packages.name as packageName',
                'gateways.title as gatewayTitle',
                'gateways.slug as gatewaySlug',
                'file_managers.id as file_id',
            ])
            ->where('subscription_orders.user_id', $userId)
            ->orderByDesc('subscription_orders.id')
            ->when($limit, function ($q) use ($limit) {
                $q->limit($limit);
            })
            ->get();
    }

    public function getById($id)
    {
        $package = Package::query()->findOrFail($id);
        return $package?->makeHidden(['created_at', 'deleted_at', 'updated_at']);
    }

    public function getCurrencyByGatewayId($id, $type)
    {
        if ($type == 'subscription') {
            $userId = User::where('role', USER_ROLE_SUPER_ADMIN)->first()->id;
        } else {
            $userId = User::where('role', USER_ROLE_ADMIN)->first()->id;
        }
        $currencies = GatewayCurrency::where(['user_id' => $userId, 'gateway_id' => $id])->get();
        foreach ($currencies as $currency) {
            $currency->symbol = $currency->symbol;
        }
        return $currencies?->makeHidden(['created_at', 'updated_at', 'deleted_at', 'gateway_id', 'user_id']);
    }

    public function cancel()
    {
        $userPackage = UserPackage::query()
            ->where(['user_id' => auth()->id(), 'status' => ACTIVE])
            ->whereDate('end_date', '>=', now()->toDateTimeString())
            ->first();

        // send email and notification
        $customData = (object)[
            'username' => auth()->user()->name,
            'package' => $userPackage->name,
        ];
        $user = User::where('role', USER_ROLE_SUPER_ADMIN)->first();
        sendCommonNotification('subscription-cancel-notify-for-super-admin', [$user->id, auth()->id()], $customData);
        sendCommonEmailNotification('subscription-cancel-notify-for-super-admin', [$user->id, auth()->id()], $customData);

        // user package deactivate
        $userPackage->update(['status' => DEACTIVATE]);
    }
}
