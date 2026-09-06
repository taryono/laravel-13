<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalaryComponentsSeeder extends Seeder
{
    public function run()
    {
        // Ambil organization_id pertama (default = 1)
        $organizationId = 1;

        // Cek apakah organization dengan id 1 ada
        $orgExists = DB::table('organizations')->where('id', $organizationId)->exists();
        if (!$orgExists) {
            $this->command->warn('⚠️ Organization dengan ID ' . $organizationId . ' tidak ditemukan. Membuat organization default...');
            
            // Buat organization default jika belum ada
            DB::table('organizations')->insert([
                'id' => $organizationId,
                'name' => 'Yayasan Pendidikan Utama',
                'code' => 'YPU-001',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info('✅ Organization default berhasil dibuat.');
        }

        $components = [];

        // ================================================================
        // 1. KOMPONEN PENGHASILAN (EARNING) - Pendapatan Karyawan
        // ================================================================

        // 1.1 Gaji Pokok
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'SALARY_BASE',
            'name' => 'Gaji Pokok',
            'type' => 'earning',
            'calculation_type' => 'fixed',
            'calculation_base' => null,
            'default_value' => 0.0000,
            'is_taxable' => 1,
            'is_bpjs_base' => 1,
            'affects_net_salary' => 1,
            'is_recurring' => 1,
            'sort_order' => 1,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 1.2 Tunjangan Jabatan
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'ALLOWANCE_POSITION',
            'name' => 'Tunjangan Jabatan',
            'type' => 'earning',
            'calculation_type' => 'fixed',
            'calculation_base' => null,
            'default_value' => 0.0000,
            'is_taxable' => 1,
            'is_bpjs_base' => 1,
            'affects_net_salary' => 1,
            'is_recurring' => 1,
            'sort_order' => 2,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 1.3 Tunjangan Sertifikasi (khusus guru)
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'ALLOWANCE_CERTIFICATION',
            'name' => 'Tunjangan Sertifikasi',
            'type' => 'earning',
            'calculation_type' => 'fixed',
            'calculation_base' => null,
            'default_value' => 0.0000,
            'is_taxable' => 1,
            'is_bpjs_base' => 1,
            'affects_net_salary' => 1,
            'is_recurring' => 1,
            'sort_order' => 3,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 1.4 Tunjangan Keluarga
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'ALLOWANCE_FAMILY',
            'name' => 'Tunjangan Keluarga',
            'type' => 'earning',
            'calculation_type' => 'percentage',
            'calculation_base' => 'SALARY_BASE',
            'default_value' => 10.0000, // 10% dari gaji pokok
            'is_taxable' => 1,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 1,
            'is_recurring' => 1,
            'sort_order' => 4,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 1.5 Tunjangan Fungsional
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'ALLOWANCE_FUNCTIONAL',
            'name' => 'Tunjangan Fungsional',
            'type' => 'earning',
            'calculation_type' => 'fixed',
            'calculation_base' => null,
            'default_value' => 0.0000,
            'is_taxable' => 1,
            'is_bpjs_base' => 1,
            'affects_net_salary' => 1,
            'is_recurring' => 1,
            'sort_order' => 5,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 1.6 Tunjangan Transportasi
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'ALLOWANCE_TRANSPORT',
            'name' => 'Tunjangan Transportasi',
            'type' => 'earning',
            'calculation_type' => 'fixed',
            'calculation_base' => null,
            'default_value' => 500000.0000,
            'is_taxable' => 0,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 1,
            'is_recurring' => 1,
            'sort_order' => 6,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 1.7 Tunjangan Makan
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'ALLOWANCE_MEAL',
            'name' => 'Tunjangan Makan',
            'type' => 'earning',
            'calculation_type' => 'attendance',
            'calculation_base' => 'SALARY_BASE',
            'default_value' => 25000.0000, // Rp 25.000 per hari
            'is_taxable' => 0,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 1,
            'is_recurring' => 1,
            'sort_order' => 7,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 1.8 Tunjangan Hari Raya (THR)
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'ALLOWANCE_THR',
            'name' => 'Tunjangan Hari Raya (THR)',
            'type' => 'earning',
            'calculation_type' => 'formula',
            'calculation_base' => 'SALARY_BASE',
            'default_value' => 100.0000, // 1x gaji (100%)
            'is_taxable' => 1,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 1,
            'is_recurring' => 0, // Tidak setiap bulan
            'sort_order' => 8,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 1.9 Bonus Tahunan
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'BONUS_ANNUAL',
            'name' => 'Bonus Tahunan',
            'type' => 'earning',
            'calculation_type' => 'formula',
            'calculation_base' => 'SALARY_BASE',
            'default_value' => 50.0000, // 0.5x gaji (50%)
            'is_taxable' => 1,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 1,
            'is_recurring' => 0,
            'sort_order' => 9,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 1.10 Lembur
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'OVERTIME',
            'name' => 'Uang Lembur',
            'type' => 'earning',
            'calculation_type' => 'attendance',
            'calculation_base' => 'SALARY_BASE',
            'default_value' => 50000.0000, // Rp 50.000 per jam
            'is_taxable' => 1,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 1,
            'is_recurring' => 0,
            'sort_order' => 10,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 1.11 Insentif / Bonus Kinerja
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'BONUS_PERFORMANCE',
            'name' => 'Insentif Kinerja',
            'type' => 'earning',
            'calculation_type' => 'manual',
            'calculation_base' => null,
            'default_value' => 0.0000,
            'is_taxable' => 1,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 1,
            'is_recurring' => 0,
            'sort_order' => 11,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 1.12 Tunjangan Pendidikan (khusus guru/dosen)
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'ALLOWANCE_EDUCATION',
            'name' => 'Tunjangan Pendidikan',
            'type' => 'earning',
            'calculation_type' => 'fixed',
            'calculation_base' => null,
            'default_value' => 0.0000,
            'is_taxable' => 1,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 1,
            'is_recurring' => 1,
            'sort_order' => 12,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 1.13 Tunjangan Profesi (khusus dosen)
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'ALLOWANCE_PROFESSION',
            'name' => 'Tunjangan Profesi',
            'type' => 'earning',
            'calculation_type' => 'fixed',
            'calculation_base' => null,
            'default_value' => 0.0000,
            'is_taxable' => 1,
            'is_bpjs_base' => 1,
            'affects_net_salary' => 1,
            'is_recurring' => 1,
            'sort_order' => 13,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ================================================================
        // 2. POTONGAN (DEDUCTION)
        // ================================================================

        // 2.1 BPJS Kesehatan (dipotong dari gaji)
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'DEDUCTION_BPJS_HEALTH',
            'name' => 'Potongan BPJS Kesehatan',
            'type' => 'deduction',
            'calculation_type' => 'percentage',
            'calculation_base' => 'SALARY_BASE',
            'default_value' => 2.0000, // 2% dari gaji pokok
            'is_taxable' => 0,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 1,
            'is_recurring' => 1,
            'sort_order' => 20,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 2.2 BPJS Ketenagakerjaan (dipotong dari gaji)
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'DEDUCTION_BPJS_EMPLOYMENT',
            'name' => 'Potongan BPJS Ketenagakerjaan',
            'type' => 'deduction',
            'calculation_type' => 'percentage',
            'calculation_base' => 'SALARY_BASE',
            'default_value' => 1.0000, // 1% dari gaji pokok
            'is_taxable' => 0,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 1,
            'is_recurring' => 1,
            'sort_order' => 21,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 2.3 PPh 21 (Pajak Penghasilan)
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'DEDUCTION_TAX_PPH21',
            'name' => 'Potongan PPh Pasal 21',
            'type' => 'tax',
            'calculation_type' => 'tax',
            'calculation_base' => null,
            'default_value' => 0.0000,
            'is_taxable' => 0,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 1,
            'is_recurring' => 1,
            'sort_order' => 22,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 2.4 Potongan Koperasi / Simpanan Wajib
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'DEDUCTION_COOPERATIVE',
            'name' => 'Potongan Koperasi',
            'type' => 'deduction',
            'calculation_type' => 'fixed',
            'calculation_base' => null,
            'default_value' => 150000.0000,
            'is_taxable' => 0,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 1,
            'is_recurring' => 1,
            'sort_order' => 23,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 2.5 Potongan Seragam / ATK
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'DEDUCTION_UNIFORM',
            'name' => 'Potongan Seragam / ATK',
            'type' => 'deduction',
            'calculation_type' => 'fixed',
            'calculation_base' => null,
            'default_value' => 100000.0000,
            'is_taxable' => 0,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 1,
            'is_recurring' => 0,
            'sort_order' => 24,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 2.6 Potongan Dana Sosial
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'DEDUCTION_SOCIAL',
            'name' => 'Potongan Dana Sosial',
            'type' => 'deduction',
            'calculation_type' => 'fixed',
            'calculation_base' => null,
            'default_value' => 25000.0000,
            'is_taxable' => 0,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 1,
            'is_recurring' => 1,
            'sort_order' => 25,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 2.7 Potongan Cicilan Sertifikasi
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'DEDUCTION_CERTIFICATION',
            'name' => 'Cicilan Sertifikasi',
            'type' => 'deduction',
            'calculation_type' => 'loan',
            'calculation_base' => null,
            'default_value' => 300000.0000,
            'is_taxable' => 0,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 1,
            'is_recurring' => 0,
            'sort_order' => 26,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ================================================================
        // 3. KONTRIBUSI PERUSAHAAN (Company Contribution)
        // ================================================================

        // 3.1 BPJS Kesehatan - Perusahaan (4% - 5%)
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'COMPANY_BPJS_HEALTH',
            'name' => 'BPJS Kesehatan (Perusahaan)',
            'type' => 'company_contribution',
            'calculation_type' => 'percentage',
            'calculation_base' => 'SALARY_BASE',
            'default_value' => 4.0000, // 4% dari gaji pokok
            'is_taxable' => 0,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 0,
            'is_recurring' => 1,
            'sort_order' => 30,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 3.2 BPJS Ketenagakerjaan - Perusahaan (JHT 3.7%, JK 0.24%, JP 1%)
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'COMPANY_BPJS_EMPLOYMENT',
            'name' => 'BPJS Ketenagakerjaan (Perusahaan)',
            'type' => 'company_contribution',
            'calculation_type' => 'percentage',
            'calculation_base' => 'SALARY_BASE',
            'default_value' => 4.9400, // Total 3.7% + 0.24% + 1%
            'is_taxable' => 0,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 0,
            'is_recurring' => 1,
            'sort_order' => 31,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 3.3 BPJS Kesehatan - Detail komponen (opsional, untuk breakdown)
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'COMPANY_BPJS_HEALTH_JHT',
            'name' => 'BPJS JHT (Perusahaan)',
            'type' => 'company_contribution',
            'calculation_type' => 'percentage',
            'calculation_base' => 'SALARY_BASE',
            'default_value' => 3.7000, // 3.7% dari gaji pokok
            'is_taxable' => 0,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 0,
            'is_recurring' => 1,
            'sort_order' => 32,
            'is_active' => 0, // Tidak aktif (digabung di atas)
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 3.4 BPJS Kesehatan - Detail komponen (opsional)
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'COMPANY_BPJS_HEALTH_JK',
            'name' => 'BPJS JK (Perusahaan)',
            'type' => 'company_contribution',
            'calculation_type' => 'percentage',
            'calculation_base' => 'SALARY_BASE',
            'default_value' => 0.2400, // 0.24% dari gaji pokok
            'is_taxable' => 0,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 0,
            'is_recurring' => 1,
            'sort_order' => 33,
            'is_active' => 0, // Tidak aktif (digabung di atas)
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 3.5 BPJS Kesehatan - Detail komponen
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'COMPANY_BPJS_HEALTH_JP',
            'name' => 'BPJS JP (Perusahaan)',
            'type' => 'company_contribution',
            'calculation_type' => 'percentage',
            'calculation_base' => 'SALARY_BASE',
            'default_value' => 1.0000, // 1% dari gaji pokok
            'is_taxable' => 0,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 0,
            'is_recurring' => 1,
            'sort_order' => 34,
            'is_active' => 0, // Tidak aktif (digabung di atas)
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 3.6 Tunjangan Hari Raya (THR) - Perusahaan
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'COMPANY_THR',
            'name' => 'Tunjangan Hari Raya (THR)',
            'type' => 'company_contribution',
            'calculation_type' => 'formula',
            'calculation_base' => 'SALARY_BASE',
            'default_value' => 100.0000, // 1x gaji
            'is_taxable' => 0,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 0,
            'is_recurring' => 0,
            'sort_order' => 35,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 3.7 Bonus Tahunan - Perusahaan
        $components[] = [
            'organization_id' => $organizationId,
            'code' => 'COMPANY_BONUS_ANNUAL',
            'name' => 'Bonus Tahunan (Perusahaan)',
            'type' => 'company_contribution',
            'calculation_type' => 'formula',
            'calculation_base' => 'SALARY_BASE',
            'default_value' => 50.0000, // 0.5x gaji
            'is_taxable' => 0,
            'is_bpjs_base' => 0,
            'affects_net_salary' => 0,
            'is_recurring' => 0,
            'sort_order' => 36,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ================================================================
        // INSERT DATA
        // ================================================================

        // Cek existing data
        $existingCodes = DB::table('salary_components')
            ->where('organization_id', $organizationId)
            ->pluck('code')
            ->toArray();

        $newData = [];
        $updatedData = [];

        foreach ($components as $component) {
            if (!in_array($component['code'], $existingCodes)) {
                $newData[] = $component;
            } else {
                // Update jika ada perubahan
                unset($component['created_at']);
                $updatedData[] = $component;
            }
        }

        // Insert data baru
        if (!empty($newData)) {
            DB::table('salary_components')->insert($newData);
            $this->command->info('✅ ' . count($newData) . ' salary component(s) berhasil ditambahkan.');
        }

        // Update data yang sudah ada
        if (!empty($updatedData)) {
            foreach ($updatedData as $data) {
                DB::table('salary_components')
                    ->where('organization_id', $organizationId)
                    ->where('code', $data['code'])
                    ->update($data);
            }
            $this->command->info('🔄 ' . count($updatedData) . ' salary component(s) diperbarui.');
        }

        if (empty($newData) && empty($updatedData)) {
            $this->command->info('ℹ️ Tidak ada perubahan pada salary_components.');
        }

        // ================================================================
        // STATISTIK
        // ================================================================
        $totalEarning = DB::table('salary_components')
            ->where('organization_id', $organizationId)
            ->where('type', 'earning')
            ->where('is_active', 1)
            ->count();

        $totalDeduction = DB::table('salary_components')
            ->where('organization_id', $organizationId)
            ->where('type', 'deduction')
            ->where('is_active', 1)
            ->count();

        $totalCompany = DB::table('salary_components')
            ->where('organization_id', $organizationId)
            ->where('type', 'company_contribution')
            ->where('is_active', 1)
            ->count();

        $this->command->info('📊 Statistik Salary Components:');
        $this->command->info('   - Earning Components (Penghasilan): ' . $totalEarning);
        $this->command->info('   - Deduction Components (Potongan): ' . $totalDeduction);
        $this->command->info('   - Company Contributions (Kontribusi Perusahaan): ' . $totalCompany);
        $this->command->info('   - Total Active: ' . ($totalEarning + $totalDeduction + $totalCompany));
    }
}