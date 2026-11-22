<?php

namespace Models;

use Illuminate\Support\Facades\Cache;

class Organization extends SoftDeletableModel
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
        if ($this->id === $ancestorId)
            return true;
        if (!$this->parent_id)
            return false;

        return $this->parent->isDescendantOf($ancestorId);
    }

    public static function descendantsOf($orgId = null)
    {
        if (!$orgId)
            return [];

        return Cache::remember("org_{$orgId}_descendants", 3600, function () use ($orgId) {
            $root = self::with('children')->find($orgId); // eager load biar gak N+1
            if (!$root)
                return [];

            return $root->getAllDescendantOrganizationIds();
        });
    }

    /**
     * Ambil organisasi sebagai list datar yang sudah terurut hirarki,
     * dengan properti `depth` untuk indentasi.
     */
    public static function getHierarchicalList(?array $onlyIds = null)
    {
        $query = static::query()->orderBy('name');

        if ($onlyIds !== null) {
            $query->whereIn('id', $onlyIds);
        }

        $all = $query->get();
        $byParent = $all->groupBy('parent_id');
        $ids = $all->pluck('id')->all();

        $result = collect();

        $walk = function ($parentId, int $depth) use (&$walk, $byParent, $result) {
            foreach ($byParent->get($parentId, collect()) as $org) {
                $org->depth = $depth;
                $result->push($org);
                $walk($org->id, $depth + 1);
            }
        };

        // Root = parent_id null, ATAU parent tidak ada di daftar
        // (penting kalau user hanya boleh lihat sebagian cabang)
        $roots = $all->filter(fn($o) => is_null($o->parent_id) || !in_array($o->parent_id, $ids));

        foreach ($roots as $root) {
            $root->depth = 0;
            $result->push($root);
            $walk($root->id, 1);
        }

        return $result;
    }

    /**
     * Ambil tree organisasi sesuai hak akses user.
     * Jika $user null, pakai user yang sedang login.
     */
    public static function getTree($user = null)
    {
        $user = $user ?? auth()->user();

        // Tidak ada user (misal belum login) = kembalikan kosong
        if (!$user) {
            return collect();
        }

        return static::getHierarchicalList(
            static::getIdsForUser($user)
        );
    }

    public static function getFullTree()
    {
        return static::getHierarchicalList();
    }
}
