<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Http\Requests\RemoveUserFromRoleRequest;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Exception;

class RoleController extends Controller
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of roles
     */
    public function index(Request $request): View|JsonResponse
    {
        // Handle AJAX request for DataTable
        if ($request->ajax()) {
            try {
                $data = $this->roleService->getDataTableData($request);
                return response()->json($data);
            } catch (Exception $e) {
                return response()->json([
                    'error' => 'Failed to load data: ' . $e->getMessage()
                ], 500);
            }
        }

        $metadata = [
            'title' => 'Peran',
            'desc' => 'Halaman yang berisi daftar peran aplikasi.',
            'bread1' => '<i class="ki-outline ki-home text-gray-700 fs-6"></i>',
            'bread1_link' => route('dashboard'),
            'bread2' => 'Dashboard',
            'bread2_link' => route('dashboard'),
            'bread3' => 'Peran',
            'bread3_link' => route('setting.role.index'),
            'bread4' => '',
            'bread4_link' => '',
            'bread5' => '',
            'bread5_link' => '',
            'page' => 'Daftar',
        ];

        // Get all roles for card display
        $roles = $this->roleService->getAllRoles();
        $permissions = $this->roleService->getAllPermissionsFlat();

        return view('dashboard.pages.roles.index', compact('metadata', 'roles', 'permissions'));
    }

    /**
     * Show the form for creating a new role
     */
    public function create(): View
    {
        $metadata = [
            'title' => 'Tambah Peran',
            'desc' => 'Halaman untuk menambah peran baru.',
            'bread1' => '<i class="ki-outline ki-home text-gray-700 fs-6"></i>',
            'bread1_link' => route('dashboard'),
            'bread2' => 'Dashboard',
            'bread2_link' => route('dashboard'),
            'bread3' => 'Peran',
            'bread3_link' => route('setting.role.index'),
            'bread4' => 'Tambah',
            'bread4_link' => '',
            'bread5' => '',
            'bread5_link' => '',
            'page' => 'Form',
        ];

        $permissions = $this->roleService->getAllPermissions();

        return view('dashboard.pages.roles.create', compact('metadata', 'permissions'));
    }

    /**
     * Store a newly created role in storage
     */
    public function store(RoleRequest $request): JsonResponse
    {
        try {
            $role = $this->roleService->createRole($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil dibuat!',
                'data' => $role
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified role
     */
    public function show(int $id): View|JsonResponse
    {
        try {
            $role = $this->roleService->getRoleWithPermissions($id);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak ditemukan'
                ], 404);
            }

            $metadata = [
                'title' => 'Detail Peran',
                'desc' => 'Halaman detail peran ' . $role->name . '.',
                'bread1' => '<i class="ki-outline ki-home text-gray-700 fs-6"></i>',
                'bread1_link' => route('dashboard'),
                'bread2' => 'Dashboard',
                'bread2_link' => route('dashboard'),
                'bread3' => 'Peran',
                'bread3_link' => route('setting.role.index'),
                'bread4' => 'Detail',
                'bread4_link' => '',
                'bread5' => '',
                'bread5_link' => '',
                'page' => $role->name,
            ];

            $permissions = $this->roleService->getAllPermissions();

            return view('dashboard.pages.roles.show', compact('metadata', 'role', 'permissions'));
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get users with specific role for DataTable
     */
    public function getUsersWithRole(Request $request, int $id): JsonResponse
    {
        try {
            $data = $this->roleService->getUsersWithRoleForDataTable($request, $id);
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to load users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit(int $id): View|JsonResponse
    {
        try {
            $role = $this->roleService->getRoleWithPermissions($id);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak ditemukan'
                ], 404);
            }

            $permissions = $this->roleService->getAllPermissions();

            return response()->json([
                'success' => true,
                'data' => [
                    'role' => $role,
                    'permissions' => $permissions,
                    'rolePermissions' => $role->permissions->pluck('name')->toArray()
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified role in storage
     */
    public function update(RoleRequest $request, int $id): JsonResponse
    {
        try {
            $updated = $this->roleService->updateRole($id, $request->validated());

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil diperbarui!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified role from storage
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->roleService->deleteRole($id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil dihapus!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete multiple roles
     */
    public function destroyMultiple(Request $request): JsonResponse
    {
        $request->validate([
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'integer|exists:roles,id'
        ]);

        try {
            $result = $this->roleService->deleteMultipleRoles($request->role_ids);

            $message = "{$result['deleted_count']} role berhasil dihapus.";

            if ($result['has_errors']) {
                $message .= " Namun ada beberapa role yang tidak dapat dihapus: " . implode('; ', $result['errors']);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'deleted_count' => $result['deleted_count'],
                'has_partial_errors' => $result['has_errors'],
                'errors' => $result['errors']
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove user from role
     */
    public function removeUser(RemoveUserFromRoleRequest $request): JsonResponse
    {
        try {
            $userId = $request->input('user_id');
            $roleId = $request->input('role_id');

            $success = $this->roleService->removeUserFromRole($userId, $roleId);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'User berhasil dihapus dari role.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus user dari role.'
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove multiple users from role
     */
    public function removeMultipleUsers(Request $request): JsonResponse
    {
        $request->validate([
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'role_id' => ['required', 'integer', 'exists:roles,id']
        ]);

        try {
            $userIds = $request->input('user_ids');
            $roleId = $request->input('role_id');

            $result = $this->roleService->removeMultipleUsersFromRole($userIds, $roleId);

            $summary = $result['summary'];
            $message = "{$summary['successful']} user berhasil dihapus dari role.";

            if ($summary['failed'] > 0) {
                $message .= " {$summary['failed']} user gagal diproses.";
            }

            return response()->json([
                'success' => $summary['successful'] > 0,
                'message' => $message,
                'details' => $result['results'],
                'summary' => $summary
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
