<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionStoreRequest;
use App\Http\Requests\PermissionUpdateRequest;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Permission;

use Exception;

class PermissionController extends Controller
{
    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of permissions
     */
    public function index(Request $request)
    {
        $metadata = [
            'title' => 'Permission',
            'desc' => 'Halaman yang berisi daftar permission aplikasi.',
            'bread1' => '<i class="ki-outline ki-home text-gray-700 fs-6"></i>',
            'bread1_link' => route('dashboard'),
            'bread2' => 'Dashboard',
            'bread2_link' => route('dashboard'),
            'bread3' => 'Permission',
            'bread3_link' => route('setting.permission.index'),
            'bread4' => '',
            'bread4_link' => '',
            'bread5' => '',
            'bread5_link' => '',
            'page' => 'Daftar',
        ];

        return view('dashboard.pages.permissions.index', compact('metadata'));
    }

    /**
     * Get permissions data for DataTable
     */
    public function datatableData(Request $request): JsonResponse
    {
        $data = $this->permissionService->getDatatableData($request);

        if (isset($data['error'])) {
            return response()->json($data, 500);
        }

        return response()->json($data);
    }

    /**
     * Store a newly created permission
     */
    public function store(PermissionStoreRequest $request): JsonResponse
    {
        try {
            $permission = $this->permissionService->createPermission($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Permission berhasil dibuat.',
                'data' => $permission
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Display the specified permission
     */
    public function show(Permission $permission): JsonResponse
    {
        $permission->load('roles');

        return response()->json([
            'success' => true,
            'data' => $permission
        ]);
    }

    /**
     * Update the specified permission
     */
    public function update(PermissionUpdateRequest $request, Permission $permission): JsonResponse
    {
        try {
            $this->permissionService->updatePermission($permission, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Permission berhasil diupdate.',
                'data' => $permission->fresh()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Remove the specified permission
     */
    public function destroy(Permission $permission): JsonResponse
    {
        try {
            $this->permissionService->deletePermission($permission);

            return response()->json([
                'success' => true,
                'message' => 'Permission berhasil dihapus.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Remove multiple permissions
     */
    public function destroyMultiple(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:permissions,id'
        ]);

        try {
            $this->permissionService->deleteMultiplePermissions($request->ids);

            return response()->json([
                'success' => true,
                'message' => 'Permission berhasil dihapus.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Search permissions
     */
    public function search(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        $permissions = $this->permissionService->searchPermissions($search);

        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }

    /**
     * Get permission statistics
     */
    public function stats(): JsonResponse
    {
        $stats = $this->permissionService->getPermissionStats();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
