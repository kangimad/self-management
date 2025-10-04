<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for Finance module
        $financePermissions = [
            // Transaction permissions
            'finance-transaction-list',
            'finance-transaction-create',
            'finance-transaction-edit',
            'finance-transaction-delete',

            // Category permissions
            'finance-category-list',
            'finance-category-create',
            'finance-category-edit',
            'finance-category-delete',

            // Source permissions
            'finance-source-list',
            'finance-source-create',
            'finance-source-edit',
            'finance-source-delete',

            // Balance permissions
            'finance-balance-list',
            'finance-balance-create',
            'finance-balance-edit',
            'finance-balance-delete',

            // Allocation permissions
            'finance-allocation-list',
            'finance-allocation-create',
            'finance-allocation-edit',
            'finance-allocation-delete',

            // Type permissions
            'finance-type-list',
            'finance-type-create',
            'finance-type-edit',
            'finance-type-delete',
        ];

        // Create permissions
        foreach ($financePermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $financeManagerRole = Role::create(['name' => 'finance-manager']);
        $financeUserRole = Role::create(['name' => 'finance-user']);

        // Assign permissions to roles
        // Admin gets all permissions
        $adminRole->givePermissionTo(Permission::all());

        // Finance Manager gets all finance permissions
        $financeManagerRole->givePermissionTo($financePermissions);

        // Finance User gets read and limited write permissions
        $financeUserRole->givePermissionTo([
            'finance-transaction-list',
            'finance-transaction-create',
            'finance-category-list',
            'finance-source-list',
            'finance-balance-list',
            'finance-allocation-list',
            'finance-type-list',
        ]);

        // Create default admin user
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Assign admin role to admin user
        $admin->assignRole($adminRole);
    }
}
