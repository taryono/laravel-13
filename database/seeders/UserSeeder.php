<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // === 1. Buat Admin Utama (tidak terduplikasi) ===
        User::updateOrCreate(
            ['email' => 'admin@contoh.id'],
            [
                'name'                      => 'Administrator Sistem',
                'email_verified_at'        => now(),
                'password'                  => Hash::make('admin123'),
                'current_organization_id'   => 1,
                'isCheckingRole'            => 1,
                'created_by'                => null,
                'updated_by'                => null,
            ]
        );

        // === 2. Buat User Tambahan Tetap ===
        User::updateOrCreate(
            ['email' => 'staff@contoh.id'],
            [
                'name'                      => 'Staff Organisasi',
                'email_verified_at'        => now(),
                'password'                  => Hash::make('staff123'),
                'current_organization_id'   => 1,
                'isCheckingRole'            => 0,
                'created_by'                => User::where('email', 'admin@contoh.id')->value('id'),
                'updated_by'                => null,
            ]
        );

        // === 3. Buat 10 User Acak untuk Pengujian ===
        User::factory()->count(10)->create();
    }
}
