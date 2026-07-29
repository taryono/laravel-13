<?php
// app/Core/Menu/MenuService.php
namespace App\Core\Menu;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    public function __construct(
        private MenuRepository $repository = new MenuRepository(),
        private MenuTreeBuilder $treeBuilder = new MenuTreeBuilder(),
        private MenuPermissionResolver $permissionResolver = new MenuPermissionResolver(),
    ) {}

    /**
     * @return Collection<MenuItem>
     */
    public function getTree(?string $currentUrl = null): Collection
    {
        $isSuper = auth()->check() && isSuperUser();
        $roleIds = session()->get('role_ids', []);

        $allowedModuleIds = $isSuper
            ? []
            : $this->getAllowedModuleIds($roleIds);

        // Raw items & base tree structure di-cache (tidak tergantung currentUrl)
        $items = $this->getCachedItems($roleIds, $isSuper);

        $tree = $this->treeBuilder->build($items, $currentUrl);

        return $this->treeBuilder->filterByPermission($tree, $allowedModuleIds, $isSuper);
    }

    /**
     * @return array{menu: Collection<MenuItem>, topnavMenu: Collection<MenuItem>}
     */
    public function getSplitMenu(?string $currentUrl = null): array
    {
        $filtered = $this->getTree($currentUrl);

        return [
            'menu' => $filtered->reject(fn (MenuItem $i) => $i->isTopnav)->values(),
            'topnavMenu' => $filtered->filter(fn (MenuItem $i) => $i->isTopnav)->values(),
        ];
    }

    private function getAllowedModuleIds(array $roleIds): array
    {
        $key = 'menu.allowed_modules.' . md5(implode(',', $roleIds));

        return Cache::remember($key, now()->addMinutes(30), fn () =>
            $this->permissionResolver->allowedModuleIds($roleIds)
        );
    }

    private function getCachedItems(array $roleIds, bool $isSuper): Collection
    {
        $roleKey = $isSuper ? 'superuser' : (auth()->check() ? md5(implode(',', $roleIds)) : 'guest');
        $key = "menu.items.{$roleKey}";

        return Cache::remember($key, now()->addMinutes(30), fn () =>
            $this->repository->all()
        );
    }
}