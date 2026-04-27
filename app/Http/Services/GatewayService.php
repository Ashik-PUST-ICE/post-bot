<?php

namespace App\Http\Services;

use App\Models\Bank;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class GatewayService
{
    use ResponseTrait;

    public function getAll($tenant_id = null)
    {
        $tenant_id = $tenant_id == null ? null : $tenant_id;
        return Gateway::where('tenant_id', $tenant_id)->get();
    }

    public function getActiveAll($tenant_id = null)
    {
        $tenant_id = $tenant_id == null ? null : $tenant_id;
        return Gateway::where('tenant_id', $tenant_id)->where('status', ACTIVE)->get();
    }

    public function getActiveBanks()
    {
        $user_id = isset($user_id) ? $user_id : auth()->id();
        return Bank::where('user_id', $user_id)->where('status', ACTIVE)->get();
    }

    public function getCurrenciesByGatewayId($id)
    {
        $data['gateway'] = $this->getInfo($id);
        if ($data['gateway']->slug == 'bank') {
            $data['banks'] = $this->banks($data['gateway']->id);
        }
        $data['image'] = asset($data['gateway']->image);
        $currencies = GatewayCurrency::where('gateway_id', decrypt($id))->get();
        foreach ($currencies as $currency) {
            $currency->symbol;
        }
        $data['currencies'] = $currencies;
        return $this->success($data);
    }

    public function getInfo($id)
    {
        return Gateway::where('tenant_id', auth()->user()->tenant_id)->findOrFail(decrypt($id));
    }

    public function banks($gateway_id)
    {
        return Bank::where('gateway_id', $gateway_id)->get();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $gateway = Gateway::where('tenant_id', auth()->user()->tenant_id)->findOrFail(decrypt($request->id));
            if ($gateway->slug == 'bank') {
                $bankIds = [];
                for ($i = 0; $i < count($request->bank['name']); $i++) {
                    $bank = Bank::updateOrCreate([
                        'id' => isset($request->bank['id'][$i]) ? $request->bank['id'][$i] : null,
                        'gateway_id' => $gateway->id,
                    ], [
                        'gateway_id' => $gateway->id,
                        'name' => $request->bank['name'][$i],
                        'details' => $request->bank['details'][$i],
                        'status' => ACTIVE,
                    ]);
                    array_push($bankIds, $bank->id);
                }
                Bank::where('gateway_id', $gateway->id)->whereNotIn('id', $bankIds)->delete();
            } else {
                $gateway->mode = $request->mode == GATEWAY_MODE_LIVE ? GATEWAY_MODE_LIVE : GATEWAY_MODE_SANDBOX;
                $gateway->url = $request->url;
                $gateway->key = $request->key;
                $gateway->secret = $request->secret;
            }
            $gateway->user_id = auth()->id();
            $gateway->tenant_id = auth()->user()->tenant_id;
            $gateway->status = $request->status == STATUS_ACTIVE ? STATUS_ACTIVE : STATUS_PENDING;
            $gateway->save();

            $gatewayCurrencyIds = [];
            if (is_array($request->currency)) {
                foreach ($request->currency as $key => $currency) {
                    $gatewayCurrency = GatewayCurrency::updateOrCreate([
                        'id' => isset($request->currency_id[$key]) ? $request->currency_id[$key] : null,
                        'tenant_id' => auth()->user()->tenant_id,
                    ], [
                        'user_id' => auth()->id(),
                        'tenant_id' => auth()->user()->tenant_id,
                        'gateway_id' => $gateway->id,
                        'currency' => $currency,
                        'conversion_rate' => $request->conversion_rate[$key],
                    ]);
                    array_push($gatewayCurrencyIds, $gatewayCurrency->id);
                }
            } else {
                throw new Exception(__('Please add at least one currency'));
            }
            GatewayCurrency::where('tenant_id', auth()->user()->tenant_id)->whereNotIn('id', $gatewayCurrencyIds)->where('gateway_id', $gateway->id)->delete();

            DB::commit();
            $message = $request->id ? __(UPDATED_SUCCESSFULLY) : __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function getCurrencyByGatewayId($id)
    {
        return GatewayCurrency::where('gateway_id', $id)->get();
    }
}
