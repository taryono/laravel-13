<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuCategorySeeder extends Seeder
{
    public function run(): void
    {
        $organizationIds = DB::table('organizations')->pluck('id');

        if ($organizationIds->isEmpty()) {
            $this->command?->warn('Tabel organizations kosong, MenuCategorySeeder dilewati.');
            return;
        }

        // Nama icon mengacu ke Lucide (ganti sesuai icon library di frontend)
        $categories = [
            ['code' => 'keuangan',      'name' => 'Keuangan',      'description' => 'SPP & Bayar',     'icon' => 'wallet',          'color' => 'emerald'],
            ['code' => 'data-akademik', 'name' => 'Data Akademik', 'description' => 'Kurikulum',       'icon' => 'graduation-cap',  'color' => 'blue'],
            ['code' => 'fasilitas',     'name' => 'Fasilitas',     'description' => 'Inventaris Lab',  'icon' => 'building-2',      'color' => 'amber'],
            ['code' => 'antar-jemput',  'name' => 'Antar Jemput',  'description' => 'Rute Bus',        'icon' => 'bus',             'color' => 'sky'],
            ['code' => 'cbt',           'name' => 'CBT',           'description' => 'Ujian & Bank',    'icon' => 'laptop',          'color' => 'purple'],
            ['code' => 'ppdb',          'name' => 'PPDB Baru',     'description' => 'Pendaftaran',     'icon' => 'user-check',      'color' => 'rose'],
            ['code' => 'absensi',       'name' => 'Absensi',       'description' => 'Presensi QR',     'icon' => 'clipboard-check', 'color' => 'indigo'],
            ['code' => 'karyawan',      'name' => 'Karyawan',      'description' => 'SDM & Guru',      'icon' => 'id-card',         'color' => 'cyan'],
            ['code' => 'akuntansi',     'name' => 'Akuntansi',     'description' => 'Buku Besar',      'icon' => 'receipt-text',    'color' => 'teal'],
            // Sub-label dibuat generik; tahun anggaran sebaiknya dinamis dari pengaturan
            ['code' => 'budgeting',     'name' => 'Budgeting',     'description' => 'RAPBS',           'icon' => 'chart-pie',       'color' => 'orange'],
        ];

        $now = now();
        $rows = [];

        foreach ($organizationIds as $organizationId) {
            foreach ($categories as $index => $category) {
                $rows[] = [
                    'organization_id' => $organizationId,
                    'parent_id'       => null,
                    'code'            => $category['code'],
                    'name'            => $category['name'],
                    'description'     => $category['description'],
                    'icon'            => $category['icon'],
                    'color'           => $category['color'],
                    'sort_order'      => ($index + 1) * 10,
                    'is_system'       => true,
                    'is_active'       => true,
                    'show_to_trial'   => false, // atur sesuai kebutuhan paket trial
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];
            }
        }

        // Aman dijalankan berulang (unik: organization_id + code)
        DB::table('menu_categories')->upsert(
            $rows,
            ['organization_id', 'code'],
            ['name', 'description', 'icon', 'color', 'sort_order', 'is_system', 'is_active', 'updated_at']
        );
    }
}