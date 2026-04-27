<?php


namespace App\Http\Services\Payment;

use App\Models\Gateway;
use App\Models\GatewayCurrency;

class BasePaymentService
{
    public $paymentMethod;
    public $callbackUrl;
    public $currency;
    public $gateway;
    public $gatewayCurrency;
    public $amount;
    public $tenantId;
    public $type;

    public function __construct($method, $object)
    {
        if (isset($object['id'])) {
            $this->callbackUrl = $object['callback_url'] . '?id=' . $object['id'];
            $this->cancelUrl = $object['cancel_url'];
        }

        if (isset($object['currency'])) {
            $this->currency = $object['currency'];
        }

        if (isset($object['tenant_id'])) {
            $this->tenantId = $object['tenant_id'];
        }

        if (isset($object['type'])) {
            $this->type = $object['type'];
        }

        $this->paymentMethod = $method;
        $this->gateway = Gateway::where(['slug' => $this->paymentMethod, 'tenant_id' => $this->tenantId])->first();
        $this->gatewayCurrency = GatewayCurrency::where(['gateway_id' => $this->gateway->id, 'currency' => $this->currency])->firstOrFail();
    }

    public function setAmount($amount)
    {
        $this->amount = $this->calculateAmount($amount);
    }

    public function calculateAmount($amount)
    {
        return $this->numberParser($this->gatewayCurrency->conversion_rate) * $this->numberParser($amount);
    }

    function numberParser($value)
    {
        return (float)str_replace(',', '', number_format(($value), 2));
    }
}
