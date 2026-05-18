<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GatewayRequest;
use App\Http\Services\GatewayService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    use ResponseTrait;

    public $gatewayService;

    public function __construct()
    {
        $this->gatewayService = new GatewayService;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Gateway');
        $data['showManageApplicationSetting'] = 'show';
        $data['activeGatewaySetting'] = 'active';
        $data['activeGateway'] = 'active';
        $data['gateways'] = $this->gatewayService->getAll(auth()->user()->tenant_id);

        return view('admin.setting.gateway.index', $data);
    }

    public function edit($id)
    {
        $data['pageTitle'] = __('Gateway Edit');
        $data['showManageApplicationSetting'] = 'show';
        $data['activeGatewaySetting'] = 'active';
        $data['activeGateway'] = 'active';
        $data['gateway'] = \App\Models\Gateway::where('tenant_id', auth()->user()->tenant_id)->findOrFail(decrypt($id));
        $data['gatewaySettings'] = gatewaySettings($data['gateway']->slug);
        $data['gatewayCurrencies'] = \App\Models\GatewayCurrency::where('gateway_id', $data['gateway']->id)->get();
        $data['gatewayBanks'] = \App\Models\Bank::where('gateway_id', $data['gateway']->id)->get();

        return view('admin.setting.gateway.edit', $data);
    }

    public function store(GatewayRequest $request)
    {
        return $this->gatewayService->store($request);
    }

    public function getInfo(Request $request)
    {
        return $this->gatewayService->getCurrenciesByGatewayId($request->id);
    }

    public function getCurrencyByGateway(Request $request)
    {
        $data = $this->gatewayService->getCurrencyByGatewayId($request->id);
        return $this->success($data);
    }
    public function syncs()
    {
        syncMissingGateway();

        return redirect()->back()->with('success', 'Gateways synced successfully!');
    }
}
