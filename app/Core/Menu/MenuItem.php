<?php
// app/Core/Menu/MenuItem.php 
namespace App\Core\Menu;

class MenuItem
{
    public int $id;
    public ?int $parent_id = null;
    public ?int $module_id = null;
    public ?string $text = null; // ✅ Tambah sesuai tabel
    public ?string $action = null; // ✅ Tambah sesuai tabel
    public ?string $permission = null; // ✅ Tambah jika pakai kolom `can`
    public ?string $icon = null;
    public string $iconType = 'material';
    public ?string $url = null;
    public ?string $route = null;
    public ?string $flyoutTitle = null;
    public bool $isTopnav = false;
    public bool $active = false;
    public bool $open = false;
    public ?int $sort_order = null;
     public ?string $role = null; // ✅ Tambah baris ini
    public array $children = [];

    public static function fromModel(\Models\Menu $menu): self
    {
        $item = new self();
        $item->id = $menu->id;
        $item->parent_id = $menu->parent_id ?: null;
        $item->module_id = $menu->module_id;
        $item->text = ucwords(strtolower(str_replace('_', ' ', $menu->text)));
        $item->icon = $menu->icon;
        $item->iconType = $menu->icon_type ?? 'material';
        $item->url = $menu->url;
        $item->action = $menu->action;
        $item->permission = $menu->can; // kolom `can` jadi izin
        $item->flyoutTitle = $menu->flyout_title ?? $item->text;
        $item->isTopnav = (int) $menu->menu_type_id === 1;
        $item->sort_order = $menu->sequence; // ambil dari kolom sequence
        $item->role = $menu->role; // ✅ Ambil nilai nama role dari tabel
        return $item;
    }

    public function hasChildren(): bool
    {
        return count($this->children) > 0;
    }
}