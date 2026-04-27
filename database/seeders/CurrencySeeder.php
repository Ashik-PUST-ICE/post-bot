<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['currency_code' => 'USD', 'symbol' => '$', 'currency_placement' => 'before', 'current_currency' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['currency_code' => 'BDT', 'symbol' => '৳', 'currency_placement' => 'before', 'current_currency' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['currency_code' => 'INR', 'symbol' => '₹', 'currency_placement' => 'before', 'current_currency' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['currency_code' => 'GBP', 'symbol' => '£', 'currency_placement' => 'after', 'current_currency' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['currency_code' => 'MXN', 'symbol' => '$', 'currency_placement' => 'before', 'current_currency' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['currency_code' => 'SAR', 'symbol' => 'SR', 'currency_placement' => 'before', 'current_currency' => 0, 'created_at' => now(), 'updated_at' => now()],
        ];
        Currency::insert($data);
    }
}
