<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::where('id', '!=', 0)->delete();
        $now = now();
        $data = [
            // Application Settings Module
            ['name' => 'Application: Manage Settings', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Application: Manage Version Update', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Application: Manage Cache', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],

            // Moderator Module
            ['name' => 'Moderator: Manage Roles', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Moderator: Manage Permissions', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],

            // Content Module
            ['name' => 'Content: Manage News', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Content: Manage Events', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Content: Manage Notices', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Content: Manage Job Posts', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],

            // Users Module
            ['name' => 'Users: Manage Alumni', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Users: Manage Batches', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Users: Manage Departments', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
        ];
        Permission::insert($data);
    }
}
