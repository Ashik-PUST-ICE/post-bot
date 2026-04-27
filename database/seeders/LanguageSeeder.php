<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::insert([
            ['id' => 1, 'language' => 'English', 'iso_code' => 'en', 'flag_id' => null, 'rtl' => 0, 'status' => 1, 'default' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
