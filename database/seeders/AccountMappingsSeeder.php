<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountMappingsSeeder extends Seeder
{
    public function run()
    {
        // Ambil ID akun berdasarkan kode
        $accounts = DB::table('accounts')->pluck('id', 'code');

        $mappings = [
            // === MAPPING PAYROLL (Gaji) ===
            [
                'source_type' => 'payroll_component',
                'source_code' => 'SALARY_TK',
                'debit_account_id' => $accounts['5-1100'] ?? null, // Gaji Guru TK
                'credit_account_id' => $accounts['2-1200'] ?? null, // Utang Gaji
                'description' => 'Mapping gaji guru TK',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'source_type' => 'payroll_component',
                'source_code' => 'SALARY_SD',
                'debit_account_id' => $accounts['5-1101'] ?? null,
                'credit_account_id' => $accounts['2-1200'] ?? null,
                'description' => 'Mapping gaji guru SD',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'source_type' => 'payroll_component',
                'source_code' => 'SALARY_SMP',
                'debit_account_id' => $accounts['5-1102'] ?? null,
                'credit_account_id' => $accounts['2-1200'] ?? null,
                'description' => 'Mapping gaji guru SMP',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'source_type' => 'payroll_component',
                'source_code' => 'SALARY_SMA',
                'debit_account_id' => $accounts['5-1103'] ?? null,
                'credit_account_id' => $accounts['2-1200'] ?? null,
                'description' => 'Mapping gaji guru SMA',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'source_type' => 'payroll_component',
                'source_code' => 'SALARY_UNIV',
                'debit_account_id' => $accounts['5-1104'] ?? null,
                'credit_account_id' => $accounts['2-1200'] ?? null,
                'description' => 'Mapping gaji dosen universitas',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'source_type' => 'payroll_component',
                'source_code' => 'STAFF_ADMIN',
                'debit_account_id' => $accounts['5-2100'] ?? null,
                'credit_account_id' => $accounts['2-1200'] ?? null,
                'description' => 'Mapping gaji staf administrasi',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // === MAPPING SPP / INVOICE ===
            [
                'source_type' => 'sales',
                'source_code' => 'SPP_TK',
                'debit_account_id' => $accounts['1-1300'] ?? null, // Piutang SPP TK
                'credit_account_id' => $accounts['4-1100'] ?? null, // Pendapatan SPP TK
                'description' => 'Mapping penjualan SPP TK',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'source_type' => 'sales',
                'source_code' => 'SPP_SD',
                'debit_account_id' => $accounts['1-1301'] ?? null,
                'credit_account_id' => $accounts['4-1101'] ?? null,
                'description' => 'Mapping penjualan SPP SD',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'source_type' => 'sales',
                'source_code' => 'SPP_SMP',
                'debit_account_id' => $accounts['1-1302'] ?? null,
                'credit_account_id' => $accounts['4-1102'] ?? null,
                'description' => 'Mapping penjualan SPP SMP',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'source_type' => 'sales',
                'source_code' => 'SPP_SMA',
                'debit_account_id' => $accounts['1-1303'] ?? null,
                'credit_account_id' => $accounts['4-1103'] ?? null,
                'description' => 'Mapping penjualan SPP SMA',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'source_type' => 'sales',
                'source_code' => 'UKT_UNIV',
                'debit_account_id' => $accounts['1-1304'] ?? null,
                'credit_account_id' => $accounts['4-1104'] ?? null,
                'description' => 'Mapping penjualan UKT Universitas',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // === MAPPING PEMBELIAN ===
            [
                'source_type' => 'purchase',
                'source_code' => 'ATK_OFFICE',
                'debit_account_id' => $accounts['5-2300'] ?? null, // Beban ATK
                'credit_account_id' => $accounts['2-1100'] ?? null, // Utang Usaha
                'description' => 'Mapping pembelian ATK kantor',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'source_type' => 'purchase',
                'source_code' => 'PRACTICUM',
                'debit_account_id' => $accounts['5-1200'] ?? null, // Beban Praktikum
                'credit_account_id' => $accounts['2-1100'] ?? null,
                'description' => 'Mapping pembelian bahan praktikum',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // === MAPPING BEBAN LAINNYA ===
            [
                'source_type' => 'expense',
                'source_code' => 'ELECTRICITY',
                'debit_account_id' => $accounts['5-2200'] ?? null, // Beban Listrik
                'credit_account_id' => $accounts['2-1100'] ?? null,
                'description' => 'Mapping tagihan listrik & air',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'source_type' => 'expense',
                'source_code' => 'MAINTENANCE',
                'debit_account_id' => $accounts['5-2400'] ?? null, // Beban Perawatan
                'credit_account_id' => $accounts['2-1100'] ?? null,
                'description' => 'Mapping biaya perawatan gedung',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('account_mappings')->insert($mappings);
    }
}