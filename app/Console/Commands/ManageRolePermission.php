<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class ManageRolePermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:manage
                            {action : The action to perform (list-roles, list-permissions, assign-role, remove-role, list-users)}
                            {--user= : User email for role assignment}
                            {--role= : Role name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage roles and permissions for users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list-roles':
                $this->listRoles();
                break;
            case 'list-permissions':
                $this->listPermissions();
                break;
            case 'assign-role':
                $this->assignRole();
                break;
            case 'remove-role':
                $this->removeRole();
                break;
            case 'list-users':
                $this->listUsers();
                break;
            default:
                $this->error('Invalid action. Available actions: list-roles, list-permissions, assign-role, remove-role, list-users');
        }
    }

    private function listRoles()
    {
        $roles = Role::with('permissions')->get();

        $this->info('Available Roles:');
        foreach ($roles as $role) {
            $this->line("- {$role->name} ({$role->permissions->count()} permissions)");
            foreach ($role->permissions as $permission) {
                $this->line("  • {$permission->name}");
            }
        }
    }

    private function listPermissions()
    {
        $permissions = Permission::all();

        $this->info('Available Permissions:');
        foreach ($permissions as $permission) {
            $this->line("- {$permission->name}");
        }
    }

    private function assignRole()
    {
        $userEmail = $this->option('user');
        $roleName = $this->option('role');

        if (!$userEmail || !$roleName) {
            $this->error('Both --user and --role options are required');
            return;
        }

        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            $this->error("User with email '{$userEmail}' not found");
            return;
        }

        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            $this->error("Role '{$roleName}' not found");
            return;
        }

        if ($user->hasRole($roleName)) {
            $this->warn("User already has the '{$roleName}' role");
            return;
        }

        $user->assignRole($roleName);
        $this->info("Role '{$roleName}' assigned to user '{$userEmail}'");
    }

    private function removeRole()
    {
        $userEmail = $this->option('user');
        $roleName = $this->option('role');

        if (!$userEmail || !$roleName) {
            $this->error('Both --user and --role options are required');
            return;
        }

        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            $this->error("User with email '{$userEmail}' not found");
            return;
        }

        if (!$user->hasRole($roleName)) {
            $this->warn("User doesn't have the '{$roleName}' role");
            return;
        }

        $user->removeRole($roleName);
        $this->info("Role '{$roleName}' removed from user '{$userEmail}'");
    }

    private function listUsers()
    {
        $users = User::with('roles')->get();

        $this->info('Users and their roles:');
        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->join(', ') ?: 'No roles';
            $this->line("- {$user->name} ({$user->email}): {$roles}");
        }
    }
}
