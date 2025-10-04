<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaseFinanceController extends Controller
{
    /**
     * Check if user has specific finance permission
     */
    protected function checkPermission(string $permission): bool
    {
        /** @var User $user */
        $user = Auth::user();
        return $user->can($permission);
    }

    /**
     * Check if user has any of the specified permissions
     */
    protected function checkAnyPermission(array $permissions): bool
    {
        /** @var User $user */
        $user = Auth::user();
        return $user->hasAnyPermission($permissions);
    }

    /**
     * Check if user has role
     */
    protected function checkRole(string $role): bool
    {
        /** @var User $user */
        $user = Auth::user();
        return $user->hasRole($role);
    }

    /**
     * Authorize user for specific permission or throw 403
     */
    protected function authorizePermission(string $permission): void
    {
        if (!$this->checkPermission($permission)) {
            abort(403, 'Unauthorized action.');
        }
    }
}
