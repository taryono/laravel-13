<?php
// app/Core/Menu/MenuTreeBuilder.php
namespace App\Core\Menu;

use Illuminate\Support\Collection;

class MenuTreeBuilder
{
    private ?int $rootParentId = null;
    private array $customParentMappings = [];

    public function setRootParentId(?int $rootParentId): self
    {
        $this->rootParentId = $rootParentId;
        return $this;
    }

    public function setCustomParentMappings(array $mappings): self
    {
        $this->customParentMappings = $mappings;
        return $this;
    }

    public function build(Collection $items, ?string $currentUrl = null): Collection
    {
        // Terapkan custom   mappings
        if (!empty($this->customParentMappings)) {
            $items = $items->map(function ($item) {
                if (isset($this->customParentMappings[$item->id])) {
                    $item->parent_id = $this->customParentMappings[$item->id];
                }
                return $item;
            });
        }

        // Jika rootParentId di-set, hanya ambil subtree tersebut
        if ($this->rootParentId !== null) {
            $items = $this->extractSubtree($items, $this->rootParentId);
        }

        $grouped = $items->groupBy('parent_id');
        return $this->buildLevel($grouped, null, $currentUrl);
    }

    private function buildLevel(Collection $grouped, ?int $parentId, ?string $currentUrl): Collection
    {

        return ($grouped->get($parentId, collect()))
            ->values()
            ->map(function ($item) use ($grouped, $currentUrl) {
                // Biarkan sebagai Collection, jangan diubah ke array
                $item->children = $this->buildLevel($grouped, $item->id, $currentUrl)->all();
                $currentPath = $currentUrl !== null ? ltrim((string) parse_url($currentUrl, PHP_URL_PATH), '/') : '';

                // Bandingkan dengan item->url
                // Tambahkan properti active dan open
                $item->active = $item->url !== null && $item->url === $currentPath;
                $item->open = $item->active || collect($item->children)->contains(function ($child) {
                    return $child->active ?? false || $child->open ?? false;
                });
                return $item;
            });
    }

    private function extractSubtree(Collection $items, int $rootId): Collection
    {

        $root = $items->firstWhere('id', $rootId);
        if (!$root) {
            return collect();
        }

        $root->parent_id = null;
        $descendantIds = $this->getDescendantIds($items, $rootId);
        $descendants = $items->filter(fn($item) => in_array($item->id, $descendantIds));

        return collect([$root])->merge($descendants);
    }

    private function getDescendantIds(Collection $items, int $parentId): array
    {
        $ids = [];
        $children = $items->where('parent_id', $parentId);

        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getDescendantIds($items, $child->id));
        }

        return $ids;
    }

    /**
     * Filter menu tree untuk menghapus grup kosong
     */
    public function filterByPermission(Collection $items): Collection
    {
        return $items
            ->map(function ($item) {
                if (!empty($item->children)) {
                    $item->children = $this->filterByPermission(collect($item->children));
                }
                return $item;
            })
            ->filter(function ($item) {
                // ✅ Tampilkan jika: punya URL ATAU punya anak yang tampil
                $hasChildren = !empty($item->children) && collect($item->children)->isNotEmpty();
                $hasUrl = $item->url !== null && $item->url !== '';

                return $hasUrl || $hasChildren;
            })
            ->values();
    }
}
