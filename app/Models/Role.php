<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;


class Role extends BasedModel
{
    protected $fillable = [
        'organization_id',
        'code',
        'name',
        'description',
        'parent_id',
        'is_system',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // === RELASI ===

    public function parent()
    {
        return $this->belongsTo(Role::class, 'parent_id')
            ->where('organization_id', $this->organization_id);
    }

    public function children()
    {
        return $this->hasMany(Role::class, 'parent_id');
    }

    // === FUNGSI POHON ===

    // Semua anak langsung + turunan (rekursif)
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    // Semua induk ke atas sampai akar
    public function allParents()
    {
        return $this->parent()->with('allParents');
    }

    // Dapatkan ID semua keturunan (bantu untuk query massal)
    public function getAllChildrenIds()
    {
        $ids = collect([$this->id]);
        $this->load('allChildren');

        $flatten = function ($role) use (&$flatten, &$ids) {
            foreach ($role->children as $child) {
                $ids->push($child->id);
                $flatten($child);
            }
        };

        $flatten($this);
        return $ids->unique()->values();
    }

    // Dapatkan ID semua induk
    public function getAllParentIds()
    {
        $ids = collect();
        $current = $this->parent;

        while ($current) {
            $ids->push($current->id);
            $current = $current->parent;
        }

        return $ids;
    }

    // Tambahkan di class Role
    // === TAMBAHKAN INI DI BAGIAN FUNGSI POHON ===
    public static function getFlatTree($organizationId = null)
    {
        $tree = static::getTree($organizationId);

        $flatten = function ($nodes, $level = 0) use (&$flatten) {
            $list = [];
            foreach ($nodes as $node) {
                // Tambahkan properti level ke setiap node
                $node['level'] = $level;
                $list[] = $node;

                if (!empty($node['children'])) {
                    $list = array_merge($list, $flatten($node['children'], $level + 1));
                }
            }
            return $list;
        };

        return collect($flatten($tree));
    }

    // === VALIDASI ANTI-SIKLUS ===

    public static function boot()
    {
        parent::boot();

        static::saving(function ($role) {
            // Aturan 1: Tidak boleh menunjuk ke dirinya sendiri
            if (!is_null($role->parent_id) && $role->parent_id === $role->id) {
                throw ValidationException::withMessages([
                    'parent_id' => ['Peran tidak boleh menjadi induk bagi dirinya sendiri.'],
                ]);
            }

            // Aturan 2: Tidak boleh beda organisasi
            if (!is_null($role->parent_id)) {
                $parent = Role::find($role->parent_id);
                if ($parent && $parent->organization_id !== $role->organization_id) {
                    throw ValidationException::withMessages([
                        'parent_id' => ['Peran induk harus berada di dalam organisasi yang sama.'],
                    ]);
                }
            }

            // Aturan 3: Cegah siklus tertutup
            if (!is_null($role->parent_id)) {
                $visited = collect([$role->id]);
                $check = Role::find($role->parent_id);

                while ($check) {
                    if ($visited->contains($check->id)) {
                        throw ValidationException::withMessages([
                            'parent_id' => ['Terdeteksi siklus pada hierarki peran. Operasi dibatalkan.'],
                        ]);
                    }
                    $visited->push($check->id);
                    $check = $check->parent;
                }
            }

            // Aturan 4: Peran sistem tidak boleh punya induk (opsional, sesuaikan kebutuhan)
            if ($role->is_system && !is_null($role->parent_id)) {
                throw ValidationException::withMessages([
                    'parent_id' => ['Peran sistem tidak boleh memiliki induk.'],
                ]);
            }

            // Ubah ke huruf besar & spasi jadi garis bawah
            if ($role->code) {
                $role->code = strtoupper(trim(preg_replace('/\s+/', '_', $role->code)));
            }

            // Opsional: kode tidak boleh diubah setelah dibuat
            if ($role->exists && $role->isDirty('code')) {
                throw ValidationException::withMessages([
                    'code' => ['Kode tidak dapat diubah setelah dibuat.'],
                ]);
            }
        });
    }

    // === BANTUAN TAMBAHAN ===

    // Cek apakah peran ini adalah turunan dari peran tertentu
    public function isDescendantOf($ancestorId)
    {
        return $this->getAllParentIds()->contains($ancestorId);
    }

    // Cek apakah peran ini adalah leluhur dari peran tertentu
    public function isAncestorOf($descendantId)
    {
        return $this->getAllChildrenIds()->contains($descendantId);
    }

    // Dapatkan daftar peran lengkap dengan struktur pohon
    public static function getTree($organizationId = null)
    {
        $query = static::orderBy('name');
        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        $roles = $query->get()->keyBy('id');

        return $roles->filter(fn($r) => is_null($r->parent_id))
            ->map(function ($root) use ($roles) {
                return static::buildTreeNode($root, $roles);
            });
    }

    protected static function buildTreeNode($node, $allRoles)
    {
        return [
            'id' => $node->id,
            'name' => $node->name,
            'name' => $node->name,
            'description' => $node->description,
            'is_system' => $node->is_system,
            'is_active' => $node->is_active,
            'children' => $allRoles
                ->where('parent_id', $node->id)
                ->map(fn($child) => static::buildTreeNode($child, $allRoles))
                ->values()
                ->toArray(),
        ];
    }


    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'role_user')
            ->withPivot(['is_default'])
            ->withTimestamps();
    }

    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'menu_role')
            ->withPivot(['is_view', 'is_create', 'is_edit', 'is_delete'])
            ->withTimestamps();
    }

    public function hasAction($id)
    {
        return $this->action_role()->whereHas('action', function ($query) use ($id) {
            $query->where('id', $id);
        })->where("is_allowed", 1)->count() > 0;
    }

    /**
     * A description of the entire PHP function.
     *
     * @param datatype $paramname description
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */

    public function actions()
    {
        return $this->hasMany(Action::class, 'action_role')->withTimestamps();;
    }
}
