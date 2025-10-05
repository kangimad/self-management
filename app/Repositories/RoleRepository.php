<?php

namespace App\Repositories;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface RoleRepositoryInterface
{
    public function getAllRoles(): Collection;
    public function getPaginatedRoles(int $perPage = 10): LengthAwarePaginator;
    public function findRoleById(int $id): ?Role;
    public function createRole(array $data): Role;
    public function updateRole(int $id, array $data): bool;
    public function deleteRole(int $id): bool;
    public function getAllPermissions(): Collection;
    public function syncRolePermissions(Role $role, array $permissions): void;
    public function getRoleWithPermissions(int $id): ?Role;
    public function removeUserFromRole(int $userId, int $roleId): bool;
    public function removeMultipleUsersFromRole(array $userIds, int $roleId): array;
}

class RoleRepository implements RoleRepositoryInterface
{
    /**
     * Get all roles
     */
    public function getAllRoles(): Collection
    {
        return Role::all();
    }

    /**
     * Get paginated roles
     */
    public function getPaginatedRoles(int $perPage = 10): LengthAwarePaginator
    {
        return Role::withCount('users')
            ->with('permissions')
            ->paginate($perPage);
    }

    /**
     * Find role by ID
     */
    public function findRoleById(int $id): ?Role
    {
        return Role::find($id);
    }

    /**
     * Create new role
     */
    public function createRole(array $data): Role
    {
        return Role::create([
            'name' => $data['role_name'],
            'guard_name' => 'web',
        ]);
    }

    /**
     * Update role
     */
    public function updateRole(int $id, array $data): bool
    {
        $role = $this->findRoleById($id);
        if (!$role) {
            return false;
        }

        return $role->update([
            'name' => $data['role_name'],
        ]);
    }

    /**
     * Delete role
     */
    public function deleteRole(int $id): bool
    {
        $role = $this->findRoleById($id);
        if (!$role) {
            return false;
        }

        // Remove all permissions from role before deleting
        $role->permissions()->detach();

        return $role->delete();
    }

    /**
     * Get all permissions
     */
    public function getAllPermissions(): Collection
    {
        return Permission::all();
    }

    /**
     * Sync role permissions
     */
    public function syncRolePermissions(Role $role, array $permissions): void
    {
        $role->syncPermissions($permissions);
    }

    /**
     * Get role with permissions
     */
    public function getRoleWithPermissions(int $id): ?Role
    {
        return Role::with('permissions')->find($id);
    }

    /**
     * Remove user from specific role
     */
    public function removeUserFromRole(int $userId, int $roleId): bool
    {
        try {
            $role = $this->findRoleById($roleId);
            if (!$role) {
                return false;
            }

            $role->users()->detach($userId);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Remove multiple users from specific role
     */
    public function removeMultipleUsersFromRole(array $userIds, int $roleId): array
    {
        $results = [];
        $role = $this->findRoleById($roleId);

        if (!$role) {
            return array_fill_keys($userIds, false);
        }

        foreach ($userIds as $userId) {
            try {
                $role->users()->detach($userId);
                $results[$userId] = true;
            } catch (\Exception $e) {
                $results[$userId] = false;
            }
        }

        return $results;
    }
}
