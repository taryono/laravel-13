<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalaryStructuresSeeder extends Seeder
{
    public function run()
    {
        $organizationId = 1;

        // Cek apakah organization ada
        $orgExists = DB::table('organizations')->where('id', $organizationId)->exists();
        if (!$orgExists) {
            $this->command->warn('⚠️ Organization ID ' . $organizationId . ' tidak ditemukan.');
            return;
        }

        $structures = [
            // ==========================================
            // 1. STRUKTUR GURU
            // ==========================================
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_GURU_TK',
                'name' => 'Struktur Gaji Guru TK',
                'description' => 'Struktur gaji untuk posisi Guru Taman Kanak-Kanak',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_GURU_SD',
                'name' => 'Struktur Gaji Guru SD',
                'description' => 'Struktur gaji untuk posisi Guru Sekolah Dasar',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_GURU_SMP',
                'name' => 'Struktur Gaji Guru SMP',
                'description' => 'Struktur gaji untuk posisi Guru Sekolah Menengah Pertama',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_GURU_SMA',
                'name' => 'Struktur Gaji Guru SMA',
                'description' => 'Struktur gaji untuk posisi Guru Sekolah Menengah Atas',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ==========================================
            // 2. STRUKTUR GURU DENGAN SERTIFIKASI
            // ==========================================
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_GURU_SERTIFIKASI_TK',
                'name' => 'Struktur Gaji Guru TK Sertifikasi',
                'description' => 'Struktur gaji untuk Guru TK yang sudah bersertifikasi',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_GURU_SERTIFIKASI_SD',
                'name' => 'Struktur Gaji Guru SD Sertifikasi',
                'description' => 'Struktur gaji untuk Guru SD yang sudah bersertifikasi',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_GURU_SERTIFIKASI_SMP',
                'name' => 'Struktur Gaji Guru SMP Sertifikasi',
                'description' => 'Struktur gaji untuk Guru SMP yang sudah bersertifikasi',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_GURU_SERTIFIKASI_SMA',
                'name' => 'Struktur Gaji Guru SMA Sertifikasi',
                'description' => 'Struktur gaji untuk Guru SMA yang sudah bersertifikasi',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ==========================================
            // 3. STRUKTUR DOSEN
            // ==========================================
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_DOSEN_TETAP',
                'name' => 'Struktur Gaji Dosen Tetap',
                'description' => 'Struktur gaji untuk posisi Dosen Tetap Universitas',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_DOSEN_TETAP_SERTIFIKASI',
                'name' => 'Struktur Gaji Dosen Tetap Sertifikasi',
                'description' => 'Struktur gaji untuk Dosen Tetap yang sudah bersertifikasi',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_DOSEN_TIDAK_TETAP',
                'name' => 'Struktur Gaji Dosen Tidak Tetap',
                'description' => 'Struktur gaji untuk Dosen Tidak Tetap (honor per SKS)',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ==========================================
            // 4. STRUKTUR STAF ADMINISTRASI
            // ==========================================
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_STAFF_ADMIN',
                'name' => 'Struktur Gaji Staf Administrasi',
                'description' => 'Struktur gaji untuk staf administrasi dan tata usaha',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_STAFF_ADMIN_SENIOR',
                'name' => 'Struktur Gaji Staf Administrasi Senior',
                'description' => 'Struktur gaji untuk staf administrasi senior / kepala TU',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ==========================================
            // 5. STRUKTUR TENAGA KEPENDIDIKAN LAINNYA
            // ==========================================
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_LABORAN',
                'name' => 'Struktur Gaji Laboran',
                'description' => 'Struktur gaji untuk laboran / teknisi laboratorium',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_PUSTAKAWAN',
                'name' => 'Struktur Gaji Pustakawan',
                'description' => 'Struktur gaji untuk pustakawan / staf perpustakaan',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_KEAMANAN',
                'name' => 'Struktur Gaji Satpam & Keamanan',
                'description' => 'Struktur gaji untuk satpam dan petugas keamanan',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_KEBERSIHAN',
                'name' => 'Struktur Gaji Petugas Kebersihan',
                'description' => 'Struktur gaji untuk petugas kebersihan',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ==========================================
            // 6. STRUKTUR MANAJEMEN / KEPEMIMPINAN
            // ==========================================
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_KEPALA_SEKOLAH',
                'name' => 'Struktur Gaji Kepala Sekolah',
                'description' => 'Struktur gaji untuk posisi Kepala Sekolah (TK/SD/SMP/SMA)',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_REKTOR',
                'name' => 'Struktur Gaji Rektor Universitas',
                'description' => 'Struktur gaji untuk posisi Rektor Universitas',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_WAKIL_REKTOR',
                'name' => 'Struktur Gaji Wakil Rektor',
                'description' => 'Struktur gaji untuk posisi Wakil Rektor',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $organizationId,
                'code' => 'STRUCT_DEKAN',
                'name' => 'Struktur Gaji Dekan',
                'description' => 'Struktur gaji untuk posisi Dekan Fakultas',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Cek existing data
        $existingCodes = DB::table('salary_structures')
            ->where('organization_id', $organizationId)
            ->pluck('code')
            ->toArray();

        $newData = [];
        foreach ($structures as $structure) {
            if (!in_array($structure['code'], $existingCodes)) {
                $newData[] = $structure;
            }
        }

        if (!empty($newData)) {
            DB::table('salary_structures')->insert($newData);
            $this->command->info('✅ ' . count($newData) . ' salary structure(s) berhasil ditambahkan.');
        } else {
            $this->command->info('ℹ️ Tidak ada salary structure baru yang ditambahkan.');
        }
    }
}