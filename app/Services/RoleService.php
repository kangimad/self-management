<?php

namespace App\Services;

use App\Repositories\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class RoleService
{
    protected RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Get all roles
     */
    public function getAllRoles(): Collection
    {
        return $this->roleRepository->getAllRoles();
    }

    /**
     * Get paginated roles for DataTable
     */
    public function getPaginatedRoles(int $perPage = 10): LengthAwarePaginator
    {
        return $this->roleRepository->getPaginatedRoles($perPage);
    }

    /**
     * Get role by ID
     */
    public function getRoleById(int $id): ?Role
    {
        return $this->roleRepository->findRoleById($id);
    }

    /**
     * Get role with permissions
     */
    public function getRoleWithPermissions(int $id): ?Role
    {
        return $this->roleRepository->getRoleWithPermissions($id);
    }

    /**
     * Create new role
     */
    public function createRole(array $data): Role
    {
        try {
            $role = $this->roleRepository->createRole($data);

            // Sync permissions if provided
            if (isset($data['permissions']) && is_array($data['permissions'])) {
                $this->roleRepository->syncRolePermissions($role, $data['permissions']);
            }

            return $role;
        } catch (Exception $e) {
            throw new Exception('Failed to create role: ' . $e->getMessage());
        }
    }

    /**
     * Update role
     */
    public function updateRole(int $id, array $data): bool
    {
        try {
            $role = $this->roleRepository->findRoleById($id);

            if (!$role) {
                throw new Exception('Role not found');
            }

            $updated = $this->roleRepository->updateRole($id, $data);

            if ($updated && isset($data['permissions']) && is_array($data['permissions'])) {
                $this->roleRepository->syncRolePermissions($role, $data['permissions']);
            }

            return $updated;
        } catch (Exception $e) {
            throw new Exception('Failed to update role: ' . $e->getMessage());
        }
    }

    /**
     * Delete role
     */
    public function deleteRole(int $id): bool
    {
        try {
            $role = $this->roleRepository->findRoleById($id);

            if (!$role) {
                throw new Exception('Role not found');
            }

            // Check if role is being used by users
            if ($role->users()->count() > 0) {
                throw new Exception('Cannot delete role that is assigned to users');
            }

            return $this->roleRepository->deleteRole($id);
        } catch (Exception $e) {
            throw new Exception('Failed to delete role: ' . $e->getMessage());
        }
    }

    /**
     * Delete multiple roles
     * @return array{deleted_count: int, errors: string[], has_errors: bool}
     */
    public function deleteMultipleRoles(array $roleIds): array
    {
        $deletedCount = 0;
        $errors = [];

        foreach ($roleIds as $roleId) {
            try {
                $role = $this->roleRepository->findRoleById($roleId);

                if (!$role) {
                    $errors[] = "Role dengan ID {$roleId} tidak ditemukan";
                    continue;
                }

                // Check if role is being used by users
                if ($role->users()->count() > 0) {
                    $errors[] = "Role '{$role->name}' tidak dapat dihapus karena masih digunakan oleh user";
                    continue;
                }

                if ($this->roleRepository->deleteRole($roleId)) {
                    $deletedCount++;
                }
            } catch (Exception $e) {
                $errors[] = "Gagal menghapus role dengan ID {$roleId}: " . $e->getMessage();
            }
        }

        if (!empty($errors) && $deletedCount === 0) {
            throw new Exception(implode('; ', $errors));
        }

        // Return array with detailed information for partial success
        return [
            'deleted_count' => $deletedCount,
            'errors' => $errors,
            'has_errors' => !empty($errors)
        ];
    }

    /**
     * Get users with specific role for DataTable
     */
    public function getUsersWithRoleForDataTable(Request $request, int $roleId): array
    {
        // Get role first to validate
        $role = $this->roleRepository->findRoleById($roleId);
        if (!$role) {
            throw new Exception('Role not found');
        }

        $query = User::whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role->name);
        })->with(['roles']);

        // Handle search
        if ($search = $request->get('search')['value'] ?? '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Get total records
        $totalRecords = User::whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role->name);
        })->count();
        $filteredRecords = $query->count();        // Handle pagination
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);

        // Handle ordering
        $orderColumn = $request->get('order')[0]['column'] ?? 1;
        $orderDir = $request->get('order')[0]['dir'] ?? 'asc';

        $columns = ['id', 'name', 'name', 'login_status', 'created_at'];
        if (isset($columns[$orderColumn])) {
            $query->orderBy($columns[$orderColumn], $orderDir);
        }

        $users = $query->skip($start)->take($length)->get();

        $data = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'image_url' => $user->image_url ?? null,
                'initials' => $user->initials,
                'is_online' => $user->is_currently_online,
                'login_status' => $user->is_currently_online ? 'Online' : 'Offline',
                'created_at' => $user->created_at->format('j M Y'),
                'created_at_formatted' => $user->created_at->format('g:i A'),
                'roles' => $user->roles->pluck('name')->implode(', '),
            ];
        });

        return [
            'draw' => intval($request->get('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ];
    }

    /**
     * Get all permissions grouped by category
     */
    public function getAllPermissions(): array
    {
        $permissions = $this->roleRepository->getAllPermissions();

        // Group permissions by category (assuming permission names follow pattern: category-action)
        $groupedPermissions = [];

        foreach ($permissions as $permission) {
            $parts = explode('-', $permission->name);
            $category = ucfirst($parts[0] ?? 'general');

            if (!isset($groupedPermissions[$category])) {
                $groupedPermissions[$category] = [];
            }

            $groupedPermissions[$category][] = $permission;
        }

        return $groupedPermissions;
    }

    /**
     * Get all permissions (flat list)
     */
    public function getAllPermissionsFlat(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->roleRepository->getAllPermissions();
    }

    /**
     * Get DataTable data for AJAX
     */
    public function getDataTableData($request): array
    {
        $query = Role::withCount('users')->with('permissions');

        // Handle search
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $query->where('name', 'like', "%{$searchValue}%");
        }

        // Handle ordering
        if ($request->has('order')) {
            $orderColumn = $request->columns[$request->order[0]['column']]['data'];
            $orderDirection = $request->order[0]['dir'];

            if ($orderColumn === 'name') {
                $query->orderBy('name', $orderDirection);
            } elseif ($orderColumn === 'users_count') {
                $query->orderBy('users_count', $orderDirection);
            }
        }

        // Get total records before pagination
        $totalRecords = Role::count();
        $filteredRecords = $query->count();

        // Handle pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;

        $roles = $query->offset($start)->limit($length)->get();

        // Format data for DataTable
        $data = [];
        foreach ($roles as $role) {
            $permissions = $role->permissions->pluck('name')->toArray();
            $permissionsDisplay = count($permissions) > 0
                ? implode(', ', array_slice($permissions, 0, 3)) . (count($permissions) > 3 ? '...' : '')
                : 'No permissions';

            $data[] = [
                'id' => $role->id,
                'name' => $role->name,
                'users_count' => $role->users_count,
                'permissions' => $permissionsDisplay,
                'permissions_count' => count($permissions),
                'created_at' => $role->created_at->format('d/m/Y H:i'),
                'actions' => $role->id // Will be processed in the view
            ];
        }

        return [
            'draw' => $request->draw ?? 1,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ];
    }

    /**
     * Remove user from role
     */
    public function removeUserFromRole(int $userId, int $roleId): bool
    {
        // Check if user exists
        $user = User::find($userId);
        if (!$user) {
            throw new Exception('User tidak ditemukan.');
        }

        // Check if role exists
        $role = $this->roleRepository->findRoleById($roleId);
        if (!$role) {
            throw new Exception('Role tidak ditemukan.');
        }

        // Check if user has this role
        if (!$user->hasRole($role->name)) {
            throw new Exception('User tidak memiliki role ini.');
        }

        // Prevent removing all roles from current user
        if ($user->id === Auth::id() && $user->roles()->count() === 1) {
            throw new Exception('Tidak dapat menghapus semua role dari akun sendiri.');
        }

        return $this->roleRepository->removeUserFromRole($userId, $roleId);
    }

    /**
     * Remove multiple users from role
     */
    public function removeMultipleUsersFromRole(array $userIds, int $roleId): array
    {
        // Check if role exists
        $role = $this->roleRepository->findRoleById($roleId);
        if (!$role) {
            throw new Exception('Role tidak ditemukan.');
        }

        $results = [];
        $successful = 0;
        $failed = 0;

        foreach ($userIds as $userId) {
            try {
                $user = User::find($userId);
                if (!$user) {
                    $results[] = [
                        'user_id' => $userId,
                        'success' => false,
                        'message' => 'User tidak ditemukan.'
                    ];
                    $failed++;
                    continue;
                }

                // Check if user has this role
                if (!$user->hasRole($role->name)) {
                    $results[] = [
                        'user_id' => $userId,
                        'success' => false,
                        'message' => 'User tidak memiliki role ini.'
                    ];
                    $failed++;
                    continue;
                }

                // Prevent removing all roles from current user
                if ($user->id === Auth::id() && $user->roles()->count() === 1) {
                    $results[] = [
                        'user_id' => $userId,
                        'success' => false,
                        'message' => 'Tidak dapat menghapus semua role dari akun sendiri.'
                    ];
                    $failed++;
                    continue;
                }

                $success = $this->roleRepository->removeUserFromRole($userId, $roleId);
                $results[] = [
                    'user_id' => $userId,
                    'success' => $success,
                    'message' => $success ? 'Berhasil dihapus.' : 'Gagal menghapus role.'
                ];

                if ($success) {
                    $successful++;
                } else {
                    $failed++;
                }
            } catch (Exception $e) {
                $results[] = [
                    'user_id' => $userId,
                    'success' => false,
                    'message' => $e->getMessage()
                ];
                $failed++;
            }
        }

        return [
            'results' => $results,
            'summary' => [
                'total' => count($userIds),
                'successful' => $successful,
                'failed' => $failed
            ]
        ];
    }
}
