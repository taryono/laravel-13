<?php 
namespace Models;
use Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Menu extends BasedModel
{
     protected $fillable = [
        'parent_id',
        'title',
        'flyout_title', // lihat catatan migration: kolom tambahan, bukan bagian dump asli
        'slug',
        'icon',
        'icon_type',
        'route',
        'url',
        'type',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
 

    public function parent()
    {
        return $this->belongsTo(Menu::class,'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class,'parent_id')
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
}