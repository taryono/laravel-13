<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountMappingSeeder extends Seeder
{
    public function run()
    {
        // Ambil ID akun berdasarkan kode
        $accounts = DB::table('accounts')->pluck('id', 'code');

        $mappings = [];

        // ================================================================
        // 1. MAPPING PAYROLL COMPONENT (Komponen Gaji)
        // ================================================================

        // 1.1 Gaji Guru & Dosen per Jenjang
        $payrollMappings = [
            [
                'source_code' => 'SALARY_TK',
                'description' => 'Gaji pokok guru TK',
                'debit_code' => '5-1100',  // Beban Gaji Guru TK
                'credit_code' => '2-1200', // Utang Gaji & Tunjangan
            ],
            [
                'source_code' => 'SALARY_SD',
                'description' => 'Gaji pokok guru SD',
                'debit_code' => '5-1101',  // Beban Gaji Guru SD
                'credit_code' => '2-1200', // Utang Gaji & Tunjangan
            ],
            [
                'source_code' => 'SALARY_SMP',
                'description' => 'Gaji pokok guru SMP',
                'debit_code' => '5-1102',  // Beban Gaji Guru SMP
                'credit_code' => '2-1200', // Utang Gaji & Tunjangan
            ],
            [
                'source_code' => 'SALARY_SMA',
                'description' => 'Gaji pokok guru SMA',
                'debit_code' => '5-1103',  // Beban Gaji Guru SMA
                'credit_code' => '2-1200', // Utang Gaji & Tunjangan
            ],
            [
                'source_code' => 'SALARY_UNIV',
                'description' => 'Gaji pokok dosen universitas',
                'debit_code' => '5-1104',  // Beban Gaji Dosen Universitas
                'credit_code' => '2-1200', // Utang Gaji & Tunjangan
            ],
            [
                'source_code' => 'SALARY_STAFF',
                'description' => 'Gaji staf administrasi & TU',
                'debit_code' => '5-2100',  // Beban Gaji Staf Administrasi
                'credit_code' => '2-1200', // Utang Gaji & Tunjangan
            ],
        ];

        foreach ($payrollMappings as $mapping) {
            $mappings[] = [
                'source_type' => 'payroll_component',
                'source_code' => $mapping['source_code'],
                'debit_account_id' => $accounts[$mapping['debit_code']] ?? null,
                'credit_account_id' => $accounts[$mapping['credit_code']] ?? null,
                'description' => $mapping['description'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 1.2 Tunjangan & Komponen Gaji Lainnya
        $tunjanganMappings = [
            [
                'source_code' => 'ALLOWANCE_CERTIFICATION',
                'description' => 'Tunjangan sertifikasi guru',
                'debit_code' => '5-1100',  // Beban Gaji Guru TK
                'credit_code' => '2-1200', // Utang Gaji & Tunjangan
            ],
            [
                'source_code' => 'ALLOWANCE_FAMILY',
                'description' => 'Tunjangan keluarga',
                'debit_code' => '5-2100',  // Beban Gaji Staf Administrasi
                'credit_code' => '2-1200', // Utang Gaji & Tunjangan
            ],
            [
                'source_code' => 'ALLOWANCE_FUNCTIONAL',
                'description' => 'Tunjangan fungsional',
                'debit_code' => '5-2100',  // Beban Gaji Staf Administrasi
                'credit_code' => '2-1200', // Utang Gaji & Tunjangan
            ],
            [
                'source_code' => 'BONUS_THR',
                'description' => 'THR (Tunjangan Hari Raya)',
                'debit_code' => '5-2100',  // Beban Gaji Staf Administrasi
                'credit_code' => '2-1200', // Utang Gaji & Tunjangan
            ],
            [
                'source_code' => 'BONUS_ANNUAL',
                'description' => 'Bonus tahunan',
                'debit_code' => '5-2100',  // Beban Gaji Staf Administrasi
                'credit_code' => '2-1200', // Utang Gaji & Tunjangan
            ],
        ];

        foreach ($tunjanganMappings as $mapping) {
            $mappings[] = [
                'source_type' => 'payroll_component',
                'source_code' => $mapping['source_code'],
                'debit_account_id' => $accounts[$mapping['debit_code']] ?? null,
                'credit_account_id' => $accounts[$mapping['credit_code']] ?? null,
                'description' => $mapping['description'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ================================================================
        // 2. MAPPING SALES (Penjualan / Pendapatan)
        // ================================================================

        // 2.1 Pendapatan SPP per Jenjang
        $salesMappings = [
            [
                'source_code' => 'SPP_TK',
                'description' => 'Pendapatan SPP TK',
                'debit_code' => '1-1300',  // Piutang SPP TK
                'credit_code' => '4-1100', // Pendapatan SPP TK
            ],
            [
                'source_code' => 'SPP_SD',
                'description' => 'Pendapatan SPP SD',
                'debit_code' => '1-1301',  // Piutang SPP SD
                'credit_code' => '4-1101', // Pendapatan SPP SD
            ],
            [
                'source_code' => 'SPP_SMP',
                'description' => 'Pendapatan SPP SMP',
                'debit_code' => '1-1302',  // Piutang SPP SMP
                'credit_code' => '4-1102', // Pendapatan SPP SMP
            ],
            [
                'source_code' => 'SPP_SMA',
                'description' => 'Pendapatan SPP SMA',
                'debit_code' => '1-1303',  // Piutang SPP SMA
                'credit_code' => '4-1103', // Pendapatan SPP SMA
            ],
            [
                'source_code' => 'UKT_UNIV',
                'description' => 'Pendapatan UKT Universitas',
                'debit_code' => '1-1304',  // Piutang UKT Universitas
                'credit_code' => '4-1104', // Pendapatan UKT Universitas
            ],
        ];

        foreach ($salesMappings as $mapping) {
            $mappings[] = [
                'source_type' => 'sales',
                'source_code' => $mapping['source_code'],
                'debit_account_id' => $accounts[$mapping['debit_code']] ?? null,
                'credit_account_id' => $accounts[$mapping['credit_code']] ?? null,
                'description' => $mapping['description'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 2.2 Pendapatan Non-SPP
        $nonSppMappings = [
            [
                'source_code' => 'FEE_DEVELOPMENT',
                'description' => 'Pendapatan uang gedung / pengembangan',
                'debit_code' => '1-1300',  // Piutang SPP TK
                'credit_code' => '4-2100', // Pendapatan Uang Gedung
            ],
            [
                'source_code' => 'FEE_REGISTRATION_TK',
                'description' => 'Pendapatan pendaftaran siswa baru TK',
                'debit_code' => '1-1300',  // Piutang SPP TK
                'credit_code' => '4-2200', // Pendapatan Pendaftaran
            ],
            [
                'source_code' => 'FEE_REGISTRATION_SD',
                'description' => 'Pendapatan pendaftaran siswa baru SD',
                'debit_code' => '1-1301',  // Piutang SPP SD
                'credit_code' => '4-2200', // Pendapatan Pendaftaran
            ],
            [
                'source_code' => 'FEE_REGISTRATION_SMP',
                'description' => 'Pendapatan pendaftaran siswa baru SMP',
                'debit_code' => '1-1302',  // Piutang SPP SMP
                'credit_code' => '4-2200', // Pendapatan Pendaftaran
            ],
            [
                'source_code' => 'FEE_REGISTRATION_SMA',
                'description' => 'Pendapatan pendaftaran siswa baru SMA',
                'debit_code' => '1-1303',  // Piutang SPP SMA
                'credit_code' => '4-2200', // Pendapatan Pendaftaran
            ],
            [
                'source_code' => 'FEE_REGISTRATION_UNIV',
                'description' => 'Pendapatan pendaftaran mahasiswa baru',
                'debit_code' => '1-1304',  // Piutang UKT Universitas
                'credit_code' => '4-2200', // Pendapatan Pendaftaran
            ],
            [
                'source_code' => 'RENTAL_FACILITY',
                'description' => 'Pendapatan sewa fasilitas (gedung, lapangan)',
                'debit_code' => '1-1200',  // Bank BCA
                'credit_code' => '4-2300', // Pendapatan Sewa Fasilitas
            ],
            [
                'source_code' => 'CANTEEN_RENT',
                'description' => 'Pendapatan sewa kantin',
                'debit_code' => '1-1200',  // Bank BCA
                'credit_code' => '4-2400', // Pendapatan Kantin & Koperasi
            ],
            [
                'source_code' => 'STUDY_TOUR',
                'description' => 'Pendapatan kegiatan study tour',
                'debit_code' => '1-1300',  // Piutang SPP TK
                'credit_code' => '4-2500', // Pendapatan Kegiatan Khusus
            ],
        ];

        foreach ($nonSppMappings as $mapping) {
            $mappings[] = [
                'source_type' => 'sales',
                'source_code' => $mapping['source_code'],
                'debit_account_id' => $accounts[$mapping['debit_code']] ?? null,
                'credit_account_id' => $accounts[$mapping['credit_code']] ?? null,
                'description' => $mapping['description'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 2.3 Pendapatan Hibah & Donasi
        $hibahMappings = [
            [
                'source_code' => 'GRANT_BOS',
                'description' => 'Pendapatan hibah BOS',
                'debit_code' => '1-1200',  // Bank BCA
                'credit_code' => '4-3100', // Pendapatan Hibah BOS
            ],
            [
                'source_code' => 'GRANT_BOP',
                'description' => 'Pendapatan hibah BOP',
                'debit_code' => '1-1200',  // Bank BCA
                'credit_code' => '4-3110', // Pendapatan Hibah BOP
            ],
            [
                'source_code' => 'GRANT_BOPTN',
                'description' => 'Pendapatan hibah BOPTN',
                'debit_code' => '1-1200',  // Bank BCA
                'credit_code' => '4-3120', // Pendapatan Hibah BOPTN
            ],
            [
                'source_code' => 'DONATION_ALUMNI',
                'description' => 'Donasi dari alumni',
                'debit_code' => '1-1200',  // Bank BCA
                'credit_code' => '4-3200', // Pendapatan Donasi Alumni
            ],
            [
                'source_code' => 'DONATION_CORPORATE',
                'description' => 'Donasi dari perusahaan',
                'debit_code' => '1-1200',  // Bank BCA
                'credit_code' => '4-3210', // Pendapatan Donasi Corporate
            ],
            [
                'source_code' => 'DONATION_PARENTS',
                'description' => 'Sumbangan sukarela orang tua',
                'debit_code' => '1-1200',  // Bank BCA
                'credit_code' => '4-3220', // Pendapatan Donasi Orang Tua
            ],
        ];

        foreach ($hibahMappings as $mapping) {
            $mappings[] = [
                'source_type' => 'sales',
                'source_code' => $mapping['source_code'],
                'debit_account_id' => $accounts[$mapping['debit_code']] ?? null,
                'credit_account_id' => $accounts[$mapping['credit_code']] ?? null,
                'description' => $mapping['description'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ================================================================
        // 3. MAPPING PURCHASE (Pembelian)
        // ================================================================

        $purchaseMappings = [
            [
                'source_code' => 'PURCHASE_ATK',
                'description' => 'Pembelian ATK kantor',
                'debit_code' => '5-2300',  // Beban ATK & Peralatan Kantor
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'PURCHASE_PRACTICUM',
                'description' => 'Pembelian bahan praktikum lab',
                'debit_code' => '5-1200',  // Beban Praktikum & Laboratorium
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'PURCHASE_BOOKS',
                'description' => 'Pembelian buku perpustakaan',
                'debit_code' => '5-1300',  // Beban Perpustakaan
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'PURCHASE_EQUIPMENT',
                'description' => 'Pembelian peralatan & mesin',
                'debit_code' => '1-2400',  // Peralatan & Mesin (Aset)
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'PURCHASE_IT',
                'description' => 'Pembelian komputer & IT equipment',
                'debit_code' => '1-2500',  // Komputer & IT Equipment (Aset)
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'PURCHASE_VEHICLE',
                'description' => 'Pembelian kendaraan operasional',
                'debit_code' => '1-2300',  // Kendaraan Operasional (Aset)
                'credit_code' => '2-2100', // Utang Bank Jangka Panjang
            ],
            [
                'source_code' => 'PURCHASE_FURNITURE',
                'description' => 'Pembelian meubelair kantor',
                'debit_code' => '1-2400',  // Peralatan & Mesin (Aset)
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'PURCHASE_INVENTORY_ATK',
                'description' => 'Pembelian persediaan ATK',
                'debit_code' => '1-1500',  // Persediaan ATK (Aset)
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'PURCHASE_INVENTORY_PRACTICUM',
                'description' => 'Pembelian persediaan bahan praktikum',
                'debit_code' => '1-1600',  // Persediaan Bahan Praktikum (Aset)
                'credit_code' => '2-1100', // Utang Usaha
            ],
        ];

        foreach ($purchaseMappings as $mapping) {
            $mappings[] = [
                'source_type' => 'purchase',
                'source_code' => $mapping['source_code'],
                'debit_account_id' => $accounts[$mapping['debit_code']] ?? null,
                'credit_account_id' => $accounts[$mapping['credit_code']] ?? null,
                'description' => $mapping['description'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ================================================================
        // 4. MAPPING EXPENSE (Beban / Pengeluaran)
        // ================================================================

        $expenseMappings = [
            // 4.1 Beban Operasional
            [
                'source_code' => 'EXP_ELECTRICITY',
                'description' => 'Tagihan listrik',
                'debit_code' => '5-2200',  // Beban Listrik & Air
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'EXP_WATER',
                'description' => 'Tagihan air',
                'debit_code' => '5-2200',  // Beban Listrik & Air
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'EXP_INTERNET',
                'description' => 'Tagihan internet',
                'debit_code' => '5-2700',  // Beban Komunikasi & Internet
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'EXP_PHONE',
                'description' => 'Tagihan telepon',
                'debit_code' => '5-2700',  // Beban Komunikasi & Internet
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'EXP_MAINTENANCE',
                'description' => 'Biaya perawatan gedung',
                'debit_code' => '5-2400',  // Beban Perawatan Gedung
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'EXP_TRANSPORT',
                'description' => 'Biaya transportasi & perjalanan dinas',
                'debit_code' => '5-2500',  // Beban Transportasi
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'EXP_INSURANCE',
                'description' => 'Premi asuransi',
                'debit_code' => '5-2600',  // Beban Asuransi
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'EXP_TRAINING',
                'description' => 'Biaya pelatihan & pengembangan SDM',
                'debit_code' => '5-2800',  // Beban Pelatihan SDM
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'EXP_MARKETING',
                'description' => 'Biaya pemasaran & promosi',
                'debit_code' => '5-2900',  // Beban Pemasaran
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'EXP_EXTRACURRICULAR',
                'description' => 'Biaya kegiatan ekstrakurikuler',
                'debit_code' => '5-1400',  // Beban Ekstrakurikuler
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'EXP_GRADUATION',
                'description' => 'Biaya wisuda & pelepasan',
                'debit_code' => '5-1500',  // Beban Wisuda
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'EXP_COMPETITION',
                'description' => 'Biaya perlombaan & kompetisi',
                'debit_code' => '5-1600',  // Beban Perlombaan
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'EXP_SCHOLARSHIP',
                'description' => 'Biaya pemberian beasiswa',
                'debit_code' => '5-1700',  // Beban Beasiswa
                'credit_code' => '2-1100', // Utang Usaha
            ],

            // 4.2 Beban Pajak & Non-Operasional
            [
                'source_code' => 'EXP_TAX_INCOME',
                'description' => 'Pembayaran PPh',
                'debit_code' => '5-4100',  // Beban Pajak Penghasilan
                'credit_code' => '2-1500', // Utang Pajak
            ],
            [
                'source_code' => 'EXP_TAX_ART21',
                'description' => 'PPh Pasal 21 (karyawan)',
                'debit_code' => '5-4100',  // Beban Pajak Penghasilan
                'credit_code' => '2-1500', // Utang Pajak
            ],
            [
                'source_code' => 'EXP_BANK_ADMIN',
                'description' => 'Biaya administrasi bank',
                'debit_code' => '5-4200',  // Beban Administrasi Bank
                'credit_code' => '1-1200', // Bank BCA
            ],
            [
                'source_code' => 'EXP_BANK_TRANSFER',
                'description' => 'Biaya transfer bank',
                'debit_code' => '5-4200',  // Beban Administrasi Bank
                'credit_code' => '1-1200', // Bank BCA
            ],
            [
                'source_code' => 'EXP_PENALTY',
                'description' => 'Denda keterlambatan pembayaran',
                'debit_code' => '5-4300',  // Beban Denda & Penalti
                'credit_code' => '2-1100', // Utang Usaha
            ],
            [
                'source_code' => 'EXP_BAD_DEBT',
                'description' => 'Penghapusan piutang tak tertagih',
                'debit_code' => '5-4400',  // Beban Kerugian Piutang
                'credit_code' => '1-1300', // Piutang SPP TK
            ],
            [
                'source_code' => 'EXP_DEPRECIATION_BUILDING',
                'description' => 'Penyusutan gedung',
                'debit_code' => '5-3100',  // Beban Penyusutan Gedung
                'credit_code' => '1-2600', // Akumulasi Penyusutan
            ],
            [
                'source_code' => 'EXP_DEPRECIATION_VEHICLE',
                'description' => 'Penyusutan kendaraan',
                'debit_code' => '5-3200',  // Beban Penyusutan Kendaraan
                'credit_code' => '1-2600', // Akumulasi Penyusutan
            ],
            [
                'source_code' => 'EXP_DEPRECIATION_EQUIPMENT',
                'description' => 'Penyusutan peralatan & IT',
                'debit_code' => '5-3300',  // Beban Penyusutan Peralatan
                'credit_code' => '1-2600', // Akumulasi Penyusutan
            ],
            [
                'source_code' => 'EXP_OTHER',
                'description' => 'Beban lain-lain',
                'debit_code' => '5-4500',  // Beban Lain-lain
                'credit_code' => '2-1100', // Utang Usaha
            ],
        ];

        foreach ($expenseMappings as $mapping) {
            $mappings[] = [
                'source_type' => 'expense',
                'source_code' => $mapping['source_code'],
                'debit_account_id' => $accounts[$mapping['debit_code']] ?? null,
                'credit_account_id' => $accounts[$mapping['credit_code']] ?? null,
                'description' => $mapping['description'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ================================================================
        // 5. MAPPING KHUSUS untuk Jurnal Penyesuaian (Adjustment)
        // ================================================================

        $adjustmentMappings = [
            [
                'source_code' => 'ADJ_BAD_DEBT',
                'description' => 'Jurnal penyesuaian cadangan piutang tak tertagih',
                'debit_code' => '5-4400',  // Beban Kerugian Piutang
                'credit_code' => '1-1300', // Piutang SPP TK
            ],
            [
                'source_code' => 'ADJ_DEPRECIATION',
                'description' => 'Jurnal penyesuaian penyusutan aset',
                'debit_code' => '5-3100',  // Beban Penyusutan Gedung
                'credit_code' => '1-2600', // Akumulasi Penyusutan
            ],
            [
                'source_code' => 'ADJ_PREPAID_INCOME',
                'description' => 'Jurnal penyesuaian pendapatan diterima di muka',
                'debit_code' => '2-1400',  // Pendapatan Diterima di Muka
                'credit_code' => '4-1100', // Pendapatan SPP TK
            ],
            [
                'source_code' => 'ADJ_ACCRUED_EXPENSE',
                'description' => 'Jurnal penyesuaian beban yang masih harus dibayar',
                'debit_code' => '5-2200',  // Beban Listrik & Air
                'credit_code' => '2-1100', // Utang Usaha
            ],
        ];

        foreach ($adjustmentMappings as $mapping) {
            $mappings[] = [
                'source_type' => 'expense', // Bisa disesuaikan dengan kebutuhan
                'source_code' => $mapping['source_code'],
                'debit_account_id' => $accounts[$mapping['debit_code']] ?? null,
                'credit_account_id' => $accounts[$mapping['credit_code']] ?? null,
                'description' => $mapping['description'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ================================================================
        // 6. MAPPING KHUSUS untuk Transaksi Kas & Bank
        // ================================================================

        $cashMappings = [
            [
                'source_code' => 'CASH_RECEIPT_SPP',
                'description' => 'Penerimaan kas dari SPP',
                'debit_code' => '1-1100',  // Kas
                'credit_code' => '1-1300', // Piutang SPP TK
            ],
            [
                'source_code' => 'CASH_RECEIPT_DONATION',
                'description' => 'Penerimaan kas dari donasi',
                'debit_code' => '1-1100',  // Kas
                'credit_code' => '4-3200', // Pendapatan Donasi Alumni
            ],
            [
                'source_code' => 'CASH_PAYMENT_EXPENSE',
                'description' => 'Pembayaran beban dengan kas',
                'debit_code' => '5-2300',  // Beban ATK
                'credit_code' => '1-1100', // Kas
            ],
            [
                'source_code' => 'BANK_TRANSFER_IN',
                'description' => 'Transfer masuk ke rekening bank',
                'debit_code' => '1-1200',  // Bank BCA
                'credit_code' => '1-1100', // Kas
            ],
            [
                'source_code' => 'BANK_TRANSFER_OUT',
                'description' => 'Transfer keluar dari rekening bank',
                'debit_code' => '1-1100',  // Kas
                'credit_code' => '1-1200', // Bank BCA
            ],
        ];

        foreach ($cashMappings as $mapping) {
            $mappings[] = [
                'source_type' => 'expense',
                'source_code' => $mapping['source_code'],
                'debit_account_id' => $accounts[$mapping['debit_code']] ?? null,
                'credit_account_id' => $accounts[$mapping['credit_code']] ?? null,
                'description' => $mapping['description'],
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ================================================================
        // INSERT SEMUA MAPPING
        // ================================================================
      
        DB::table('account_mappings')->insertOrIgnore($mappings);
    }
}