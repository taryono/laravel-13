<?php
// app/Core/Menu/MenuRepository.php
namespace App\Core\Menu;

use Models\Menu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MenuRepository
{
    public function all(): Collection
    {
        $query = Menu::query()
            ->where('is_active', 1)
            ->whereNull('deleted_at');
        // ✅ Hapus syarat is_publish = 1 karena datanya NULL

        if (Auth::check()) {
            $user = Auth::user();
            $orgId = session('active_organization_id') ?? $user->active_organization_id;

            if (!$user->isSuperUser()) {
                $roleIds = DB::table('role_user')
                    ->where('user_id', $user->id)
                    ->where('organization_id', $orgId)
                    ->pluck('role_id');

                $allowedMenuIds = DB::table('menu_role')
                    ->whereIn('role_id', $roleIds)
                    ->where('is_view', 1)
                    ->pluck('menu_id');

                $query->whereIn('id', $allowedMenuIds);
            }
        }

        return $query
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn(Menu $m) => MenuItem::fromModel($m));
    }


    /**
     * Ambil SEMUA menu tanpa filter apapun — khusus keperluan admin management.
     * Admin harus bisa lihat & edit menu yang tidak aktif/tidak publish sekalipun.
     */
    public function allForAdmin(): Collection
    {
        return Menu::with('roles')
            ->orderBy('sort_order')
            ->orderBy('sequence')
            ->orderBy('name')
            ->get()
            ->map(fn(Menu $m) => MenuItem::fromModel($m));
    }

    public function findRaw(int $id): Menu
    {
        return Menu::findOrFail($id);
    }
}
