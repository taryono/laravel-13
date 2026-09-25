<?php

namespace Models;

use Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends BasedModel
{
    protected $fillable = [
        'organization_id',
        'module_id',
        'parent_id',
        'title',
        'flyout_title',
        'slug',
        'icon',
        'icon_type',
        'route',
        'url',
        'type',
        'sort_order',
        'sequence',
        'is_active',
        'is_publish',
        'name',
        'id_text',
        'en_text',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }


    public function organization()
    {
        return $this->belongsTo(Organization::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->orderBy('sequence');
    }

    public function getRoleByName()
    {
        if (empty($this->role)) return null;
        return Role::where('name', $this->role)->first();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'menu_role')
            ->withPivot(['is_view', 'is_create', 'is_edit', 'is_delete'])->withTimestamps();
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    /**
     * "Action utama" untuk quick-check di tabel index admin.
     * Konvensi: nama action dasar = slug menu itu sendiri
     * (mis. slug "master-produk" -> action "master-produk",
     * lalu turunannya "master-produk-create", "master-produk-edit", dst).
     * Ini bukan kolom fisik, murni konvensi penamaan.
     */
    public function getPrimaryActionAttribute(): ?Action
    {
        return $this->actions->firstWhere('name', $this->slug)
            ?? $this->actions->first();
    }

    // 🔥 Scope untuk menu dengan parent
    public function scopeHasParent($query)
    {
        return $query->whereNotNull('parent_id');
    }

    // 🔥 Scope untuk menu root (tanpa parent)
    public function scopeIsRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    // 🔥 Scope untuk sorting hierarchical
    public function scopeOrderHierarchical($query, $direction = 'asc')
    {
        return $query->leftJoin('menus as parent_menu', 'menus.parent_id', '=', 'parent_menu.id')
            ->orderByRaw("
                CASE 
                    WHEN menus.parent_id IS NULL THEN 0 
                    ELSE 1 
                END {$direction},
                parent_menu.name {$direction},
                menus.name {$direction}
            ");
    }

    /**
     * Get the role assignments for this menu
     */
    public function menuRoles()
    {
        return $this->hasMany(MenuRole::class, 'menu_id');
    }
}
