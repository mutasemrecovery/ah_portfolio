<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // ── Roles & Employees ──────────────────────────────────────────────
            'role-table',             'role-add',             'role-edit',             'role-delete',
            'employee-table',         'employee-add',         'employee-edit',         'employee-delete',

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);
        }
    }
}
