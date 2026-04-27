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
            ['user_id' => 1, 'tenant_id' => null, 'title' => 'Paypal', 'slug' => 'paypal', 'image' => 'assets/images/gateway-icon/paypal.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 1, 'tenant_id' => null, 'title' => 'Stripe', 'slug' => 'stripe', 'image' => 'assets/images/gateway-icon/stripe.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 1, 'tenant_id' => null, 'title' => 'Razorpay', 'slug' => 'razorpay', 'image' => 'assets/images/gateway-icon/razorpay.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 1, 'tenant_id' => null, 'title' => 'Instamojo', 'slug' => 'instamojo', 'image' => 'assets/images/gateway-icon/instamojo.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 1, 'tenant_id' => null, 'title' => 'Mollie', 'slug' => 'mollie', 'image' => 'assets/images/gateway-icon/mollie.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 1, 'tenant_id' => null, 'title' => 'Paystack', 'slug' => 'paystack', 'image' => 'assets/images/gateway-icon/paystack.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 1, 'tenant_id' => null, 'title' => 'Sslcommerz', 'slug' => 'sslcommerz', 'image' => 'assets/images/gateway-icon/sslcommerz.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 1, 'tenant_id' => null, 'title' => 'Flutterwave', 'slug' => 'flutterwave', 'image' => 'assets/images/gateway-icon/flutterwave.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 1, 'tenant_id' => null, 'title' => 'Mercadopago', 'slug' => 'mercadopago', 'image' => 'assets/images/gateway-icon/mercadopago.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 1, 'tenant_id' => null, 'title' => 'Bank', 'slug' => 'bank', 'image' => 'assets/images/gateway-icon/bank.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 1, 'tenant_id' => null, 'title' => 'Cash', 'slug' => 'cash', 'image' => 'assets/images/gateway-icon/cash.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => '',],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'title' => 'Paypal', 'slug' => 'paypal', 'image' => 'assets/images/gateway-icon/paypal.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'title' => 'Stripe', 'slug' => 'stripe', 'image' => 'assets/images/gateway-icon/stripe.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'title' => 'Razorpay', 'slug' => 'razorpay', 'image' => 'assets/images/gateway-icon/razorpay.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'title' => 'Instamojo', 'slug' => 'instamojo', 'image' => 'assets/images/gateway-icon/instamojo.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'title' => 'Mollie', 'slug' => 'mollie', 'image' => 'assets/images/gateway-icon/mollie.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'title' => 'Paystack', 'slug' => 'paystack', 'image' => 'assets/images/gateway-icon/paystack.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'title' => 'Sslcommerz', 'slug' => 'sslcommerz', 'image' => 'assets/images/gateway-icon/sslcommerz.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'title' => 'Flutterwave', 'slug' => 'flutterwave', 'image' => 'assets/images/gateway-icon/flutterwave.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'title' => 'Mercadopago', 'slug' => 'mercadopago', 'image' => 'assets/images/gateway-icon/mercadopago.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'title' => 'Bank', 'slug' => 'bank', 'image' => 'assets/images/gateway-icon/bank.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'title' => 'Cash', 'slug' => 'cash', 'image' => 'assets/images/gateway-icon/cash.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => '',],
        ];
        Gateway::insert($data);

        GatewayCurrency::insert([
            ['user_id' => 1, 'tenant_id' => null, 'gateway_id' => 1, 'currency' => 'USD', 'conversion_rate' => 1],
            ['user_id' => 1, 'tenant_id' => null, 'gateway_id' => 2, 'currency' => 'USD', 'conversion_rate' => 1],
            ['user_id' => 1, 'tenant_id' => null, 'gateway_id' => 3, 'currency' => 'INR', 'conversion_rate' => 80],
            ['user_id' => 1, 'tenant_id' => null, 'gateway_id' => 4, 'currency' => 'INR', 'conversion_rate' => 80],
            ['user_id' => 1, 'tenant_id' => null, 'gateway_id' => 5, 'currency' => 'USD', 'conversion_rate' => 1],
            ['user_id' => 1, 'tenant_id' => null, 'gateway_id' => 6, 'currency' => 'NGN', 'conversion_rate' => 464],
            ['user_id' => 1, 'tenant_id' => null, 'gateway_id' => 7, 'currency' => 'BDT', 'conversion_rate' => 100],
            ['user_id' => 1, 'tenant_id' => null, 'gateway_id' => 8, 'currency' => 'NGN', 'conversion_rate' => 464],
            ['user_id' => 1, 'tenant_id' => null, 'gateway_id' => 9, 'currency' => 'BRL', 'conversion_rate' => 5],
            ['user_id' => 1, 'tenant_id' => null, 'gateway_id' => 10, 'currency' => 'USD', 'conversion_rate' => 1],
            ['user_id' => 1, 'tenant_id' => null, 'gateway_id' => 11, 'currency' => 'USD', 'conversion_rate' => 1],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'gateway_id' => 12, 'currency' => 'USD', 'conversion_rate' => 1],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'gateway_id' => 13, 'currency' => 'USD', 'conversion_rate' => 1],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'gateway_id' => 14, 'currency' => 'INR', 'conversion_rate' => 80],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'gateway_id' => 15, 'currency' => 'INR', 'conversion_rate' => 80],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'gateway_id' => 16, 'currency' => 'USD', 'conversion_rate' => 1],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'gateway_id' => 17, 'currency' => 'NGN', 'conversion_rate' => 464],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'gateway_id' => 18, 'currency' => 'BDT', 'conversion_rate' => 100],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'gateway_id' => 19, 'currency' => 'NGN', 'conversion_rate' => 464],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'gateway_id' => 20, 'currency' => 'BRL', 'conversion_rate' => 5],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'gateway_id' => 21, 'currency' => 'USD', 'conversion_rate' => 1],
            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'gateway_id' => 22, 'currency' => 'USD', 'conversion_rate' => 1],
        ]);
    }
}
