<?php

namespace App\Services;

use App\Repositories\PermissionRepository;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class PermissionService
{
    protected $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Get all permissions for listing
     */
    public function getAllPermissions(): Collection
    {
        return $this->permissionRepository->getAllWithRoleCounts();
    }

    /**
     * Get permissions for DataTable
     */
    public function getPermissionsForDataTable()
    {
        return $this->permissionRepository->getForDataTable();
    }

    /**
     * Get DataTable data with pagination and search
     */
    public function getDatatableData($request)
    {
        try {
            $draw = $request->get('draw', 1);
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            $searchValue = $request->get('search')['value'] ?? '';
            $orderData = $request->get('order', []);

            $query = $this->permissionRepository->getForDataTable();

            // Apply search filter
            if (!empty($searchValue)) {
                $query->where('name', 'like', "%{$searchValue}%");
            }

            // Apply ordering
            $orderApplied = false;
            if (!empty($orderData)) {
                foreach ($orderData as $order) {
                    $columnIndex = intval($order['column']);
                    $direction = strtolower($order['dir']) === 'desc' ? 'desc' : 'asc';

                    // Map column indices to actual column names
                    $columns = [
                        0 => null, // checkbox column - not sortable
                        1 => null, // index column - not sortable
                        2 => 'name',
                        3 => 'roles', // This will be handled specially
                        4 => 'created_at',
                        5 => null, // actions column - not sortable
                    ];

                    if (isset($columns[$columnIndex]) && $columns[$columnIndex] !== null) {
                        $columnName = $columns[$columnIndex];

                        if ($columnName === 'roles') {
                            // For roles column, order by the count of roles
                            $query->withCount('roles')->orderBy('roles_count', $direction);
                        } else {
                            $query->orderBy($columnName, $direction);
                        }
                        $orderApplied = true;
                        break; // Apply only the first valid order
                    }
                }
            }

            // Default ordering by name if no order was applied
            if (!$orderApplied) {
                $query->orderBy('name', 'asc');
            }

            // Get total records count
            $totalRecords = Permission::count();

            // Get filtered records count
            $filteredQuery = clone $query;
            $filteredRecords = $filteredQuery->count();

            // Apply pagination
            $permissions = $query->offset($start)->limit($length)->get();

            $data = [];
            foreach ($permissions as $index => $permission) {
                $roles = $permission->roles->map(function ($role) {
                    return '<span class="badge badge-light-primary fs-7 m-1">' . $role->name . '</span>';
                })->implode(' ');

                if (empty($roles)) {
                    $roles = '<span class="text-muted">Tidak ada role</span>';
                }

                $actions = view('dashboard.pages.settings.permissions.partials.actions', compact('permission'))->render();

                $data[] = [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'guard_name' => $permission->guard_name,
                    'roles' => $roles,
                    'created_at' => $permission->created_at ? $permission->created_at->format('d M Y, H:i') : '-',
                    'actions' => $actions
                ];
            }

            return [
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ];
        } catch (Exception $e) {
            return [
                'draw' => $request->get('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create a new permission
     */
    public function createPermission(array $data): Permission
    {
        // Check if permission already exists
        if ($this->permissionRepository->existsByName($data['name'])) {
            throw new Exception('Permission dengan nama tersebut sudah ada.');
        }

        return $this->permissionRepository->create($data);
    }

    /**
     * Get permission by ID
     */
    public function getPermissionById(int $id): ?Permission
    {
        return $this->permissionRepository->findById($id);
    }

    /**
     * Update permission
     */
    public function updatePermission(Permission $permission, array $data): bool
    {
        // Check if permission name already exists (excluding current permission)
        if ($this->permissionRepository->existsByName($data['name'], $permission->id)) {
            throw new Exception('Permission dengan nama tersebut sudah ada.');
        }

        return $this->permissionRepository->update($permission, $data);
    }

    /**
     * Delete permission
     */
    public function deletePermission(Permission $permission): bool
    {
        // Check if permission is assigned to any roles
        if ($permission->roles()->count() > 0) {
            throw new Exception('Permission tidak dapat dihapus karena masih digunakan oleh role.');
        }

        return $this->permissionRepository->delete($permission);
    }

    /**
     * Delete multiple permissions
     */
    public function deleteMultiplePermissions(array $ids): bool
    {
        // Check if any permissions are assigned to roles
        $permissions = Permission::whereIn('id', $ids)->with('roles')->get();

        $assignedPermissions = $permissions->filter(function ($permission) {
            return $permission->roles()->count() > 0;
        });

        if ($assignedPermissions->count() > 0) {
            $names = $assignedPermissions->pluck('name')->implode(', ');
            throw new Exception("Permission berikut tidak dapat dihapus karena masih digunakan oleh role: {$names}");
        }

        return $this->permissionRepository->deleteMultiple($ids);
    }

    /**
     * Get permission statistics
     */
    public function getPermissionStats(): array
    {
        $permissions = $this->permissionRepository->getWithRoles();

        return [
            'total_permissions' => $permissions->count(),
            'assigned_permissions' => $permissions->filter(function ($permission) {
                return $permission->roles->count() > 0;
            })->count(),
            'unassigned_permissions' => $permissions->filter(function ($permission) {
                return $permission->roles->count() === 0;
            })->count(),
        ];
    }

    /**
     * Search permissions by name
     */
    public function searchPermissions(string $search): Collection
    {
        return Permission::where('name', 'like', "%{$search}%")
            ->with('roles')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get permission by name
     */
    public function getPermissionByName(string $name): ?Permission
    {
        return $this->permissionRepository->findByName($name);
    }
}
