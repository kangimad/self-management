<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Get all users with roles
     */
    public function getAllWithRoles(): Collection
    {
        return $this->model->with('roles')->get();
    }

    /**
     * Get paginated users with filters
     */
    public function getPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->with('roles');

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Apply role filter
        if (!empty($filters['role'])) {
            $query->whereHas('roles', function ($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        // Apply status filter
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'online') {
                $query->where('is_online', true);
            } elseif ($filters['status'] === 'offline') {
                $query->where('is_online', false);
            }
        }

        // Apply email verification filter
        if (!empty($filters['verified'])) {
            if ($filters['verified'] === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($filters['verified'] === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Create new user
     */
    public function create(array $data): User
    {
        $userData = [
            'name' => $data['user_name'] ?? $data['name'],
            'email' => $data['user_email'] ?? $data['email'],
            'password' => Hash::make($data['user_password'] ?? $data['password']),
        ];

        // Handle image upload
        if (!empty($data['image'])) {
            $userData['image'] = $data['image'];
        }

        $user = $this->model->create($userData);

        // Assign roles
        if (!empty($data['user_role'])) {
            $user->assignRole($data['user_role']);
        } elseif (!empty($data['roles'])) {
            $user->assignRole($data['roles']);
        }

        return $user;
    }

    /**
     * Update user
     */
    public function update(User $user, array $data): User
    {
        $userData = [
            'name' => $data['user_name'] ?? $data['name'],
            'email' => $data['user_email'] ?? $data['email'],
        ];

        // Only update password if provided
        if (!empty($data['user_password']) || !empty($data['password'])) {
            $userData['password'] = Hash::make($data['user_password'] ?? $data['password']);
        }

        // Handle image upload
        if (!empty($data['image'])) {
            $userData['image'] = $data['image'];
        }

        $user->update($userData);

        // Sync roles
        if (!empty($data['user_role'])) {
            $user->syncRoles($data['user_role']);
        } elseif (!empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user;
    }

    /**
     * Delete user
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Delete multiple users
     */
    public function deleteMultiple(array $userIds): int
    {
        return $this->model->whereIn('id', $userIds)->delete();
    }

    /**
     * Find user by ID
     */
    public function findById(int $id): ?User
    {
        return $this->model->with('roles')->find($id);
    }

    /**
     * Get users for export
     */
    public function getForExport(array $filters = []): Collection
    {
        $query = $this->model->with('roles');

        // Apply the same filters as getPaginated
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($filters['role'])) {
            $query->whereHas('roles', function ($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'online') {
                $query->where('is_online', true);
            } elseif ($filters['status'] === 'offline') {
                $query->where('is_online', false);
            }
        }

        if (!empty($filters['verified'])) {
            if ($filters['verified'] === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($filters['verified'] === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
