<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $roles = [
            'Super Admin',
            'Admin',
            'Manager',
            'User',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create permissions (optional - you can add more specific permissions)
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'finance-transaction-list',
            'finance-transaction-create',
            'finance-transaction-edit',
            'finance-transaction-delete',
            'finance-category-list',
            'finance-category-create',
            'finance-category-edit',
            'finance-category-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign all permissions to Super Admin
        $superAdmin = Role::findByName('Super Admin');
        $superAdmin->givePermissionTo(Permission::all());

        // Assign some permissions to Admin
        $admin = Role::findByName('Admin');
        $admin->givePermissionTo([
            'user-list',
            'user-create',
            'user-edit',
            'finance-transaction-list',
            'finance-transaction-create',
            'finance-transaction-edit',
            'finance-category-list',
            'finance-category-create',
            'finance-category-edit',
        ]);

        // Assign limited permissions to Manager
        $manager = Role::findByName('Manager');
        $manager->givePermissionTo([
            'user-list',
            'finance-transaction-list',
            'finance-transaction-create',
            'finance-transaction-edit',
            'finance-category-list',
        ]);

        // Assign basic permissions to User
        $user = Role::findByName('User');
        $user->givePermissionTo([
            'finance-transaction-list',
            'finance-category-list',
        ]);
    }
}
