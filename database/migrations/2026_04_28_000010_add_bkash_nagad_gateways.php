<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tenants = DB::table('gateways')
            ->select('user_id', 'tenant_id')
            ->distinct()
            ->get();

        $rows = [];
        foreach ($tenants as $tenant) {
            $exists = DB::table('gateways')
                ->where('user_id', $tenant->user_id)
                ->where('tenant_id', $tenant->tenant_id)
                ->where('slug', 'bkash')
                ->exists();

            if (!$exists) {
                $rows[] = ['user_id' => $tenant->user_id, 'tenant_id' => $tenant->tenant_id, 'title' => 'bKash', 'slug' => 'bkash', 'image' => 'assets/images/gateway-icon/bkash.png', 'status' => 1, 'mode' => 2, 'url' => '', 'key' => '', 'secret' => '', 'created_at' => now(), 'updated_at' => now()];
                $rows[] = ['user_id' => $tenant->user_id, 'tenant_id' => $tenant->tenant_id, 'title' => 'Nagad', 'slug' => 'nagad', 'image' => 'assets/images/gateway-icon/nagad.png', 'status' => 1, 'mode' => 2, 'url' => '', 'key' => '', 'secret' => '', 'created_at' => now(), 'updated_at' => now()];
            }
        }

        if (!empty($rows)) {
            DB::table('gateways')->insert($rows);
        }

        $newGateways = DB::table('gateways')->whereIn('slug', ['bkash', 'nagad'])->get();
        $currencyRows = [];
        foreach ($newGateways as $gw) {
            if (!DB::table('gateway_currencies')->where('gateway_id', $gw->id)->exists()) {
                $currencyRows[] = ['user_id' => $gw->user_id, 'tenant_id' => $gw->tenant_id, 'gateway_id' => $gw->id, 'currency' => 'BDT', 'conversion_rate' => 1, 'created_at' => now(), 'updated_at' => now()];
            }
        }

        if (!empty($currencyRows)) {
            DB::table('gateway_currencies')->insert($currencyRows);
        }
    }

    public function down(): void
    {
        DB::table('gateways')->whereIn('slug', ['bkash', 'nagad'])->delete();
    }
};
