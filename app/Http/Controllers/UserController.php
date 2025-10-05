<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserUpdateDetailRequest;
use App\Http\Requests\UserUpdatePasswordRequest;
use App\Http\Requests\UserUpdateRoleRequest;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->datatableData($request);
        }

        $metadata = [
            'title' => 'Pengguna',
            'desc' => 'Halaman yang berisi daftar pengguna aplikasi.',
            'bread1' => '<i class="ki-outline ki-home text-gray-700 fs-6"></i>',
            'bread1_link' => route('dashboard'),
            'bread2' => 'Dashboard',
            'bread2_link' => route('dashboard'),
            'bread3' => 'Pengguna',
            'bread3_link' => route('setting.user.index'),
            'bread4' => '',
            'bread4_link' => '',
            'bread5' => '',
            'bread5_link' => '',
            'page' => 'Daftar',
        ];

        $roles = $this->userService->getAvailableRoles();
        $statistics = $this->userService->getUserStatistics();

        // For non-AJAX requests, we don't need the result data anymore
        // since DataTable will load data via AJAX
        return view('dashboard.pages.users.index', compact('metadata', 'roles', 'statistics'));
    }

    /**
     * Get data for DataTable
     */
    public function datatableData(Request $request): JsonResponse
    {
        // Handle DataTables search parameter (it comes as array)
        $searchParam = $request->get('search', []);
        $search = is_array($searchParam) ? ($searchParam['value'] ?? '') : $searchParam;

        $role = $request->get('role', '');
        $status = $request->get('status', '');
        $verified = $request->get('verified', '');
        $start = (int) $request->get('start', 0);
        $length = (int) $request->get('length', 10);

        // Handle DataTables order parameter
        $orderParam = $request->get('order', []);
        $orderColumnIndex = (int) ($orderParam[0]['column'] ?? 0);
        $orderDirection = $orderParam[0]['dir'] ?? 'asc';

        // Column mapping for ordering
        $columns = ['id', 'name', 'email', 'created_at'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';

        $result = $this->userService->getDatatableData([
            'search' => $search,
            'role' => $role,
            'status' => $status,
            'verified' => $verified,
            'start' => $start,
            'length' => $length,
            'order_column' => $orderColumn,
            'order_direction' => $orderDirection,
        ]);

        return response()->json([
            'draw' => (int) $request->get('draw', 1),
            'recordsTotal' => $result['total'],
            'recordsFiltered' => $result['filtered'],
            'data' => $result['data']->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'image_url' => $user->image_url,
                    'initials' => $user->initials,
                    'roles' => $user->role_summary,
                    'login_status' => $user->login_status,
                    'is_online' => $user->is_currently_online,
                    'verified' => !is_null($user->email_verified_at),
                    'created_at' => $user->created_at->format('M d, Y'),
                    'created_at_formatted' => $user->created_at->diffForHumans(),
                ];
            })
        ]);
    }

    /**
     * Store a newly created user
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->createUser($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditambahkan.',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified user
     */
    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        try {
            $updatedUser = $this->userService->updateUser($user, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'User berhasil diperbarui.',
                'data' => $updatedUser
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user details (name, email, image only)
     */
    public function updateDetail(UserUpdateDetailRequest $request, User $user): JsonResponse
    {
        try {
            $updatedUser = $this->userService->updateUser($user, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Detail pengguna berhasil diperbarui.',
                'data' => $updatedUser
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui detail pengguna: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user password
     */
    public function updatePassword(UserUpdatePasswordRequest $request, User $user): JsonResponse
    {
        try {
            $this->userService->updateUserPassword($user, $request->input('new_password'));

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user roles
     */
    public function updateRole(UserUpdateRoleRequest $request, User $user): JsonResponse
    {
        try {
            $updatedUser = $this->userService->updateUserRoles($user, $request->input('user_role'));

            return response()->json([
                'success' => true,
                'message' => 'Role pengguna berhasil diperbarui.',
                'data' => $updatedUser
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $this->userService->deleteUser($user);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Delete multiple users
     */
    public function destroyMultiple(Request $request): JsonResponse
    {
        $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'integer|exists:users,id'
        ]);

        try {
            $deletedCount = $this->userService->deleteMultipleUsers($request->user_ids);

            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} user berhasil dihapus."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Export users to Excel
     */
    public function export(Request $request)
    {
        try {
            $filters = [
                'search' => $request->get('search', ''),
                'role' => $request->get('role', ''),
                'status' => $request->get('status', ''),
                'verified' => $request->get('verified', ''),
            ];

            $filename = $this->userService->exportUsers($filters);

            return Response::download($filename, 'users_export.csv')->deleteFileAfterSend();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengekspor data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new user
     */
    public function create(): View
    {
        return view('user.create');
    }

    /**
     * Display the specified user
     */
    public function show(User $user): View
    {
        $metadata = [
            'title' => 'Pengguna',
            'desc' => 'Halaman yang berisi detail pengguna aplikasi.',
            'bread1' => '<i class="ki-outline ki-home text-gray-700 fs-6"></i>',
            'bread1_link' => route('dashboard'),
            'bread2' => 'Dashboard',
            'bread2_link' => route('dashboard'),
            'bread3' => 'Pengguna',
            'bread3_link' => route('setting.user.index'),
            'bread4' => $user->name,
            'bread4_link' => '',
            'bread5' => '',
            'bread5_link' => '',
            'page' => 'Detail',
        ];

        $roles = $this->userService->getAvailableRoles();

        // For non-AJAX requests, we don't need the result data anymore
        // since DataTable will load data via AJAX
        return view('dashboard.pages.users.show', compact('metadata', 'roles', 'user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user): View
    {
        return view('user.edit', compact('user'));
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus(User $user): JsonResponse
    {
        try {
            $updatedUser = $this->userService->toggleUserStatus($user);

            return response()->json([
                'success' => true,
                'message' => 'Status user berhasil diubah.',
                'data' => [
                    'status' => $updatedUser->status,
                    'login_status' => $updatedUser->login_status
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Reset user password
     */
    public function resetPassword(User $user): JsonResponse
    {
        try {
            $newPassword = $this->userService->resetUserPassword($user);

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset.',
                'data' => [
                    'new_password' => $newPassword
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Send email verification
     */
    public function sendVerification(User $user): JsonResponse
    {
        try {
            $this->userService->sendEmailVerification($user);

            return response()->json([
                'success' => true,
                'message' => 'Email verifikasi berhasil dikirim.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get available roles for selection
     */
    public function getRoles(): JsonResponse
    {
        try {
            $roles = $this->userService->getAvailableRoles();

            return response()->json([
                'success' => true,
                'data' => $roles->map(function ($role) {
                    return [
                        'value' => $role->name,
                        'text' => ucfirst($role->name)
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
