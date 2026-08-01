<?php
// app/Providers/AuthServiceProvider.php
namespace App\Providers;

use Models\Action;
use Models\Menu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Pemetaan Policy ke Model
     */
    protected $policies = [
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    /**
     * Daftar layanan aplikasi.
     */
    public function register(): void
    {
        //
    }

    /**
     * Jalankan layanan saat aplikasi dimuat.
     */
    public function boot(): void
    {
        // =============================================
        // 1. Pengecualian untuk Super Admin
        // =============================================
        Gate::before(function ($user, $ability) {
            // Pastikan method isSuperUser() ada di Model User kamu
            if (method_exists($user, 'isSuperUser') && $user->isSuperUser()) {
                return true;
            }
        });

        // Gate khusus contoh (bisa disesuaikan/dihapus)
        Gate::define('manage-menu', function ($user) {
            return $user->isSuperUser() || $user->can('menu-manage');
        });

        // Jalankan hanya jika tabel sudah tersedia di database
        try {
            if (!Schema::hasTable('actions') || !Schema::hasTable('menus')) {
                return;
            }

            // =============================================
            // 2. Daftarkan Gate untuk SEMUA AKSI (Permissions)
            // =============================================
            // Menggunakan cache 1 jam agar tidak query berulang
            $allActions = Cache::remember('acl-all-actions', 3600, function () {
                return Action::where('is_active', 1)
                    ->pluck('name', 'id');
            });

            foreach ($allActions as $actionId => $actionName) {
                // Di dalam Gate::define untuk Action:
                Gate::define($actionName, function ($user) use ($actionName) {
                    // Super admin sudah ditangani di Gate::before
                    
                    // Ambil role yang sedang aktif saja
                    $activeRoleId = session('user_auth.active_role_id');

                    // Atau jika ingin gabungan semua role:
                    // $roleIds = session('all_role_ids', []);

                    return \DB::table('action_role')
                        ->where('action_id', function ($query) use ($actionName) {
                            $query->select('id')->from('actions')->where('name', $actionName)->where('is_active', 1);
                        })
                        ->where('role_id', $activeRoleId) // ganti sesuai pilihanmu
                        ->exists();
                });
            }

            // =============================================
            // 3. Daftarkan Gate untuk MENU (jika dibutuhkan)
            // =============================================
            // Biasanya pengecekan menu dilakukan di MenuService,
            // tapi jika butuh pengecekan lewat Gate, bisa pakai ini:
            $allMenus = Cache::remember('acl-all-menus', 3600, function () {
                return Menu::where('is_active', 1)
                    ->pluck('slug', 'id');
            });

            foreach ($allMenus as $menuId => $menuSlug) {
                $gateName = "menu-{$menuSlug}";

                Gate::define($gateName, function ($user) use ($menuId) {
                    $roleIds = $user->roles->pluck('id');

                    return \DB::table('menu_role')
                        ->where('menu_id', $menuId)
                        ->whereIn('role_id', $roleIds)
                        ->exists();
                });
            }

        } catch (\Exception $e) {
            // Hindari dump di produksi, simpan ke log saja
            logger()->error('Error memuat ACL: ' . $e->getMessage());
        }
    }
}