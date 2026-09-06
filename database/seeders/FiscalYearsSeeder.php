<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FiscalYearsSeeder extends Seeder
{
    public function run()
    {
        // Data fiscal years untuk Yayasan Pendidikan
        // Biasanya tahun buku mengikuti tahun akademik (Juli - Juni)
        // Tapi bisa juga mengikuti tahun kalender (Jan - Des)

        $fiscalYears = [
            // ==========================================
            // TAHUN BUKU MENGAJAR (Juli - Juni)
            // ==========================================
            [
                'year' => 2020,
                'start_date' => '2020-07-01',
                'end_date' => '2021-06-30',
                'is_closed' => 1,
                'closed_at' => '2021-07-15 23:59:59',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'year' => 2021,
                'start_date' => '2021-07-01',
                'end_date' => '2022-06-30',
                'is_closed' => 1,
                'closed_at' => '2022-07-15 23:59:59',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'year' => 2022,
                'start_date' => '2022-07-01',
                'end_date' => '2023-06-30',
                'is_closed' => 1,
                'closed_at' => '2023-07-15 23:59:59',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'year' => 2023,
                'start_date' => '2023-07-01',
                'end_date' => '2024-06-30',
                'is_closed' => 1,
                'closed_at' => '2024-07-15 23:59:59',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'year' => 2024,
                'start_date' => '2024-07-01',
                'end_date' => '2025-06-30',
                'is_closed' => 0, // ✅ Tahun berjalan (belum ditutup)
                'closed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'year' => 2025,
                'start_date' => '2025-07-01',
                'end_date' => '2026-06-30',
                'is_closed' => 0,
                'closed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'year' => 2026,
                'start_date' => '2026-07-01',
                'end_date' => '2027-06-30',
                'is_closed' => 0,
                'closed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // ================================================================
        // INSERT DENGAN CEK DUPLIKAT
        // ================================================================
        
        // Cek existing data
        $existingYears = DB::table('fiscal_years')
            ->pluck('year')
            ->toArray();

        $newData = [];
        $updatedData = [];

        foreach ($fiscalYears as $fiscalYear) {
            if (!in_array($fiscalYear['year'], $existingYears)) {
                $newData[] = $fiscalYear;
            } else {
                // Update jika ada perubahan
                unset($fiscalYear['created_at']);
                $updatedData[] = $fiscalYear;
            }
        }

        // Insert data baru
        if (!empty($newData)) {
            DB::table('fiscal_years')->insert($newData);
            $this->command->info('✅ ' . count($newData) . ' fiscal year(s) berhasil ditambahkan.');
        }

        // Update data yang sudah ada
        if (!empty($updatedData)) {
            foreach ($updatedData as $data) {
                DB::table('fiscal_years')
                    ->where('year', $data['year'])
                    ->update($data);
            }
            $this->command->info('🔄 ' . count($updatedData) . ' fiscal year(s) diperbarui.');
        }

        if (empty($newData) && empty($updatedData)) {
            $this->command->info('ℹ️ Tidak ada perubahan pada fiscal_years.');
        }
    }
}