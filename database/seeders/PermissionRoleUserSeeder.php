<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PermissionRoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create basic permissions
        $permissions = [
            // Dashboard Management
            'dashboard',

            // Setting Management
            'setting',

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
            'finance',

            'finance-transaction-list',
            'finance-transaction-create',
            'finance-transaction-edit',
            'finance-transaction-delete',

            'finance-category-list',
            'finance-category-create',
            'finance-category-edit',
            'finance-category-delete',
            'finance-category-type-list',
            'finance-category-type-create',
            'finance-category-type-edit',
            'finance-category-type-delete',

            'finance-source-list',
            'finance-source-create',
            'finance-source-edit',
            'finance-source-delete',
            'finance-source-type-list',
            'finance-source-type-create',
            'finance-source-type-edit',
            'finance-source-type-delete',

            'finance-balance-list',
            'finance-balance-create',
            'finance-balance-edit',
            'finance-balance-delete',

            'finance-allocation-list',
            'finance-allocation-create',
            'finance-allocation-edit',
            'finance-allocation-delete',

            // Task Management
            'task',

            // Event Management
            'event',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);

        // Assign all permissions to Admin role
        $adminRole->syncPermissions($permissions);

        // Assign basic permissions to User role
        $userPermissions = [
            'finance',
            'finance-transaction-list',
            'finance-transaction-create',
            'finance-transaction-edit',
            'finance-transaction-delete',
            'finance-category-list',
            'finance-source-list',
            'finance-balance-list',
            'task',
            'event',
        ];
        $userRole->syncPermissions($userPermissions);

        // Create Admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole('Admin');

        // Create User user
        $regularUser = User::firstOrCreate(
            ['email' => 'user@user.com'],
            [
                'name' => 'User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $regularUser->assignRole('User');
    }
}
