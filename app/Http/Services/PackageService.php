<?php

namespace App\Http\Services;

use App\Models\Currency;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Models\Package;
use App\Models\SubscriptionOrder;
use App\Models\User;
use App\Models\UserPackage;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;


class PackageService
{
    use ResponseTrait;

    public function getAllData($request)
    {
        $packages = Package::query();

        return datatables($packages)
            ->addIndexColumn()
            ->addColumn('name', function ($package) {
                return $package->name;
            })
            ->addColumn('icon', function ($data) {
                return '<div class="min-w-160 d-flex align-items-center cg-10">
                            <div class="flex-shrink-0 w-35 h-35 bd-one bd-c-stroke rounded-circle overflow-hidden bg-eaeaea d-flex justify-content-center align-items-center">
                                <img src="' . asset($data->icon) . '" alt="icon" class="rounded avatar-xs w-100">
                            </div>
                        </div>';
            })
            ->addColumn('monthly_price', function ($package) {
                return showPrice($package->monthly_price);
            })
            ->addColumn('yearly_price', function ($package) {
                return showPrice($package->yearly_price);
            })
            ->addColumn('status', function ($package) {
                if ($package->status == STATUS_ACTIVE) {
                    return '<div class="zBadge zBadge-done">' . __('Active') . '</div>';
                } else {
                    return '<div class="zBadge zBadge-inactive">' . __('Deactivate') . '</div>';
                }
            })
            ->addColumn('trail', function ($package) {
                if ($package->is_trail == ACTIVE) {
                    return '<div class="status-btn status-btn-blue font-13 radius-4">' . __('Yes') . '</div>';
                } else {
                    return '<div class="status-btn status-btn-red font-13 radius-4">' . __('No') . '</div>';
                }
            })
            ->addColumn('action', function ($package) {
                return '<div class="dropdown dropdown-one">
                           <button class="dropdown-toggle p-0 bg-transparent w-22 h-22 ms-auto bd-one bd-c-light-border rounded-circle fs-13 text-textBlack d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></button>
                           <ul class="dropdown-menu dropdownItem-one">
                              <li>
                                 <button class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10 edit" data-id="' . $package->id . '">
                                    <div class="d-flex"><i class="fa-solid fa-pen-to-square text-para-text fs-14"></i></div>
                                    <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap">' . __("Edit") . '</p>
                                 </button>
                              </li>
                              <li>
                                 <button onclick="deleteItem(\'' . route('super-admin.packages.destroy', $package->id) . '\', \'packageDataTable\')" class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10">
                                    <div class="d-flex"><i class="fa-solid fa-trash text-para-text fs-14"></i></div>
                                    <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap">' . __("Delete") . '</p>
                                 </button>
                              </li>
                           </ul>
                        </div>';
            })
            ->rawColumns(['name', 'icon', 'status', 'trail', 'action'])
            ->make(true);
    }

    public function getActiveAll()
    {
        return Package::where('status', ACTIVE)->get();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->get('id', '');
            if ($id != '') {
                $package = Package::findOrFail($request->id);
            } else {
                $package = new Package();
            }

            $package->name = $request->name;
            $package->slug = $request->slug;
            if ($request->hasFile('icon')) {
                $newFile = new FileManager();
                $uploaded = $newFile->upload('Package', $request->icon);
                if ($uploaded) {
                    $package->icon = 'storage/' . $uploaded->path;
                }
            }
            $package->page_limit = $request->page_limit;
            $package->message_limit = $request->message_limit;

            $package->others = json_encode($request->others);
            $package->status = $request->status ? ACTIVE : DEACTIVATE;
            $package->is_trail = $request->is_trail ? ACTIVE : DEACTIVATE;
            $package->is_default = $request->is_default ? ACTIVE : DEACTIVATE;
            $package->monthly_price = $request->monthly_price;
            $package->yearly_price = $request->yearly_price;
            $package->save();

            // user subscription update
            UserPackage::where('package_id', $package->id)->update([
                'page_limit' => $package->page_limit,
                'message_limit' => $package->message_limit,
            ]);

            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function getInfo($id)
    {
        $package = Package::find($id);
        if ($package) {
            $package->icon_url = asset($package->icon);
        }
        return $package;
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            if (Package::where('status', ACTIVE)->count() > 1) {
                Package::findOrFail($id)->delete();
                if (is_null(Package::where(['is_trail' => ACTIVE, 'status' => ACTIVE])->first())) {
                    Package::where(['status' => ACTIVE])->first()->update(['is_trail' => ACTIVE]);
                }
                DB::commit();
                $message = __(DELETED_SUCCESSFULLY);
                return $this->success([], $message);
            } else {
                $message = __("Trail package can not be deleted");
                return $this->error([], $message);
            }
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function getUserPackagesData($request)
    {
        $userPackages = UserPackage::query()
            ->join('users', 'user_packages.user_id', '=', 'users.id')
            ->join('packages', 'user_packages.package_id', '=', 'packages.id')
            ->join('subscription_orders', 'user_packages.order_id', '=', 'subscription_orders.id')
            ->join('gateways', 'subscription_orders.gateway_id', '=', 'gateways.id')
            ->orderBy('user_packages.id', 'desc')
            ->select(
                'user_packages.*',
                'users.name as userName',
                'subscription_orders.payment_status',
            );

        return datatables($userPackages)
            ->addColumn('user_name', function ($userPackage) {
                return $userPackage->userName;
            })
            ->addColumn('package_name', function ($userPackage) {
                return $userPackage->name;
            })
            ->addColumn('gateway', function ($userPackage) {
                return $userPackage->gatewaysName;
            })
            ->addColumn('payment_status', function ($userPackage) {
                if ($userPackage->payment_status == ORDER_PAYMENT_STATUS_PAID) {
                    return '<div class="zBadge zBadge-paid">Paid</div>';
                } elseif ($userPackage->payment_status == ORDER_PAYMENT_STATUS_PENDING) {
                    return '<div class="zBadge zBadge-pending">Pending</div>';
                } else {
                    return '<div class="zBadge zBadge-cancel">Cancelled</div>';
                }
            })->addColumn('start_date', function ($userPackage) {
                return date('Y-m-d', strtotime($userPackage->start_date));
            })->addColumn('end_date', function ($userPackage) {
                return date('Y-m-d', strtotime($userPackage->end_date));
            })->addColumn('status', function ($userPackage) {
                if ($userPackage->status == ACTIVE) {
                    return '<div class="zBadge zBadge-active">Active</div>';
                } else {
                    return '<div class="zBadge zBadge-inactive">Deactivate</div>';
                }
            })->addColumn('action', function ($userPackage) {
                return '<div class="dropdown dropdown-one">
                           <button class="dropdown-toggle p-0 bg-transparent w-22 h-22 ms-auto bd-one bd-c-light-border rounded-circle fs-13 text-textBlack d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></button>
                           <ul class="dropdown-menu dropdownItem-one">
                              <li>
                                 <button class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10 edit" data-id="' . $userPackage->id . '">
                                    <div class="d-flex"><i class="fa-solid fa-pen-to-square text-para-text fs-14"></i></div>
                                    <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap">' . __("Edit") . '</p>
                                 </button>
                              </li>
                           </ul>
                        </div>';
            })
            ->rawColumns(['user_name', 'package_name', 'payment_status', 'start_date', 'end_date', 'status', 'action'])
            ->make(true);
    }

    public function assignPackage($request)
    {
        DB::beginTransaction();
        try {
            $package = Package::findOrFail($request->package_id);
            $user = User::where('role', USER_ROLE_ADMIN)->findOrFail($request->user_id);
            $adminUser = User::where('role', USER_ROLE_SUPER_ADMIN)->first();

            $gateway = Gateway::where(['user_id' => $adminUser->id, 'slug' => 'cash'])->firstOrFail();
            $currency = Currency::where('current_currency', ACTIVE)->first()->currency_code;
            if (is_null($currency)) {
                throw new Exception(__('Please Add Currency'));
            }
            $gatewayCurrency = GatewayCurrency::where(['user_id' => $adminUser->id, 'gateway_id' => $gateway->id, 'currency' => $currency])->firstOrFail();

            $price = 0;
            $duration = 0;
            $discount = 0;
            if (in_array($request->duration_type, [DURATION_MONTH, DURATION_YEAR])) {
                if ($request->duration_type == DURATION_MONTH) {
                    $price = $package->monthly_price;
                    $duration = 30;
                } else {
                    $price = $package->yearly_price;
                    $duration = 365;
                }
            } else {
                throw new Exception(__(SOMETHING_WENT_WRONG));
            }

            $order = SubscriptionOrder::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'order_id' => uniqid(),
                'payment_status' => ORDER_PAYMENT_STATUS_PAID,
                'transaction_id' => str_replace("-", "", uuid_create(UUID_TYPE_RANDOM)),
                'system_currency' => $currency,
                'gateway_id' => $gateway->id,
                'gateway_currency' => $gatewayCurrency->currency,
                'duration_type' => $request->duration_type,
                'conversion_rate' => $gatewayCurrency->conversion_rate,
                'amount' => $price,
                'tax_amount' => 0,
                'tax_type' => 0,
                'discount' => $discount,
                'subtotal' => $price,
                'total' => $price,
                'transaction_amount' => $price * $gatewayCurrency->conversion_rate
            ]);

            setUserPackage($order->user_id, $package, $duration, $order->id);

            DB::commit();
            return $this->success([], __(ASSIGNED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function getAll()
    {
        return Package::query()->get();
    }
}
