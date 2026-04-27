<?php

namespace Database\Seeders;

use App\Models\Gateway;
use App\Models\GatewayCurrency;
use Illuminate\Database\Seeder;

class GatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['title' => 'Paypal', 'slug' => 'paypal', 'image' => 'assets/images/gateway-icon/paypal.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Stripe', 'slug' => 'stripe', 'image' => 'assets/images/gateway-icon/stripe.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Razorpay', 'slug' => 'razorpay', 'image' => 'assets/images/gateway-icon/razorpay.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Instamojo', 'slug' => 'instamojo', 'image' => 'assets/images/gateway-icon/instamojo.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Mollie', 'slug' => 'mollie', 'image' => 'assets/images/gateway-icon/mollie.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Paystack', 'slug' => 'paystack', 'image' => 'assets/images/gateway-icon/paystack.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Sslcommerz', 'slug' => 'sslcommerz', 'image' => 'assets/images/gateway-icon/sslcommerz.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Flutterwave', 'slug' => 'flutterwave', 'image' => 'assets/images/gateway-icon/flutterwave.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Mercadopago', 'slug' => 'mercadopago', 'image' => 'assets/images/gateway-icon/mercadopago.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Bank', 'slug' => 'bank', 'image' => 'assets/images/gateway-icon/bank.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
        ];
        Gateway::insert($data);

        GatewayCurrency::insert([
            ['gateway_id' => 1, 'currency' => 'USD', 'conversion_rate' => 1],
            ['gateway_id' => 2, 'currency' => 'USD', 'conversion_rate' => 1],
            ['gateway_id' => 3, 'currency' => 'INR', 'conversion_rate' => 80],
            ['gateway_id' => 4, 'currency' => 'INR', 'conversion_rate' => 80],
            ['gateway_id' => 5, 'currency' => 'USD', 'conversion_rate' => 1],
            ['gateway_id' => 6, 'currency' => 'NGN', 'conversion_rate' => 464],
            ['gateway_id' => 7, 'currency' => 'BDT', 'conversion_rate' => 100],
            ['gateway_id' => 8, 'currency' => 'NGN', 'conversion_rate' => 464],
            ['gateway_id' => 9, 'currency' => 'BRL', 'conversion_rate' => 5],
            ['gateway_id' => 10, 'currency' => 'USD', 'conversion_rate' => 1],
        ]);
    }
}
