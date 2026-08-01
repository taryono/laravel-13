<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Models\Menu;
use Models\Role;

/**
 * Seeder ini menyelesaikan masalah "ayam-telur": halaman Menu Management
 * butuh baris di tabel `menus` supaya muncul di sidebar, tapi baris itu
 * sendiri belum pernah dibuat lewat halaman tersebut.
 *
 * SESUAIKAN:
 * - $superAdminRoleName di bawah ini dengan `name` role kamu yang sebenarnya
 *   di tabel `roles` (cek dulu: SELECT name FROM roles;)
 */
class MenuManagementMenuSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRoleName = 'super-admin'; // <-- ganti sesuai roles.name kamu

        $role = Role::where('name', $superAdminRoleName)->first();

        if (!$role) {
            $this->command->error("Role '{$superAdminRoleName}' tidak ditemukan. Cek dulu isi tabel roles.");
            return;
        }

        $menu = Menu::updateOrCreate(
            ['slug' => 'menu-management'],
            [
                'parent_id'    => null,
                'title'        => 'Manajemen Menu',
                'flyout_title' => null,
                'icon'         => 'list_alt',
                'icon_type'    => 'material',
                'route'        => 'menu_management.index',
                'url'          => 'menu_management',
                'type'         => 'sidebar',
                'sort_order'   => (Menu::max('sort_order') ?? 0) + 1,
                'is_active'    => 1,
            ]
        );

        DB::table('menu_role')->updateOrInsert(
            ['menu_id' => $menu->id, 'role_id' => $role->id],
            [
                'is_view'    => 1,
                'is_create'  => 1,
                'is_edit'    => 1,
                'is_delete'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info("Menu 'Manajemen Menu' berhasil dibuat & di-assign ke role '{$superAdminRoleName}'.");
    }
}