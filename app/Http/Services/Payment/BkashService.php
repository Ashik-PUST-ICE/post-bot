<?php

namespace App\Http\Services\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * bKash Tokenized Checkout v1.2.0-beta
 *
 * Credentials stored in Gateway model:
 *   key    = App Key
 *   secret = App Secret
 *   url    = username|password  (pipe-separated)
 *
 * Flow:
 *   1. Grant Token  → id_token
 *   2. Create Payment → bkashURL  (redirect customer here)
 *   3. Callback → Execute Payment → verify status = "Completed"
 */
class BkashService extends BasePaymentService
{
    protected string $baseUrl;
    protected string $appKey;
    protected string $appSecret;
    protected string $username;
    protected string $password;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);

        $this->appKey    = $this->gateway->key;
        $this->appSecret = $this->gateway->secret;

        // url field stores "username|password"
        [$this->username, $this->password] = array_pad(
            explode('|', $this->gateway->url ?? '|'), 2, ''
        );

        $this->baseUrl = $this->gateway->mode == GATEWAY_MODE_LIVE
            ? 'https://tokenized.pay.bka.sh/v1.2.0-beta'
            : 'https://tokenized.sandbox.bka.sh/v1.2.0-beta';
    }

    // ─── Step 1: Initiate Payment ─────────────────────────────────────────────

    public function makePayment($amount)
    {
        $this->setAmount($amount);

        $data = [
            'success'      => false,
            'redirect_url' => '',
            'payment_id'   => '',
            'message'      => SOMETHING_WENT_WRONG,
        ];

        try {
            // Grant Token
            $token = $this->grantToken();
            if (!$token) {
                $data['message'] = 'bKash: Failed to get token.';
                return $data;
            }

            // Create Payment
            $orderId  = uniqid('order_');
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => $token,
                'X-APP-Key'     => $this->appKey,
            ])->post("{$this->baseUrl}/tokenized/checkout/create", [
                'mode'                => '0011',                    // checkout
                'payerReference'      => ' ',
                'callbackURL'         => $this->callbackUrl,
                'amount'              => number_format($this->amount, 2, '.', ''),
                'currency'            => 'BDT',
                'intent'              => 'sale',
                'merchantInvoiceNumber' => $orderId,
            ]);

            $res = $response->json();
            Log::info('bKash createPayment', $res);

            if (isset($res['bkashURL']) && isset($res['paymentID'])) {
                $data['success']      = true;
                $data['redirect_url'] = $res['bkashURL'];
                $data['payment_id']   = $res['paymentID'];
            } else {
                $data['message'] = $res['statusMessage'] ?? SOMETHING_WENT_WRONG;
            }
        } catch (\Exception $e) {
            Log::error('bKash makePayment error: ' . $e->getMessage());
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    // ─── Step 2: Confirm Payment (called from callback) ────────────────────────

    public function paymentConfirmation($paymentId)
    {
        $data = ['success' => false, 'data' => null];

        try {
            $token = $this->grantToken();
            if (!$token) {
                return $data;
            }

            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => $token,
                'X-APP-Key'     => $this->appKey,
            ])->post("{$this->baseUrl}/tokenized/checkout/execute", [
                'paymentID' => $paymentId,
            ]);

            $res = $response->json();
            Log::info('bKash executePayment', $res);

            if (($res['transactionStatus'] ?? '') === 'Completed') {
                $data['success']                    = true;
                $data['data']['payment_id']         = $res['paymentID'];
                $data['data']['trx_id']             = $res['trxID'];
                $data['data']['amount']             = $res['amount'];
                $data['data']['currency']           = 'BDT';
                $data['data']['payment_status']     = 'success';
                $data['data']['payment_method']     = BKASH;
            } else {
                $data['data']['payment_status']     = $res['transactionStatus'] ?? 'failed';
                $data['data']['message']            = $res['statusMessage'] ?? SOMETHING_WENT_WRONG;
            }
        } catch (\Exception $e) {
            Log::error('bKash paymentConfirmation error: ' . $e->getMessage());
        }

        return $data;
    }

    // ─── Private: Grant Token ─────────────────────────────────────────────────

    protected function grantToken(): ?string
    {
        try {
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'username'      => $this->username,
                'password'      => $this->password,
            ])->post("{$this->baseUrl}/tokenized/checkout/token/grant", [
                'app_key'    => $this->appKey,
                'app_secret' => $this->appSecret,
            ]);

            $res = $response->json();
            Log::info('bKash grantToken', ['status' => $res['statusCode'] ?? 'unknown']);

            return $res['id_token'] ?? null;
        } catch (\Exception $e) {
            Log::error('bKash grantToken error: ' . $e->getMessage());
            return null;
        }
    }
}
