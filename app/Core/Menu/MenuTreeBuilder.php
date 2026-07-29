<?php
// app/Core/Menu/MenuTreeBuilder.php
namespace App\Core\Menu;

use Illuminate\Support\Collection;

class MenuTreeBuilder
{
    public function build(Collection $items, ?string $currentUrl = null): Collection
    {
        $grouped = $items->groupBy('parent_id');
        return $this->buildLevel($grouped, null, $currentUrl);
    }

    private function buildLevel(Collection $grouped, ?int $parentId, ?string $currentUrl): Collection
    {
        return ($grouped->get($parentId, collect()))
            ->values()
            ->map(function (MenuItem $item) use ($grouped, $currentUrl) {
                $item->children = $this->buildLevel($grouped, $item->id, $currentUrl)->all();
                $item->active = $item->url !== null && $item->url === $currentUrl;
                $item->open = $item->active || collect($item->children)
                    ->contains(fn (MenuItem $c) => $c->active || $c->open);
                return $item;
            });
    }

    /**
     * Filter menu tree berdasarkan module_id yang diizinkan.
     * - Superuser lolos semua.
     * - Item tanpa module_id (mis. hanya grup/parent) tidak diblok di sini,
     *   nanti otomatis kebuang di filter akhir kalau tidak punya url & children.
     */
    public function filterByPermissionNew(Collection $items, array $allowedModuleIds, bool $isSuperUser): Collection
    {
        return $items
            ->filter(fn (MenuItem $i) => $isSuperUser
                || $i->module_id === null
                || in_array($i->module_id, $allowedModuleIds, true))
            ->map(function (MenuItem $item) use ($allowedModuleIds, $isSuperUser) {
                if (!empty($item->children)) {
                    $item->children = $this->filterByPermission(
                        collect($item->children), $allowedModuleIds, $isSuperUser
                    )->values()->all();
                }
                return $item;
            })
            ->filter(fn (MenuItem $i) => $i->url !== null || $i->hasChildren())
            ->values();
    }

    public function filterByPermission(Collection $items, array $allowedModuleIds, bool $isSuperUser): Collection
    {
        // Karena sudah disaring lewat kolom 'role' di Repository,
        // kita hanya perlu memastikan struktur pohon tetap rapi
        return $items->map(function (MenuItem $item) use ($allowedModuleIds, $isSuperUser) {
            if (!empty($item->children)) {
                $item->children = $this->filterByPermission(
                    collect($item->children), $allowedModuleIds, $isSuperUser
                )->values()->all();
            }
            return $item;
        })
        ->filter(fn (MenuItem $i) => $i->url !== null || $i->hasChildren())
        ->values();
    }
}   