<?php

namespace App\Repositories;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

class PermissionRepository
{
    /**
     * Get all permissions with role counts
     */
    public function getAllWithRoleCounts(): Collection
    {
        return Permission::withCount('roles')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get permissions for DataTable
     */
    public function getForDataTable()
    {
        return Permission::query()->with(['roles' => function ($query) {
            $query->select('id', 'name');
        }]);
    }

    /**
     * Create a new permission
     */
    public function create(array $data): Permission
    {
        return Permission::create([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'] ?? 'web',
        ]);
    }

    /**
     * Find permission by ID
     */
    public function findById(int $id): ?Permission
    {
        return Permission::find($id);
    }

    /**
     * Update permission
     */
    public function update(Permission $permission, array $data): bool
    {
        return $permission->update([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'] ?? 'web',
        ]);
    }

    /**
     * Delete permission
     */
    public function delete(Permission $permission): bool
    {
        // Remove all role assignments before deleting
        $permission->roles()->detach();

        return $permission->delete();
    }

    /**
     * Check if permission exists by name
     */
    public function existsByName(string $name, ?int $excludeId = null): bool
    {
        $query = Permission::where('name', $name);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get permission by name
     */
    public function findByName(string $name): ?Permission
    {
        return Permission::where('name', $name)->first();
    }

    /**
     * Delete multiple permissions
     */
    public function deleteMultiple(array $ids): bool
    {
        // Remove all role assignments first
        Permission::whereIn('id', $ids)->each(function ($permission) {
            $permission->roles()->detach();
        });

        return Permission::whereIn('id', $ids)->delete();
    }

    /**
     * Get permissions with their roles
     */
    public function getWithRoles(): Collection
    {
        return Permission::with('roles')->orderBy('name')->get();
    }
}
