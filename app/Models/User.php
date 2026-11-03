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
use Models\Role;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $guarded = ['id'];
    public $timestamps = true;
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'last_login_at',
        'last_login_ip',
        'current_organization_id',
        'isCheckingRole',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'last_login_at' => 'datetime',
        'deleted_at' => 'datetime',
        'isCheckingRole' => 'boolean',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'deleted_at'         => 'datetime',
        ];
    }

    // =====================================================
    // RELASI
    // =====================================================

    // Semua peran user di semua organisasi
    public function roleUsers()
    {
        return $this->hasMany(\Models\RoleUser::class);
    }

    // Peran dengan filter organisasi aktif
    public function roles()
    {
        return $this->belongsToMany(\Models\Role::class, 'role_user', 'user_id', 'role_id')
            ->withPivot(['organization_id', 'is_default'])
            ->withTimestamps();
    }

    // Organisasi yang diikuti user
    public function organizations()
    {
        return $this->belongsToMany(\Models\Organization::class, 'organization_user', 'user_id', 'organization_id')
            ->withPivot(['is_default', 'is_active', 'id'])
            ->wherePivot('is_active', 1)
            ->withTimestamps();
    }

    // =====================================================
    // ORGANISASI AKTIF
    // =====================================================

    // Tambahkan di User model
    public function isSuperAdmin(): bool
    {
        return cache()->remember('user_is_super_admin_' . $this->id, 300, function () {
            return $this->roles()->where('name', 'super_admin')->exists();
        });
    }

    public function currentOrganization()
    {
        return cache()->remember('user_org_' . $this->id, 300, function () {
            return $this->organizations()->first();
        });
    }

    public function getCurrentOrganizationIdAttribute()
    {
        return $this->activeOrganization()?->id;
    }

    public function getActiveOrganizationIdAttribute()
    {
        return session('active_organization_id')
            ?? ($this->attributes['active_organization_id'] ?? null)
            ?? $this->activeOrganization()?->id;
    }

    public function activeOrganization()
    {
        $orgId = session('active_organization_id');

        return $this->organizations()
            ->when($orgId, fn($q) => $q->where('organizations.id', $orgId))
            ->orderByRaw('is_default DESC, id ASC')
            ->first();
    }

    // Daftar ID organisasi user
    public function organizationIds(): array
    {
        return $this->organizations()->pluck('organizations.id')->toArray();
    }

    // =====================================================
    // CEK PERAN
    // =====================================================

    public function isSuperUser(): bool
    {
        return Cache::remember("user_{$this->id}_is_super", 3600, function () {
            return $this->roles()->withoutGlobalScopes()
                ->where('code', 'super-admin')
                ->exists();
        });
    }

    public function hasRole($role, $orgId = null): bool
    {
        if ($this->isSuperUser()) return true;

        $orgId = $orgId ?? $this->active_organization_id;
        $roles = (array)$role;
        $cacheKey = "user_{$this->id}_roles_" . md5(implode(',', $roles) . "_{$orgId}");

        return Cache::remember($cacheKey, 3600, function () use ($roles, $orgId) {
            return $this->roles()
                ->when($orgId, fn($q) => $q->where('organization_id', $orgId))
                ->where(function ($q) use ($roles) {
                    $q->whereIn('roles.name', $roles)
                        ->orWhereIn('roles.code', $roles);
                })
                ->exists();
        });
    }

    public function hasAnyRole($roles, $orgId = null): bool
    {
        return $this->hasRole($roles, $orgId);
    }

    public function authorizeRoles($roles, $orgId = null): bool
    {
        if ($this->isSuperUser()) return true;

        if (!$this->hasAnyRole($roles, $orgId)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        return true;
    }

    // =====================================================
    // CEK IZIN MENU (CRUD)
    // =====================================================

    public function hasMenuPermission($menuSlug, $action = 'is_view', $orgId = null): bool
    {
        // Super Admin = semua izin
        if ($this->isSuperUser()) return true;

        $orgId = $orgId ?? $this->active_organization_id;

        $menu = \Models\Menu::where('slug', $menuSlug)->first();
        if (!$menu) return false;

        // Ambil ID peran user di organisasi aktif
        $roleIds = $this->roleUsers()
            ->where('organization_id', $orgId)
            ->whereNull('deleted_at')
            ->pluck('role_id');

        if ($roleIds->isEmpty()) return false;

        return \Models\MenuRole::where('menu_id', $menu->id)
            ->whereIn('role_id', $roleIds)
            ->where($action, 1)
            ->exists();
    }

    // =====================================================
    // CEK IZIN AKSI KHUSUS (di luar CRUD)
    // =====================================================

    public function hasActionPermission($actionName, $orgId = null): bool
    {
        if ($this->isSuperUser()) return true;

        $orgId = $orgId ?? $this->active_organization_id;

        $action = \Models\Action::where('name', $actionName)
            ->where('organization_id', $orgId)
            ->first();

        if (!$action) return false;

        // Cek langsung ke user
        $direct = \Models\ActionUser::where('action_id', $action->id)
            ->where('user_id', $this->id)
            ->exists();

        if ($direct) return true;

        // Cek lewat peran
        $roleIds = $this->roleUsers()
            ->where('organization_id', $orgId)
            ->whereNull('deleted_at')
            ->pluck('role_id');

        return \Models\ActionRole::where('action_id', $action->id)
            ->whereIn('role_id', $roleIds)
            ->exists();
    }

    // =====================================================
    // SESI & BANTUAN LAIN
    // =====================================================

    public function setAuthSession($role, $roles)
    {
        session()->put('user_auth', [
            'login_as'        => $role->code,
            'active_role_id'  => $role->id,
            'auth_role_ids'   => $roles->pluck('id'),
            'auth_roles'      => $roles->pluck('name'),
            'auth_role'       => $role,
            'layout'          => $role->layout ?? null,
            'origin_role'     => $role,
        ]);
        session()->put('role_ids', $roles->pluck('id'));
    }

    public function isMale()
    {
        return ($this->sex ?? '') === 'L';
    }

    public function isFemale()
    {
        return ($this->sex ?? '') === 'P';
    }

    public function gender()
    {
        return $this->isFemale() ? 'Perempuan' : 'Laki-laki';
    }

    public function whatsapp()
    {
        return $this->cellphone ?? $this->phone ?? '';
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn($v) => $v,
            set: fn($v) => strtolower(trim($v))
        );
    }

    // =====================================================
    // MANAJEMEN CACHE
    // =====================================================

    public function clearRoleCache()
    {
        Cache::forget("user_{$this->id}_is_super");

        // Hapus semua cache peran user ini
        $prefix = "user_{$this->id}_";
        Cache::flush(); // Atau gunakan tag jika pakai Redis
    }

    protected static function booted()
    {
        static::saved(function ($user) {
            if ($user->isDirty('roles') || $user->wasChanged()) {
                $user->clearRoleCache();
                $user->clearUserCache();
            }
        });

        static::deleted(function ($user) {
            $user->clearRoleCache();
            $user->clearUserCache();
        });

        static::restored(function ($user) {
            $user->clearRoleCache();
            $user->clearUserCache();
        });
    }

    public function clearUserCache()
    {
        cache()->forget('user_is_super_admin_' . $this->id);
        cache()->forget('user_org_' . $this->id);
        Cache::forget("user_{$this->id}_is_super");
    }

    // Cek apakah ini SUPER ADMIN SISTEM (kamu)
    public function isSystemSuperAdmin(): bool
    {
        return Cache::remember("user_{$this->id}_is_sys_super", 3600, function () {
            return $this->roles()
                ->wherePivotNull('organization_id') // Peran lintas sistem
                ->where('code', 'super-admin')
                ->exists();
        });
    }

    // Cek apakah ini ADMIN ORGANISASI
    public function isOrganizationAdmin($orgId = null): bool
    {
        if ($this->isSystemSuperAdmin()) return true; // Sistem super admin = otomatis bisa semua

        $orgId = $orgId ?? $this->active_organization_id;

        return $this->hasRole('org-admin', $orgId);
    }

    public function getAllAccessibleOrganizationIds(): array
    {
        return \Models\Organization::getIdsForUser($this);
    }


    // Cek apakah bisa mengelola peran di bawahnya
    public function canManageRole($roleId, $orgId = null): bool
    {
        if ($this->isSystemSuperAdmin()) return true;

        $orgId = $orgId ?? $this->active_organization_id;

        // Admin organisasi bisa mengelola semua peran di organisasinya
        if ($this->isOrganizationAdmin($orgId)) return true;

        // Peran biasa hanya bisa mengelola turunan langsungnya
        $myRoleIds = $this->roles()
            ->wherePivot('organization_id', $orgId)
            ->pluck('id');

        $role = Role::find($roleId);

        // Cek apakah peran target ada di bawah salah satu peran saya
        return in_array($role?->parent_id, $myRoleIds->toArray());
    }

    // User.php
    public function descendantOrganizationIds(?int $orgId = null): array
    {
        $orgId = $orgId ?? $this->active_organization_id;

        if (!$orgId) {
            return [];
        }

        return \Models\Organization::descendantsOf($orgId); // reuse logic yang sudah benar, sekali diperbaiki, berlaku di semua tempat
    }
}
