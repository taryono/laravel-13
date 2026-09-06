<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeductionCategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            // ==========================================
            // 1. POTONGAN WAJIB (Mandatory)
            // ==========================================
            [
                'code' => 'BPJS-KES',
                'name' => 'BPJS Kesehatan',
                'category_type' => 'mandatory',
                'deduction_group' => 'insurance',
                'calculation_type' => 'percentage',
                'default_amount' => null,
                'default_percentage' => 2.00,
                'min_amount' => 50000,
                'max_amount' => 300000,
                'is_based_on_salary' => 1,
                'is_taxable' => 0,
                'tax_code' => null,
                'is_active' => 1,
                'is_system' => 1,
                'description' => 'Iuran BPJS Kesehatan (2% dari gaji)',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'BPJS-TK',
                'name' => 'BPJS Ketenagakerjaan',
                'category_type' => 'mandatory',
                'deduction_group' => 'insurance',
                'calculation_type' => 'percentage',
                'default_amount' => null,
                'default_percentage' => 1.00,
                'min_amount' => 30000,
                'max_amount' => 200000,
                'is_based_on_salary' => 1,
                'is_taxable' => 0,
                'tax_code' => null,
                'is_active' => 1,
                'is_system' => 1,
                'description' => 'Iuran BPJS Ketenagakerjaan (JHT, JK, JP)',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'PPh21',
                'name' => 'PPh Pasal 21',
                'category_type' => 'mandatory',
                'deduction_group' => 'tax',
                'calculation_type' => 'sliding',
                'default_amount' => null,
                'default_percentage' => null,
                'min_amount' => 0,
                'max_amount' => null,
                'is_based_on_salary' => 1,
                'is_taxable' => 1,
                'tax_code' => 'PPh21',
                'is_active' => 1,
                'is_system' => 1,
                'description' => 'Pajak Penghasilan Pasal 21 (dipotong setiap bulan)',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ==========================================
            // 2. POTONGAN SUKARELA (Voluntary)
            // ==========================================
            [
                'code' => 'KOPERASI',
                'name' => 'Simpanan Koperasi Wajib',
                'category_type' => 'voluntary',
                'deduction_group' => 'saving',
                'calculation_type' => 'fixed',
                'default_amount' => 150000,
                'default_percentage' => null,
                'min_amount' => 50000,
                'max_amount' => 500000,
                'is_based_on_salary' => 0,
                'is_taxable' => 0,
                'tax_code' => null,
                'is_active' => 1,
                'is_system' => 0,
                'description' => 'Simpanan wajib koperasi karyawan per bulan',
                'sort_order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SOSIAL',
                'name' => 'Dana Sosial Karyawan',
                'category_type' => 'voluntary',
                'deduction_group' => 'other',
                'calculation_type' => 'fixed',
                'default_amount' => 25000,
                'default_percentage' => null,
                'min_amount' => 10000,
                'max_amount' => 100000,
                'is_based_on_salary' => 0,
                'is_taxable' => 0,
                'tax_code' => null,
                'is_active' => 1,
                'is_system' => 0,
                'description' => 'Potongan dana sosial untuk kegiatan karyawan',
                'sort_order' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SERAGAM',
                'name' => 'Seragam / ATK',
                'category_type' => 'voluntary',
                'deduction_group' => 'other',
                'calculation_type' => 'fixed',
                'default_amount' => 100000,
                'default_percentage' => null,
                'min_amount' => 50000,
                'max_amount' => 300000,
                'is_based_on_salary' => 0,
                'is_taxable' => 0,
                'tax_code' => null,
                'is_active' => 1,
                'is_system' => 0,
                'description' => 'Cicilan seragam atau alat tulis kantor',
                'sort_order' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SERTIFIKASI',
                'name' => 'Cicilan Sertifikasi Guru',
                'category_type' => 'voluntary',
                'deduction_group' => 'loan',
                'calculation_type' => 'fixed',
                'default_amount' => 300000,
                'default_percentage' => null,
                'min_amount' => 100000,
                'max_amount' => 1000000,
                'is_based_on_salary' => 0,
                'is_taxable' => 0,
                'tax_code' => null,
                'is_active' => 1,
                'is_system' => 0,
                'description' => 'Cicilan biaya sertifikasi guru (10 kali angsuran)',
                'sort_order' => 13,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ==========================================
            // 3. POTONGAN LAINNYA
            // ==========================================
            [
                'code' => 'DENDA',
                'name' => 'Denda Keterlambatan',
                'category_type' => 'other',
                'deduction_group' => 'penalty',
                'calculation_type' => 'fixed',
                'default_amount' => 75000,
                'default_percentage' => null,
                'min_amount' => 50000,
                'max_amount' => 200000,
                'is_based_on_salary' => 0,
                'is_taxable' => 0,
                'tax_code' => null,
                'is_active' => 1,
                'is_system' => 0,
                'description' => 'Denda karena keterlambatan masuk kerja',
                'sort_order' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'REIMBURSE',
                'name' => 'Potongan Kelebihan Bayar',
                'category_type' => 'other',
                'deduction_group' => 'other',
                'calculation_type' => 'fixed',
                'default_amount' => 200000,
                'default_percentage' => null,
                'min_amount' => 50000,
                'max_amount' => 500000,
                'is_based_on_salary' => 0,
                'is_taxable' => 0,
                'tax_code' => null,
                'is_active' => 1,
                'is_system' => 0,
                'description' => 'Potongan kelebihan pembayaran gaji bulan sebelumnya',
                'sort_order' => 21,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LAINNYA',
                'name' => 'Potongan Lainnya',
                'category_type' => 'other',
                'deduction_group' => 'other',
                'calculation_type' => 'fixed',
                'default_amount' => 100000,
                'default_percentage' => null,
                'min_amount' => 10000,
                'max_amount' => 1000000,
                'is_based_on_salary' => 0,
                'is_taxable' => 0,
                'tax_code' => null,
                'is_active' => 1,
                'is_system' => 0,
                'description' => 'Potongan khusus sesuai kebijakan yayasan',
                'sort_order' => 99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Cek existing data
        $existingCodes = DB::table('deduction_categories')
            ->pluck('code')
            ->toArray();

        $newData = [];
        foreach ($categories as $category) {
            if (!in_array($category['code'], $existingCodes)) {
                $newData[] = $category;
            }
        }

        if (!empty($newData)) {
            DB::table('deduction_categories')->insert($newData);
            $this->command->info('✅ ' . count($newData) . ' deduction categories berhasil ditambahkan.');
        } else {
            $this->command->info('ℹ️ Tidak ada deduction categories baru yang ditambahkan.');
        }
    }
}