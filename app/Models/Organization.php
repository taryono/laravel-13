<?php

namespace Models;

use Illuminate\Support\Facades\Cache;

class Organization extends BasedModel
{
    protected $fillable = [
        'parent_id',
        'code',
        'name',
        'legal_name',
        'organization_type_id',
        'currency_code',
        'timezone',
        'schema_name',
        'config'
    ];

    protected $casts = [
        'config' => 'array',
    ];

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function organizationType()
    {
        return $this->belongsTo(OrganizationType::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


    // Relasi
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    // ==================================================
    // 🔑 INI METODE YANG DIMAKSUD
    // ==================================================

    /**
     * Ambil SEMUA ID turunan termasuk dirinya sendiri
     * Digunakan agar Admin Pusat bisa melihat semua cabang di bawahnya
     */
    public function getAllDescendantOrganizationIds(): array
    {
        $ids = collect([$this->id]);

        foreach ($this->children as $child) {
            $ids = $ids->merge($child->getAllDescendantOrganizationIds());
        }

        return $ids->unique()->values()->all();
    }

    /**
     * Versi statis: ambil dari organisasi aktif user
     */
    public static function getIdsForUser($user): array
    {
        // Super Admin Sistem = kembalikan semua organisasi
        if ($user->isSystemSuperAdmin()) {
            return static::pluck('id')->toArray();
        }

        $org = $user->activeOrganization();
        if (!$org) {
            return [];
        }

        return $org->getAllDescendantOrganizationIds();
    }

    // Cek apakah organisasi ini di bawah organisasi induk
    public function isDescendantOf($ancestorId): bool
    {
        if ($this->id === $ancestorId) return true;
        if (!$this->parent_id) return false;

        return $this->parent->isDescendantOf($ancestorId);
    }

    public static function descendantsOf($orgId = null)
    {
        if (!$orgId) return [];

        return Cache::remember("org_{$orgId}_descendants", 3600, function () use ($orgId) {
            $root = self::with('children')->find($orgId); // eager load biar gak N+1
            if (!$root) return [];

            return $root->getAllDescendantOrganizationIds();
        });
    }
}
