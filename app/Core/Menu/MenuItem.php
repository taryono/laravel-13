<?php
// app/Core/Menu/MenuItem.php
namespace App\Core\Menu;

use Models\Menu;

class MenuItem
{
    public int $id;
    public ?int $parent_id = null;
    public ?string $text = null;
    public ?string $title = null;
    public ?string $name = null;
    public ?string $action = null;
    public ?string $permission = null;
    public ?string $can = null;
    public ?string $icon = null;
    public string $iconType = 'material';
    public ?string $url = null;
    public ?string $route = null;
    public ?string $flyoutTitle = null;
    public ?string $type = 'sidebar';
    public bool $isTopnav = false;
    public bool $active = false;
    public bool $open = false;
    public ?int $sort_order = null;
    public ?int $sequence = null;
    public bool $is_active = true;
    public ?string $role = null;
    public array $roles = [];
    public ?string $id_text = null;
    public ?string $en_text = null;
    public array $children = [];

    public static function fromModel(Menu $menu): self
    {
        $item = new self();
        $item->id = $menu->id;
        $item->parent_id = $menu->parent_id ? (int) $menu->parent_id : null;
        $displayTitle = $menu->title ?: ($menu->name ?: '');
        $item->title = $displayTitle;
        $item->text = $menu->text ? ucwords(strtolower(str_replace('_', ' ', $menu->text))) : $displayTitle;
        $item->name = $menu->name;
        $item->icon = $menu->icon;
        $item->iconType = $menu->icon_type ?? 'material';
        $item->url = $menu->url;
        $item->route = $menu->route;
        $item->type = $menu->type ?? 'sidebar';
        $item->action = $menu->action ?? null;
        $item->can = $menu->can ?? null;
        $item->flyoutTitle = $menu->flyout_title ?? $item->title;
        $item->isTopnav = ($menu->type === 'topbar') || ((int) ($menu->menu_type_id ?? 0) === 1);
        $item->sort_order = $menu->sort_order ?? $menu->sequence ?? 0;
        $item->sequence = $menu->sequence ?? $menu->sort_order ?? 0;
        $item->is_active = (bool) ($menu->is_active ?? true);
        $item->role = $menu->role ?? null;
        $item->id_text = $menu->id_text;
        $item->en_text = $menu->en_text;

        if ($menu->relationLoaded('roles')) {
            $item->roles = $menu->roles->toArray();
        }

        return $item;
    }

    public function hasChildren(): bool
    {
        return count($this->children) > 0;
    }
}
