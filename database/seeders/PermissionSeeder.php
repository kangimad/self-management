<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create basic permissions
        $permissions = [
            // User Management
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            // Role Management
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            // Permission Management
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',

            // Finance Management
            'finance-transaction-list',
            'finance-transaction-create',
            'finance-transaction-edit',
            'finance-transaction-delete',

            'finance-category-list',
            'finance-category-create',
            'finance-category-edit',
            'finance-category-delete',

            'finance-source-list',
            'finance-source-create',
            'finance-source-edit',
            'finance-source-delete',

            'finance-balance-list',
            'finance-balance-create',
            'finance-balance-edit',
            'finance-balance-delete',

            'finance-allocation-list',
            'finance-allocation-create',
            'finance-allocation-edit',
            'finance-allocation-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
    }
}
