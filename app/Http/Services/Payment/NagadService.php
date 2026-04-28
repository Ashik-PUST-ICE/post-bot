<?php

namespace App\Http\Services\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Nagad Merchant Payment API
 *
 * Credentials stored in Gateway model:
 *   key    = Merchant ID
 *   secret = Merchant Private Key (Base64 encoded RSA private key)
 *   url    = Nagad Public Key (Base64 encoded RSA public key)
 *
 * Flow:
 *   1. Initialize   → sensitiveData (encrypted), signature
 *   2. Confirm      → paymentRefId → redirect URL
 *   3. Callback     → Verify → check status "Success"
 *
 * Nagad Sandbox: http://sandbox.mynagad.com:10080/remote-payment-gateway-1.0/api/dfs
 * Nagad Live:    https://api.mynagad.com/api/dfs
 */
class NagadService extends BasePaymentService
{
    protected string $baseUrl;
    protected string $merchantId;
    protected string $merchantPrivateKey;
    protected string $nagadPublicKey;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);

        $this->merchantId         = $this->gateway->key;
        $this->merchantPrivateKey = $this->gateway->secret;
        $this->nagadPublicKey     = $this->gateway->url ?? '';

        $this->baseUrl = $this->gateway->mode == GATEWAY_MODE_LIVE
            ? 'https://api.mynagad.com/api/dfs'
            : 'http://sandbox.mynagad.com:10080/remote-payment-gateway-1.0/api/dfs';
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
            $orderId   = 'ORD-' . strtoupper(uniqid());
            $datetime  = now()->format('YmdHis');

            // ── Sensitive Data (will be encrypted with Nagad public key) ────────
            $sensitiveDataPlain = json_encode([
                'merchantId'        => $this->merchantId,
                'datetime'          => $datetime,
                'orderId'           => $orderId,
                'challenge'         => $this->generateChallenge(),
            ]);

            $sensitiveData = $this->encryptWithPublicKey($sensitiveDataPlain);
            $signature     = $this->signWithPrivateKey($sensitiveDataPlain);

            if (!$sensitiveData || !$signature) {
                $data['message'] = 'Nagad: Key configuration error.';
                return $data;
            }

            // ── Initialize ───────────────────────────────────────────────────────
            $initResponse = Http::withHeaders([
                'X-KM-Api-Version' => 'v-0.2.0',
                'X-KM-IP-V4'       => request()->ip(),
                'X-KM-Client-Type' => 'PC_WEB',
                'Content-Type'     => 'application/json',
            ])->post("{$this->baseUrl}/check-out/initialize/{$this->merchantId}/{$orderId}", [
                'dateTime'      => $datetime,
                'sensitiveData' => $sensitiveData,
                'signature'     => $signature,
            ]);

            $initRes = $initResponse->json();
            Log::info('Nagad initialize', $initRes);

            if (empty($initRes['sensitiveData'])) {
                $data['message'] = $initRes['reason'] ?? SOMETHING_WENT_WRONG;
                return $data;
            }

            // ── Decrypt response & complete ──────────────────────────────────────
            $decryptedInit = json_decode(
                $this->decryptWithPrivateKey($initRes['sensitiveData']), true
            );

            $paymentRefId   = $decryptedInit['paymentReferenceId']  ?? null;
            $challengeVal   = $decryptedInit['challenge']            ?? null;

            if (!$paymentRefId) {
                $data['message'] = 'Nagad: Could not get paymentReferenceId.';
                return $data;
            }

            // ── Complete (get redirect URL) ──────────────────────────────────────
            $completeSensitivePlain = json_encode([
                'merchantId'          => $this->merchantId,
                'orderId'             => $orderId,
                'challenge'           => $challengeVal,
                'amount'              => number_format($this->amount, 2, '.', ''),
                'currencyCode'        => '050',              // BDT
                'intent'              => 'sale',
                'productionReference' => $orderId,
                'productionName'      => 'Order Payment',
                'productionProfile'   => 'S01',
            ]);

            $completeSensitive = $this->encryptWithPublicKey($completeSensitivePlain);
            $completeSignature  = $this->signWithPrivateKey($completeSensitivePlain);

            $completeResponse = Http::withHeaders([
                'X-KM-Api-Version' => 'v-0.2.0',
                'X-KM-IP-V4'       => request()->ip(),
                'X-KM-Client-Type' => 'PC_WEB',
                'Content-Type'     => 'application/json',
            ])->post("{$this->baseUrl}/check-out/complete/{$paymentRefId}", [
                'sensitiveData'     => $completeSensitive,
                'signature'         => $completeSignature,
                'merchantCallbackURL' => $this->callbackUrl,
            ]);

            $completeRes = $completeResponse->json();
            Log::info('Nagad complete', $completeRes);

            if (!empty($completeRes['callBackUrl'])) {
                $data['success']      = true;
                $data['redirect_url'] = $completeRes['callBackUrl'];
                $data['payment_id']   = $paymentRefId;
            } else {
                $data['message'] = $completeRes['reason'] ?? SOMETHING_WENT_WRONG;
            }
        } catch (\Exception $e) {
            Log::error('Nagad makePayment error: ' . $e->getMessage());
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    // ─── Step 2: Verify Payment (called from callback) ────────────────────────

    public function paymentConfirmation($paymentRefId)
    {
        $data = ['success' => false, 'data' => null];

        try {
            $response = Http::withHeaders([
                'X-KM-Api-Version' => 'v-0.2.0',
                'X-KM-IP-V4'       => request()->ip(),
                'X-KM-Client-Type' => 'PC_WEB',
                'Content-Type'     => 'application/json',
            ])->get("{$this->baseUrl}/verify/payment/{$paymentRefId}");

            $res = $response->json();
            Log::info('Nagad verify', $res);

            if (($res['status'] ?? '') === 'Success') {
                $data['success']                = true;
                $data['data']['payment_id']     = $paymentRefId;
                $data['data']['trx_id']         = $res['issuerPaymentRefNo']   ?? '';
                $data['data']['amount']         = $res['amount']               ?? 0;
                $data['data']['currency']       = 'BDT';
                $data['data']['payment_status'] = 'success';
                $data['data']['payment_method'] = NAGAD;
            } else {
                $data['data']['payment_status'] = $res['status']  ?? 'failed';
                $data['data']['message']        = $res['reason']  ?? SOMETHING_WENT_WRONG;
            }
        } catch (\Exception $e) {
            Log::error('Nagad paymentConfirmation error: ' . $e->getMessage());
        }

        return $data;
    }

    // ─── Crypto Helpers ───────────────────────────────────────────────────────

    protected function encryptWithPublicKey(string $plain): ?string
    {
        try {
            $pubKey = "-----BEGIN PUBLIC KEY-----\n"
                . chunk_split(base64_decode($this->nagadPublicKey), 64, "\n")
                . "-----END PUBLIC KEY-----";
            openssl_public_encrypt($plain, $encrypted, $pubKey, OPENSSL_PKCS1_PADDING);
            return base64_encode($encrypted);
        } catch (\Exception $e) {
            Log::error('Nagad encryptWithPublicKey: ' . $e->getMessage());
            return null;
        }
    }

    protected function signWithPrivateKey(string $plain): ?string
    {
        try {
            $privKey = "-----BEGIN RSA PRIVATE KEY-----\n"
                . chunk_split(base64_decode($this->merchantPrivateKey), 64, "\n")
                . "-----END RSA PRIVATE KEY-----";
            openssl_sign($plain, $signature, $privKey, OPENSSL_ALGO_SHA256);
            return base64_encode($signature);
        } catch (\Exception $e) {
            Log::error('Nagad signWithPrivateKey: ' . $e->getMessage());
            return null;
        }
    }

    protected function decryptWithPrivateKey(string $encrypted): string
    {
        try {
            $privKey = "-----BEGIN RSA PRIVATE KEY-----\n"
                . chunk_split(base64_decode($this->merchantPrivateKey), 64, "\n")
                . "-----END RSA PRIVATE KEY-----";
            openssl_private_decrypt(base64_decode($encrypted), $decrypted, $privKey, OPENSSL_PKCS1_PADDING);
            return $decrypted ?? '';
        } catch (\Exception $e) {
            Log::error('Nagad decryptWithPrivateKey: ' . $e->getMessage());
            return '';
        }
    }

    protected function generateChallenge(): string
    {
        return bin2hex(random_bytes(20));
    }
}
