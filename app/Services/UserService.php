<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users with roles
     */
    public function getAllUsers(): Collection
    {
        return $this->userRepository->getAllWithRoles();
    }

    /**
     * Get paginated users with filters
     */
    public function getPaginatedUsers(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->userRepository->getPaginated($filters, $perPage);
    }

    /**
     * Create new user
     */
    public function createUser(array $data): User
    {
        // Handle image upload
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->uploadImage($data['image']);
        }

        return $this->userRepository->create($data);
    }

    /**
     * Update user
     */
    public function updateUser(User $user, array $data): User
    {
        // Handle image upload
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            // Delete old image if exists
            if ($user->image) {
                $this->deleteImage($user->image);
            }
            $data['image'] = $this->uploadImage($data['image']);
        }

        return $this->userRepository->update($user, $data);
    }

    /**
     * Update user password
     */
    public function updateUserPassword(User $user, string $newPassword): User
    {
        $data = [
            'password' => $newPassword // Kirim password mentah, biar repository yang hash
        ];

        return $this->userRepository->update($user, $data);
    }

    /**
     * Update user roles
     */
    public function updateUserRoles(User $user, array $roleIds): User
    {
        // Validate that all role IDs exist
        $validRoles = Role::whereIn('id', $roleIds)->pluck('id')->toArray();

        if (count($validRoles) !== count($roleIds)) {
            throw new \Exception('Satu atau lebih role tidak valid.');
        }

        // Prevent removing all roles from current user
        if ($user->id === Auth::id() && empty($roleIds)) {
            throw new \Exception('Tidak dapat menghapus semua role dari akun sendiri.');
        }

        // Sync roles
        $user->roles()->sync($roleIds);

        // Reload user with roles
        return $user->load('roles');
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user): bool
    {
        // Prevent deleting own account
        if ($user->id === Auth::id()) {
            throw new \Exception('Tidak dapat menghapus akun sendiri.');
        }

        return $this->userRepository->delete($user);
    }

    /**
     * Delete multiple users
     */
    public function deleteMultipleUsers(array $userIds): int
    {
        // Remove current user from deletion list
        $userIds = array_filter($userIds, function ($id) {
            return $id != Auth::id();
        });

        if (empty($userIds)) {
            throw new \Exception('Tidak ada user yang dapat dihapus.');
        }

        return $this->userRepository->deleteMultiple($userIds);
    }

    /**
     * Get user by ID
     */
    public function getUserById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Get available roles
     */
    public function getAvailableRoles(): Collection
    {
        return Role::with('permissions')->get(); // tambahkan eager load
    }

    /**
     * Export users to CSV
     */
    public function exportUsersToCSV(array $filters = []): string
    {
        $users = $this->userRepository->getForExport($filters);

        $csvData = [];
        $csvData[] = ['Name', 'Email', 'Roles', 'Status', 'Verified', 'Last Login', 'Created At'];

        foreach ($users as $user) {
            $csvData[] = [
                $user->name,
                $user->email,
                $user->roles->pluck('name')->implode(', '),
                $user->login_status,
                $user->email_verified_at ? 'Verified' : 'Unverified',
                $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never',
                $user->created_at->format('Y-m-d H:i:s'),
            ];
        }

        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
        $filePath = storage_path('app/public/exports/' . $filename);

        // Create directory if not exists
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }

        $file = fopen($filePath, 'w');
        foreach ($csvData as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        return $filename;
    }

    /**
     * Get data for DataTable with filtering and pagination
     */
    public function getDatatableData(array $params): array
    {
        $query = User::with('roles');

        // Apply search filter
        if (!empty($params['search'])) {
            $query->where(function ($q) use ($params) {
                $q->where('name', 'like', '%' . $params['search'] . '%')
                    ->orWhere('email', 'like', '%' . $params['search'] . '%');
            });
        }

        // Apply role filter
        if (!empty($params['role'])) {
            $query->whereHas('roles', function ($q) use ($params) {
                $q->where('name', $params['role']);
            });
        }

        // Apply status filter
        if (!empty($params['status'])) {
            if ($params['status'] === 'online') {
                $query->where('is_online', true);
            } elseif ($params['status'] === 'offline') {
                $query->where('is_online', false);
            }
        }

        // Apply verified filter
        if (!empty($params['verified'])) {
            if ($params['verified'] === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($params['verified'] === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        // Get total count before pagination
        $total = User::count();
        $filtered = $query->count();

        // Apply sorting
        $query->orderBy($params['order_column'] ?? 'id', $params['order_direction'] ?? 'asc');

        // Apply pagination
        $data = $query->skip($params['start'] ?? 0)
            ->take($params['length'] ?? 10)
            ->get();

        return [
            'total' => $total,
            'filtered' => $filtered,
            'data' => $data
        ];
    }

    /**
     * Export users to Excel
     */
    public function exportUsers(array $filters = [])
    {
        // For now, return CSV export
        return $this->exportUsersToCSV($filters);
    }

    /**
     * Toggle user status
     */
    public function toggleUserStatus(User $user): User
    {
        $user->is_online = !$user->is_online;
        $user->save();

        return $user;
    }

    /**
     * Reset user password
     */
    public function resetUserPassword(User $user): string
    {
        $newPassword = Str::random(8);
        $user->password = Hash::make($newPassword);
        $user->save();

        return $newPassword;
    }

    /**
     * Send email verification
     */
    public function sendEmailVerification(User $user): void
    {
        // Implementation for sending email verification
        // This would typically use Laravel's built-in email verification
        $user->sendEmailVerificationNotification();
    }

    /**
     * Get user statistics
     */
    public function getUserStatistics(): array
    {
        $totalUsers = $this->userRepository->getAllWithRoles()->count();
        $onlineUsers = User::where('is_online', true)->count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $unverifiedUsers = User::whereNull('email_verified_at')->count();

        return [
            'total' => $totalUsers,
            'online' => $onlineUsers,
            'offline' => $totalUsers - $onlineUsers,
            'verified' => $verifiedUsers,
            'unverified' => $unverifiedUsers,
        ];
    }

    /**
     * Upload user image
     */
    private function uploadImage(UploadedFile $image): string
    {
        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('images/users', $filename, 'public');

        return $path;
    }

    /**
     * Delete user image
     */
    private function deleteImage(string $imagePath): bool
    {
        if (Storage::disk('public')->exists($imagePath)) {
            return Storage::disk('public')->delete($imagePath);
        }

        return false;
    }
}
