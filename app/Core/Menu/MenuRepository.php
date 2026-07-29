<?php
// app/Core/Menu/MenuRepository.php
namespace App\Core\Menu;

use Models\Menu;
use Illuminate\Support\Collection;

class MenuRepository
{
    public function all(): Collection
    {
        $query = Menu::query()
            ->where('is_publish', 1)
            ->where('is_active', 1);

        // ✅ Terapkan filter hanya jika user SUDAH login dan BUKAN Superuser
        if (auth()->check()) {
            
            $userRoleNames = auth()->user()->roles->pluck('name')->toArray();
    
            // Tampilkan menu yang role-nya cocok ATAU role-nya kosong (umum)
            $query->where(function ($q) use ($userRoleNames) {
                $q->whereIn('role', $userRoleNames)
                    ->orWhereNull('role');
            });
        }

        return $query
            ->orderBy('sequence')
            ->orderBy('name')
            ->get()
            ->map(fn (Menu $m) => MenuItem::fromModel($m));
    }

    /**
     * Ambil SEMUA menu tanpa filter apapun — khusus keperluan admin management.
     * Admin harus bisa lihat & edit menu yang tidak aktif/tidak publish sekalipun.
     */
    public function allForAdmin(): Collection
    {
        return Menu::query()
            ->orderBy('sequence')
            ->orderBy('name')
            ->get()
            ->map(fn (Menu $m) => MenuItem::fromModel($m));
    }

    public function findRaw(int $id): Menu
    {
        return Menu::findOrFail($id);
    }
}