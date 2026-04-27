<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(BankSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(GatewaySeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(UserSeeder::class);
    }
}
