<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'image',
        'last_login_at',
        'last_login_ip',
        'is_online',
        'last_activity_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'is_online' => 'boolean',
        ];
    }

    public function getRoleSummaryAttribute()
    {
        $roles = $this->getRoleNames();
        if ($roles->isEmpty()) return '';
        return $roles->first() . ($roles->count() > 1 ? ' +' . ($roles->count() - 1) : '');
    }

    /**
     * Check if user is currently online (active within last 5 minutes)
     */
    public function getIsCurrentlyOnlineAttribute(): bool
    {
        if (!$this->last_activity_at) {
            return false;
        }

        return $this->last_activity_at->diffInMinutes(now()) <= 5;
    }

    /**
     * Get user's login status text
     */
    public function getLoginStatusAttribute(): string
    {
        if ($this->is_currently_online) {
            return 'Online';
        }

        if ($this->last_login_at) {
            return $this->last_login_at->diffForHumans();
        }

        return 'Belum pernah login';
    }

    /**
     * Get formatted last login info
     */
    public function getLastLoginInfoAttribute(): array
    {
        return [
            'status' => $this->login_status,
            'is_online' => $this->is_currently_online,
            'last_login' => $this->last_login_at,
            'last_login_formatted' => $this->last_login_at ? $this->last_login_at->format('M d, Y H:i') : 'Belum Pernah',
            'last_login_human' => $this->last_login_at ? $this->last_login_at->diffForHumans() : 'Belum Pernah',
            'ip_address' => $this->last_login_ip,
        ];
    }

    /**
     * Update user's login information
     */
    public function updateLoginInfo(string $ipAddress = null): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ipAddress ?? request()->ip(),
            'is_online' => true,
            'last_activity_at' => now(),
        ]);
    }

    /**
     * Update user's activity timestamp
     */
    public function updateActivity(): void
    {
        $this->update([
            'last_activity_at' => now(),
            'is_online' => true,
        ]);
    }

    /**
     * Mark user as offline
     */
    public function markOffline(): void
    {
        $this->update(['is_online' => false]);
    }

    /**
     * Get user image URL
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(storage_path('app/public/' . $this->image))) {
            return asset('storage/' . $this->image);
        }

        // Return default avatar based on first letter of name
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get initials for avatar
     */
    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->name);
        $initials = '';

        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }

        return substr($initials, 0, 2);
    }
}
