<?php

namespace Database\Seeders;

use App\Models\User;
use Models\Role;
use Models\Menu;
use Models\Action;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AclSeeder extends Seeder
{
    public function run(): void
    {
        // =============================================
        // 0. AMBIL ORGANIZATION DEFAULT
        // =============================================
        $organization = \Models\Organization::first();
        
        if (!$organization) {
            // Buat organization default jika belum ada
            $organization = \Models\Organization::create([
                'code' => 'DEFAULT',
                'name' => 'Default Organization',
                'organization_type' => 'company',
                'currency_code' => 'IDR',
                'timezone' => 'Asia/Jakarta',
                'is_active' => 1,
            ]);
        }

        // =============================================
        // 1. BUAT PERAN / ROLE
        // =============================================
        $roleSuper = Role::firstOrCreate(
            [
                'name' => 'super-admin',
                'organization_id' => $organization->id,
            ],
            [
                'display_name' => 'Super Administrator',
                'description' => 'Memiliki akses penuh ke seluruh sistem',
                'is_system' => 1,
            ]
        );

        $roleAdmin = Role::firstOrCreate(
            [
                'name' => 'admin',
                'organization_id' => $organization->id,
            ],
            [
                'display_name' => 'Administrator',
                'description' => 'Mengelola konten dan pengguna biasa',
                'is_system' => 1,
            ]
        );

        $roleUser = Role::firstOrCreate(
            [
                'name' => 'user',
                'organization_id' => $organization->id,
            ],
            [
                'display_name' => 'Pengguna',
                'description' => 'Pengguna biasa',
                'is_system' => 1,
            ]
        );

        // =============================================
        // 2. BUAT MENU
        // =============================================
        $menuDashboard = Menu::firstOrCreate(
            [
                'slug' => 'dashboard',
                'organization_id' => $organization->id,
            ],
            [
                'parent_id' => null,
                'title' => 'Dasbor',
                'icon' => 'fas fa-tachometer-alt',
                'route' => 'dashboard',
                'type' => 'sidebar',
                'sort_order' => 1,
                'is_active' => 1,
            ]
        );

        $menuMaster = Menu::firstOrCreate(
            [
                'slug' => 'master',
                'organization_id' => $organization->id,
            ],
            [
                'parent_id' => null,
                'title' => 'Data Utama',
                'icon' => 'fas fa-database',
                'route' => null,
                'type' => 'sidebar',
                'sort_order' => 2,
                'is_active' => 1,
            ]
        );

        $menuUser = Menu::firstOrCreate(
            [
                'slug' => 'user',
                'organization_id' => $organization->id,
            ],
            [
                'parent_id' => $menuMaster->id,
                'title' => 'Pengguna',
                'icon' => 'fas fa-users',
                'route' => 'user.index',
                'type' => 'sidebar',
                'sort_order' => 1,
                'is_active' => 1,
            ]
        );

        $menuRole = Menu::firstOrCreate(
            [
                'slug' => 'roles',
                'organization_id' => $organization->id,
            ],
            [
                'parent_id' => $menuMaster->id,
                'title' => 'Peran & Izin',
                'icon' => 'fas fa-user-shield',
                'route' => 'roles.index',
                'type' => 'sidebar',
                'sort_order' => 2,
                'is_active' => 1,
            ]
        );

        // Menu tambahan untuk user profile
        $menuProfile = Menu::firstOrCreate(
            [
                'slug' => 'profile',
                'organization_id' => $organization->id,
            ],
            [
                'parent_id' => null,
                'title' => 'Profil',
                'icon' => 'fas fa-user-circle',
                'route' => 'profile.edit',
                'type' => 'topbar',
                'sort_order' => 1,
                'is_active' => 1,
            ]
        );

        // =============================================
        // 3. BUAT AKSI / IZIN PER MENU
        // =============================================
        Action::firstOrCreate(
            [
                'name' => 'user-view',
                'organization_id' => $organization->id,
            ],
            [
                'menu_id' => $menuUser->id,
                'display_name' => 'Lihat Daftar Pengguna',
                'description' => 'Melihat daftar pengguna',
                'is_active' => 1,
            ]
        );

        Action::firstOrCreate(
            [
                'name' => 'user-manage',
                'organization_id' => $organization->id,
            ],
            [
                'menu_id' => $menuUser->id,
                'display_name' => 'Kelola Pengguna',
                'description' => 'Membuat, mengedit, dan menghapus pengguna',
                'is_active' => 1,
            ]
        );

        Action::firstOrCreate(
            [
                'name' => 'roles-manage',
                'organization_id' => $organization->id,
            ],
            [
                'menu_id' => $menuRole->id,
                'display_name' => 'Kelola Peran',
                'description' => 'Mengelola peran dan izin',
                'is_active' => 1,
            ]
        );

        Action::firstOrCreate(
            [
                'name' => 'profile-view',
                'organization_id' => $organization->id,
            ],
            [
                'menu_id' => $menuProfile->id,
                'display_name' => 'Lihat Profil',
                'description' => 'Melihat profil pengguna',
                'is_active' => 1,
            ]
        );

        Action::firstOrCreate(
            [
                'name' => 'profile-edit',
                'organization_id' => $organization->id,
            ],
            [
                'menu_id' => $menuProfile->id,
                'display_name' => 'Edit Profil',
                'description' => 'Mengedit profil pengguna',
                'is_active' => 1,
            ]
        );

        // =============================================
        // 4. BERIKAN AKSES MENU KE ROLE
        // =============================================
        // Dashboard - Super & Admin
        if (!$menuDashboard->roles()->where('role_id', $roleSuper->id)->exists()) {
            $menuDashboard->roles()->attach($roleSuper->id);
        }
        if (!$menuDashboard->roles()->where('role_id', $roleAdmin->id)->exists()) {
            $menuDashboard->roles()->attach($roleAdmin->id);
        }

        // Master - Super only
        if (!$menuMaster->roles()->where('role_id', $roleSuper->id)->exists()) {
            $menuMaster->roles()->attach($roleSuper->id);
        }

        // User - Super & Admin
        if (!$menuUser->roles()->where('role_id', $roleSuper->id)->exists()) {
            $menuUser->roles()->attach($roleSuper->id);
        }
        if (!$menuUser->roles()->where('role_id', $roleAdmin->id)->exists()) {
            $menuUser->roles()->attach($roleAdmin->id);
        }

        // Role - Super only
        if (!$menuRole->roles()->where('role_id', $roleSuper->id)->exists()) {
            $menuRole->roles()->attach($roleSuper->id);
        }

        // Profile - All roles
        if (!$menuProfile->roles()->where('role_id', $roleSuper->id)->exists()) {
            $menuProfile->roles()->attach($roleSuper->id);
        }
        if (!$menuProfile->roles()->where('role_id', $roleAdmin->id)->exists()) {
            $menuProfile->roles()->attach($roleAdmin->id);
        }
        if (!$menuProfile->roles()->where('role_id', $roleUser->id)->exists()) {
            $menuProfile->roles()->attach($roleUser->id);
        }

        // =============================================
        // 5. BERIKAN AKSI / IZIN KE ROLE
        // =============================================
        // Super Admin dapat semua aksi
        $allActions = Action::where('organization_id', $organization->id)->get();
        foreach ($allActions as $act) {
            if (!$act->roles()->where('role_id', $roleSuper->id)->exists()) {
                $act->roles()->attach($roleSuper->id);
            }
        }

        // Admin hanya aksi user-view dan user-manage
        $adminActions = Action::whereIn('name', ['user-view', 'user-manage'])
            ->where('organization_id', $organization->id)
            ->get();
        foreach ($adminActions as $act) {
            if (!$act->roles()->where('role_id', $roleAdmin->id)->exists()) {
                $act->roles()->attach($roleAdmin->id);
            }
        }

        // User hanya aksi profile-view dan profile-edit
        $userActions = Action::whereIn('name', ['profile-view', 'profile-edit'])
            ->where('organization_id', $organization->id)
            ->get();
        foreach ($userActions as $act) {
            if (!$act->roles()->where('role_id', $roleUser->id)->exists()) {
                $act->roles()->attach($roleUser->id);
            }
        }

        // =============================================
        // 6. BUAT USER & BERIKAN PERAN
        // =============================================
        $user = User::firstOrCreate(
            ['email' => 'admin@contoh.id'],
            [
                'name' => 'Administrator Utama',
                'password' => Hash::make('rahasia123'),
                'email_verified_at' => now(),
            ]
        );

        // Hapus relasi lama
        $user->roles()->detach();

        // Tambahkan relasi ke organization
        if (!$user->organizations()->where('organization_id', $organization->id)->exists()) {
            $user->organizations()->attach($organization->id, [
                'is_default' => 1,
                'is_active' => 1,
            ]);
        }

        // Berikan role ke user dengan organization context
        $user->roles()->attach($roleSuper->id, [
            'organization_id' => $organization->id,
            'is_default' => 1,
        ]);
        $user->roles()->attach($roleAdmin->id, [
            'organization_id' => $organization->id,
            'is_default' => 0,
        ]);

        // =============================================
        // 7. (OPSIONAL) BUAT USER BIASA
        // =============================================
        $regularUser = User::firstOrCreate(
            ['email' => 'user@contoh.id'],
            [
                'name' => 'Pengguna Biasa',
                'password' => Hash::make('rahasia123'),
                'email_verified_at' => now(),
            ]
        );

        if (!$regularUser->organizations()->where('organization_id', $organization->id)->exists()) {
            $regularUser->organizations()->attach($organization->id, [
                'is_default' => 1,
                'is_active' => 1,
            ]);
        }

        $regularUser->roles()->detach();
        $regularUser->roles()->attach($roleUser->id, [
            'organization_id' => $organization->id,
            'is_default' => 1,
        ]);

        // =============================================
        // 8. OUTPUT INFORMASI
        // =============================================
        $this->command->info('✓ Seeder ACL berhasil dijalankan!');
        $this->command->info('✓ Organization ID: ' . $organization->id);
        $this->command->info('✓ Super Admin: admin@contoh.id / rahasia123');
        $this->command->info('✓ User Biasa: user@contoh.id / rahasia123');
    }
}