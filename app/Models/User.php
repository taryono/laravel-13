<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
// use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $guarded = ['id'];
    public $timestamps = true;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'deleted_at' => 'datetime',
        ];
    }

    // --- Relasi ---
    public function roles()
    {
        return $this->belongsToMany(\Models\Role::class, 'role_user')
            ->withPivot('is_default')
            ->withTimestamps();
    }

    // --- Cek Peran ---
    public function isSuperUser(): bool
    {
        return Cache::remember("user_{$this->id}_is_super", 3600, function () {
            return $this->roles()->withoutGlobalScopes()
                ->where('name', 'super-admin')
                ->exists();
        });
    }


    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    // --- Fungsi Bantu ---
    public function authorizeRoles($roles)
    {
        if ($this->isSuperUser()) return true;

        $hasRole = is_array($roles)
            ? $this->hasAnyRole($roles)
            : $this->hasRole($roles);

        if (!$hasRole) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return true;
    }

    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', (array)$roles)->exists();
    }

    // Tambahkan method untuk clear cache
    public function clearRoleCache()
    {
        $cacheKeys = [
            "user_{$this->id}_role_super-admin",
            "user_{$this->id}_role_admin",
            // dll
        ];
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
        Cache::forget("user_{$this->id}_roles");
    }

    // Atau pakai cache dengan tag (Redis/Memcached)
    public function hasRole($role)
    {
        $cacheKey = "user_{$this->id}_role_{$role}";
        if (!is_array($role)) {
            return Cache::remember($cacheKey, 3600, function () use ($role) {
                return $this->roles()->where('name', $role)->exists();
            });
        } else {
            return Cache::remember($cacheKey, 3600, function () use ($role) {
                return $this->roles()->whereIn('name', $role)->exists();
            });
        }
    }

    // Panggil saat role berubah
    public function syncRoles(...$roles)
    {
        parent::syncRoles($roles);

        // Hapus semua cache yang berkaitan dengan user ini
        $cacheKeys = [
            "user_{$this->id}_role_admin",
            "user_{$this->id}_role_manager",
            "user_{$this->id}_role_special_role",
            // Tambahkan semua role yang mungkin
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Atau lebih simple: flush semua cache user
        // Cache::forget("user_{$this->id}_role_*"); // Tidak support wildcard
    }

    // --- Session & Lainnya ---
    public function setAuthSession($role, $roles)
    {
        session()->put('user_auth', [
            'login_as'      => $role->name,
            'active_role_id'  => $role->id,
            'auth_role_ids' => $roles->pluck('id'),
            'auth_roles'    => $roles->pluck('name'),
            'auth_role'     => $role,
            'layout'        => $role->layout,
            'origin_role'   => $role,
        ]);
        session()->put('role_ids', $roles->pluck('id'));
    }
    public function isMale()
    {
        return $this->sex == "L";
    }
    public function isFemale()
    {
        return $this->sex == "P";
    }
    public function gender()
    {
        return $this->sex == "P" ? "Perempuan" : "Laki-laki";
    }
    public function whatsapp()
    {
        return $this->cellphone;
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn($v) => $v,
            set: fn($v) => strtolower(trim($v))
        );
    }

    protected static function booted()
    {
        static::deleted(function ($user) {
            $user->clearRoleCache();
        });

        static::restored(function ($user) {
            $user->clearRoleCache();
        });

        // Clear cache saat role sync (gunakan event observer)
        static::saved(function ($user) {
            if ($user->wasChanged()) {
                $user->clearRoleCache();
            }
        });
    }

    public function organizations()
    {
        return $this->belongsToMany(\Models\Organization::class, 'organization_user')
            ->withPivot('is_default', 'is_active')
            ->wherePivot('is_active', 1)
            ->withTimestamps();
    }

    public function organizationIds(): array
    {
        return $this->organizations()->pluck('organizations.id')->toArray();
    }
}
