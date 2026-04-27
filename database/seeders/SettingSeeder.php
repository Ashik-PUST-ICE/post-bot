<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['option_key' => 'build_version', 'option_value' => '1',  'created_at' => now(), 'updated_at' => now()],
            ['option_key' => 'current_version', 'option_value' => '1.0',  'created_at' => now(), 'updated_at' => now()],
        ];
        Setting::insert($data);
    }
}
