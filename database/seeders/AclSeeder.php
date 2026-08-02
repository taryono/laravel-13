<?php

namespace Database\Seeders;

use Models\User;
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
        // 1. BUAT PERAN / ROLE
        // =============================================
        $roleSuper = Role::firstOrCreate(
            ['name' => 'super-admin'],
            [
                'display_name' => 'Super Administrator',
                'description' => 'Memiliki akses penuh ke seluruh sistem',
            ]
        );

        $roleAdmin = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrator',
                'description' => 'Mengelola konten dan pengguna biasa',
            ]
        );

        $roleUser = Role::firstOrCreate(
            ['name' => 'user'],
            [
                'display_name' => 'Pengguna',
                'description' => 'Pengguna biasa',
            ]
        );

        // =============================================
        // 2. BUAT MENU
        // =============================================
        $menuDashboard = Menu::firstOrCreate(
            ['slug' => 'dashboard'],
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
            ['slug' => 'master'],
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
            ['slug' => 'user'],
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
            ['slug' => 'roles'],
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

        // =============================================
        // 3. BUAT AKSI / IZIN PER MENU
        // =============================================
        Action::firstOrCreate(
            ['name' => 'user-view'],
            [
                'menu_id' => $menuUser->id,
                'display_name' => 'Lihat Daftar Pengguna',
                'is_allow_view' => 1,
                'is_allow_create' => 0,
                'is_allow_edit' => 0,
                'is_allow_delete' => 0,
                'is_active' => 1,
            ]
        );

        Action::firstOrCreate(
            ['name' => 'user-manage'],
            [
                'menu_id' => $menuUser->id,
                'display_name' => 'Kelola Pengguna',
                'is_allow_view' => 1,
                'is_allow_create' => 1,
                'is_allow_edit' => 1,
                'is_allow_delete' => 1,
                'is_active' => 1,
            ]
        );

        Action::firstOrCreate(
            ['name' => 'roles-manage'],
            [
                'menu_id' => $menuRole->id,
                'display_name' => 'Kelola Peran',
                'is_allow_view' => 1,
                'is_allow_create' => 1,
                'is_allow_edit' => 1,
                'is_allow_delete' => 1,
                'is_active' => 1,
            ]
        );

        // =============================================
        // 4. BERIKAN AKSES MENU KE ROLE
        // =============================================
        if (!$menuDashboard->roles->contains($roleSuper->id)) $menuDashboard->roles()->attach($roleSuper->id);
        if (!$menuDashboard->roles->contains($roleAdmin->id))  $menuDashboard->roles()->attach($roleAdmin->id);

        if (!$menuMaster->roles->contains($roleSuper->id)) $menuMaster->roles()->attach($roleSuper->id);

        if (!$menuUser->roles->contains($roleSuper->id)) $menuUser->roles()->attach($roleSuper->id);
        if (!$menuUser->roles->contains($roleAdmin->id))  $menuUser->roles()->attach($roleAdmin->id);

        if (!$menuRole->roles->contains($roleSuper->id)) $menuRole->roles()->attach($roleSuper->id);

        // =============================================
        // 5. BERIKAN AKSI / IZIN KE ROLE
        // =============================================
        $allActions = Action::all();
        foreach ($allActions as $act) {
            if (!$act->roles->contains($roleSuper->id)) $act->roles()->attach($roleSuper->id);
        }

        $adminActions = Action::whereIn('name', ['user-view', 'user-manage'])->get();
        foreach ($adminActions as $act) {
            if (!$act->roles->contains($roleAdmin->id)) $act->roles()->attach($roleAdmin->id);
        }

        // =============================================
        // 6. BUAT USER & BERIKAN PERAN
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'admin@contoh.id'],
            [
                'name' => 'Administrator Utama',
                'password' => Hash::make('rahasia123'),
            ]
        );

        // =============================================
        // Hapus dulu relasi lama (jika ada) lalu tambahkan ulang dengan benar
        $user->roles()->detach();

        // Pakai attach satu per satu agar pasti masuk
        $user->roles()->attach($roleSuper->id, ['is_default' => 1]);
        $user->roles()->attach($roleAdmin->id, ['is_default' => 0]);
    }
}