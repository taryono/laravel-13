<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    public function run()
    {
        // Ambil ID kategori
        $categories = DB::table('account_categories')->pluck('id', 'code');

        // ================================================================
        // TAHAP 1: INSERT SEMUA HEADER (parent_id = null)
        // ================================================================
        $headers = [
            // ===== ASET =====
            [
                'code' => '1-1000',
                'name' => 'ASET LANCAR',
                'account_category_id' => $categories['ASSET'],
                'parent_id' => null,
                'account_type' => 'header',
                'normal_balance' => 'debit',
                'currency_code' => 'IDR',
                'description' => 'Aset yang dapat dicairkan dalam waktu kurang dari 1 tahun',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '1-2000',
                'name' => 'ASET TETAP',
                'account_category_id' => $categories['ASSET'],
                'parent_id' => null,
                'account_type' => 'header',
                'normal_balance' => 'debit',
                'currency_code' => 'IDR',
                'description' => 'Aset berwujud yang dimiliki untuk operasional jangka panjang',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '1-3000',
                'name' => 'ASET LAINNYA',
                'account_category_id' => $categories['ASSET'],
                'parent_id' => null,
                'account_type' => 'header',
                'normal_balance' => 'debit',
                'currency_code' => 'IDR',
                'description' => 'Aset non-lancar lainnya seperti dana abadi',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ===== KEWAJIBAN =====
            [
                'code' => '2-1000',
                'name' => 'KEWAJIBAN LANCAR',
                'account_category_id' => $categories['LIABILITY'],
                'parent_id' => null,
                'account_type' => 'header',
                'normal_balance' => 'kredit',
                'currency_code' => 'IDR',
                'description' => 'Utang yang harus dibayar kurang dari 1 tahun',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '2-2000',
                'name' => 'KEWAJIBAN JANGKA PANJANG',
                'account_category_id' => $categories['LIABILITY'],
                'parent_id' => null,
                'account_type' => 'header',
                'normal_balance' => 'kredit',
                'currency_code' => 'IDR',
                'description' => 'Utang dengan jatuh tempo lebih dari 1 tahun',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ===== EKUITAS / ASET BERSIH =====
            [
                'code' => '3-1000',
                'name' => 'ASET BERSIH TIDAK TERIKAT',
                'account_category_id' => $categories['EQUITY'],
                'parent_id' => null,
                'account_type' => 'header',
                'normal_balance' => 'kredit',
                'currency_code' => 'IDR',
                'description' => 'Surplus yang bisa digunakan bebas oleh yayasan',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3-2000',
                'name' => 'ASET BERSIH TERIKAT TEMPORER',
                'account_category_id' => $categories['EQUITY'],
                'parent_id' => null,
                'account_type' => 'header',
                'normal_balance' => 'kredit',
                'currency_code' => 'IDR',
                'description' => 'Dana hibah yang hanya boleh digunakan untuk tujuan tertentu dalam waktu tertentu',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3-3000',
                'name' => 'ASET BERSIH TERIKAT PERMANEN',
                'account_category_id' => $categories['EQUITY'],
                'parent_id' => null,
                'account_type' => 'header',
                'normal_balance' => 'kredit',
                'currency_code' => 'IDR',
                'description' => 'Dana abadi yang pokoknya tidak boleh dipakai',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ===== PENDAPATAN =====
            [
                'code' => '4-1000',
                'name' => 'PENDAPATAN SPP / UKT',
                'account_category_id' => $categories['REVENUE'],
                'parent_id' => null,
                'account_type' => 'header',
                'normal_balance' => 'kredit',
                'currency_code' => 'IDR',
                'description' => 'Pendapatan dari SPP dan UKT semua jenjang',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '4-2000',
                'name' => 'PENDAPATAN NON-SPP',
                'account_category_id' => $categories['REVENUE'],
                'parent_id' => null,
                'account_type' => 'header',
                'normal_balance' => 'kredit',
                'currency_code' => 'IDR',
                'description' => 'Pendapatan selain SPP/UKT',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '4-3000',
                'name' => 'PENDAPATAN HIBAH & DONASI',
                'account_category_id' => $categories['REVENUE'],
                'parent_id' => null,
                'account_type' => 'header',
                'normal_balance' => 'kredit',
                'currency_code' => 'IDR',
                'description' => 'Pendapatan dari hibah pemerintah, donasi, sumbangan',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '4-4000',
                'name' => 'PENDAPATAN LAIN-LAIN',
                'account_category_id' => $categories['REVENUE'],
                'parent_id' => null,
                'account_type' => 'header',
                'normal_balance' => 'kredit',
                'currency_code' => 'IDR',
                'description' => 'Pendapatan investasi, sewa, dan lainnya',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ===== BEBAN =====
            [
                'code' => '5-1000',
                'name' => 'BEBAN PROGRAM PENDIDIKAN',
                'account_category_id' => $categories['EXPENSE'],
                'parent_id' => null,
                'account_type' => 'header',
                'normal_balance' => 'debit',
                'currency_code' => 'IDR',
                'description' => 'Biaya langsung untuk kegiatan belajar-mengajar',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '5-2000',
                'name' => 'BEBAN ADMINISTRASI & UMUM',
                'account_category_id' => $categories['EXPENSE'],
                'parent_id' => null,
                'account_type' => 'header',
                'normal_balance' => 'debit',
                'currency_code' => 'IDR',
                'description' => 'Biaya operasional kantor dan administrasi',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '5-3000',
                'name' => 'BEBAN PENYUSUTAN',
                'account_category_id' => $categories['EXPENSE'],
                'parent_id' => null,
                'account_type' => 'header',
                'normal_balance' => 'debit',
                'currency_code' => 'IDR',
                'description' => 'Penyusutan aset tetap',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '5-4000',
                'name' => 'BEBAN PAJAK & NON-OPERASIONAL',
                'account_category_id' => $categories['EXPENSE'],
                'parent_id' => null,
                'account_type' => 'header',
                'normal_balance' => 'debit',
                'currency_code' => 'IDR',
                'description' => 'Beban pajak dan biaya non-operasional lainnya',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($headers as $header) {
            DB::table('accounts')->insert($header);
        }

        // Ambil ID semua header berdasarkan kode
        $headerIds = DB::table('accounts')
            ->where('account_type', 'header')
            ->pluck('id', 'code');

        // ================================================================
        // TAHAP 2: INSERT DETAIL DENGAN parent_id YANG SUDAH DIKETAHUI
        // ================================================================

        $details = [];

        // ==========================================
        // A. ASET LANCAR (parent: 1-1000)
        // ==========================================
        $details[] = [
            'code' => '1-1100',
            'name' => 'Kas',
            'account_category_id' => $categories['ASSET'],
            'parent_id' => $headerIds['1-1000'],
            'account_type' => 'detail',
            'normal_balance' => 'debit',
            'currency_code' => 'IDR',
            'description' => 'Uang tunai di tangan',
            'opening_balance' => 50000000,
            'current_balance' => 50000000,
            'is_active' => 1,
            'is_system' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $details[] = [
            'code' => '1-1200',
            'name' => 'Bank BCA',
            'account_category_id' => $categories['ASSET'],
            'parent_id' => $headerIds['1-1000'],
            'account_type' => 'detail',
            'normal_balance' => 'debit',
            'currency_code' => 'IDR',
            'description' => 'Rekening Yayasan di BCA',
            'opening_balance' => 250000000,
            'current_balance' => 250000000,
            'is_active' => 1,
            'is_system' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $details[] = [
            'code' => '1-1210',
            'name' => 'Bank Mandiri',
            'account_category_id' => $categories['ASSET'],
            'parent_id' => $headerIds['1-1000'],
            'account_type' => 'detail',
            'normal_balance' => 'debit',
            'currency_code' => 'IDR',
            'description' => 'Rekening Yayasan di Bank Mandiri',
            'opening_balance' => 150000000,
            'current_balance' => 150000000,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $details[] = [
            'code' => '1-1220',
            'name' => 'Bank BRI',
            'account_category_id' => $categories['ASSET'],
            'parent_id' => $headerIds['1-1000'],
            'account_type' => 'detail',
            'normal_balance' => 'debit',
            'currency_code' => 'IDR',
            'description' => 'Rekening Yayasan di Bank BRI',
            'opening_balance' => 100000000,
            'current_balance' => 100000000,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Piutang per jenjang
        $piutangData = [
            ['code' => '1-1300', 'name' => 'Piutang SPP TK', 'desc' => 'Tagihan SPP siswa TK yang belum dibayar'],
            ['code' => '1-1301', 'name' => 'Piutang SPP SD', 'desc' => 'Tagihan SPP siswa SD yang belum dibayar'],
            ['code' => '1-1302', 'name' => 'Piutang SPP SMP', 'desc' => 'Tagihan SPP siswa SMP yang belum dibayar'],
            ['code' => '1-1303', 'name' => 'Piutang SPP SMA', 'desc' => 'Tagihan SPP siswa SMA yang belum dibayar'],
            ['code' => '1-1304', 'name' => 'Piutang UKT Universitas', 'desc' => 'Tagihan UKT mahasiswa yang belum dibayar'],
        ];
        foreach ($piutangData as $data) {
            $details[] = [
                'code' => $data['code'],
                'name' => $data['name'],
                'account_category_id' => $categories['ASSET'],
                'parent_id' => $headerIds['1-1000'],
                'account_type' => 'detail',
                'normal_balance' => 'debit',
                'currency_code' => 'IDR',
                'description' => $data['desc'],
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $details[] = [
            'code' => '1-1400',
            'name' => 'Piutang Hibah',
            'account_category_id' => $categories['ASSET'],
            'parent_id' => $headerIds['1-1000'],
            'account_type' => 'detail',
            'normal_balance' => 'debit',
            'currency_code' => 'IDR',
            'description' => 'Hibah yang sudah dijanjikan tapi belum diterima',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $details[] = [
            'code' => '1-1500',
            'name' => 'Persediaan ATK',
            'account_category_id' => $categories['ASSET'],
            'parent_id' => $headerIds['1-1000'],
            'account_type' => 'detail',
            'normal_balance' => 'debit',
            'currency_code' => 'IDR',
            'description' => 'Stok alat tulis kantor dan sekolah',
            'opening_balance' => 15000000,
            'current_balance' => 15000000,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $details[] = [
            'code' => '1-1600',
            'name' => 'Persediaan Bahan Praktikum',
            'account_category_id' => $categories['ASSET'],
            'parent_id' => $headerIds['1-1000'],
            'account_type' => 'detail',
            'normal_balance' => 'debit',
            'currency_code' => 'IDR',
            'description' => 'Stok bahan praktikum laboratorium',
            'opening_balance' => 5000000,
            'current_balance' => 5000000,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==========================================
        // B. ASET TETAP (parent: 1-2000)
        // ==========================================
        $details[] = [
            'code' => '1-2100',
            'name' => 'Tanah',
            'account_category_id' => $categories['ASSET'],
            'parent_id' => $headerIds['1-2000'],
            'account_type' => 'detail',
            'normal_balance' => 'debit',
            'currency_code' => 'IDR',
            'description' => 'Tanah milik yayasan',
            'opening_balance' => 5000000000,
            'current_balance' => 5000000000,
            'is_active' => 1,
            'is_system' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Gedung per jenjang
        $gedungData = [
            ['code' => '1-2200', 'name' => 'Gedung TK', 'desc' => 'Gedung untuk jenjang TK', 'balance' => 500000000],
            ['code' => '1-2210', 'name' => 'Gedung SD', 'desc' => 'Gedung untuk jenjang SD', 'balance' => 800000000],
            ['code' => '1-2220', 'name' => 'Gedung SMP', 'desc' => 'Gedung untuk jenjang SMP', 'balance' => 1000000000],
            ['code' => '1-2230', 'name' => 'Gedung SMA', 'desc' => 'Gedung untuk jenjang SMA', 'balance' => 1200000000],
            ['code' => '1-2240', 'name' => 'Gedung Universitas', 'desc' => 'Gedung kampus universitas', 'balance' => 2000000000],
        ];
        foreach ($gedungData as $data) {
            $details[] = [
                'code' => $data['code'],
                'name' => $data['name'],
                'account_category_id' => $categories['ASSET'],
                'parent_id' => $headerIds['1-2000'],
                'account_type' => 'detail',
                'normal_balance' => 'debit',
                'currency_code' => 'IDR',
                'description' => $data['desc'],
                'opening_balance' => $data['balance'],
                'current_balance' => $data['balance'],
                'is_active' => 1,
                'is_system' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $details[] = [
            'code' => '1-2300',
            'name' => 'Kendaraan Operasional',
            'account_category_id' => $categories['ASSET'],
            'parent_id' => $headerIds['1-2000'],
            'account_type' => 'detail',
            'normal_balance' => 'debit',
            'currency_code' => 'IDR',
            'description' => 'Mobil dan kendaraan dinas yayasan',
            'opening_balance' => 350000000,
            'current_balance' => 350000000,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $details[] = [
            'code' => '1-2400',
            'name' => 'Peralatan & Mesin',
            'account_category_id' => $categories['ASSET'],
            'parent_id' => $headerIds['1-2000'],
            'account_type' => 'detail',
            'normal_balance' => 'debit',
            'currency_code' => 'IDR',
            'description' => 'Mesin fotokopi, AC, peralatan dapur, dll',
            'opening_balance' => 200000000,
            'current_balance' => 200000000,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $details[] = [
            'code' => '1-2500',
            'name' => 'Komputer & IT Equipment',
            'account_category_id' => $categories['ASSET'],
            'parent_id' => $headerIds['1-2000'],
            'account_type' => 'detail',
            'normal_balance' => 'debit',
            'currency_code' => 'IDR',
            'description' => 'Komputer, laptop, server, projector',
            'opening_balance' => 300000000,
            'current_balance' => 300000000,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $details[] = [
            'code' => '1-2600',
            'name' => 'Akumulasi Penyusutan Aset Tetap',
            'account_category_id' => $categories['ASSET'],
            'parent_id' => $headerIds['1-2000'],
            'account_type' => 'detail',
            'normal_balance' => 'kredit', // CONTRA ASSET
            'currency_code' => 'IDR',
            'description' => 'Akumulasi penyusutan semua aset tetap (contra asset)',
            'opening_balance' => -50000000,
            'current_balance' => -50000000,
            'is_active' => 1,
            'is_system' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==========================================
        // C. ASET LAINNYA (parent: 1-3000)
        // ==========================================
        $details[] = [
            'code' => '1-3100',
            'name' => 'Dana Abadi (Endowment)',
            'account_category_id' => $categories['ASSET'],
            'parent_id' => $headerIds['1-3000'],
            'account_type' => 'detail',
            'normal_balance' => 'debit',
            'currency_code' => 'IDR',
            'description' => 'Dana yang pokoknya tidak boleh dipakai, hanya hasil investasinya',
            'opening_balance' => 1000000000,
            'current_balance' => 1000000000,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $details[] = [
            'code' => '1-3200',
            'name' => 'Investasi Jangka Panjang',
            'account_category_id' => $categories['ASSET'],
            'parent_id' => $headerIds['1-3000'],
            'account_type' => 'detail',
            'normal_balance' => 'debit',
            'currency_code' => 'IDR',
            'description' => 'Investasi saham, obligasi, reksadana',
            'opening_balance' => 500000000,
            'current_balance' => 500000000,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==========================================
        // D. KEWAJIBAN LANCAR (parent: 2-1000)
        // ==========================================
        $details[] = [
            'code' => '2-1100',
            'name' => 'Utang Usaha',
            'account_category_id' => $categories['LIABILITY'],
            'parent_id' => $headerIds['2-1000'],
            'account_type' => 'detail',
            'normal_balance' => 'kredit',
            'currency_code' => 'IDR',
            'description' => 'Utang kepada pemasok barang/jasa',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $details[] = [
            'code' => '2-1200',
            'name' => 'Utang Gaji & Tunjangan',
            'account_category_id' => $categories['LIABILITY'],
            'parent_id' => $headerIds['2-1000'],
            'account_type' => 'detail',
            'normal_balance' => 'kredit',
            'currency_code' => 'IDR',
            'description' => 'Gaji guru/staf yang belum dibayar',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => 1,
            'is_system' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $details[] = [
            'code' => '2-1300',
            'name' => 'Utang BPJS Ketenagakerjaan',
            'account_category_id' => $categories['LIABILITY'],
            'parent_id' => $headerIds['2-1000'],
            'account_type' => 'detail',
            'normal_balance' => 'kredit',
            'currency_code' => 'IDR',
            'description' => 'Iuran BPJS yang belum disetor',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $details[] = [
            'code' => '2-1400',
            'name' => 'Pendapatan Diterima di Muka (SPP)',
            'account_category_id' => $categories['LIABILITY'],
            'parent_id' => $headerIds['2-1000'],
            'account_type' => 'detail',
            'normal_balance' => 'kredit',
            'currency_code' => 'IDR',
            'description' => 'SPP yang sudah dibayar tapi belum menjadi hak yayasan (periode mendatang)',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => 1,
            'is_system' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $details[] = [
            'code' => '2-1500',
            'name' => 'Utang Pajak',
            'account_category_id' => $categories['LIABILITY'],
            'parent_id' => $headerIds['2-1000'],
            'account_type' => 'detail',
            'normal_balance' => 'kredit',
            'currency_code' => 'IDR',
            'description' => 'Pajak yang sudah dipotong/dihitung tapi belum disetor ke negara',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => 1,
            'is_system' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==========================================
        // E. KEWAJIBAN JANGKA PANJANG (parent: 2-2000)
        // ==========================================
        $details[] = [
            'code' => '2-2100',
            'name' => 'Utang Bank Jangka Panjang',
            'account_category_id' => $categories['LIABILITY'],
            'parent_id' => $headerIds['2-2000'],
            'account_type' => 'detail',
            'normal_balance' => 'kredit',
            'currency_code' => 'IDR',
            'description' => 'Utang bank untuk pembangunan gedung',
            'opening_balance' => 2000000000,
            'current_balance' => 2000000000,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==========================================
        // F. ASET BERSIH TIDAK TERIKAT (parent: 3-1000)
        // ==========================================
        $details[] = [
            'code' => '3-1100',
            'name' => 'Saldo Aset Bersih Tidak Terikat',
            'account_category_id' => $categories['EQUITY'],
            'parent_id' => $headerIds['3-1000'],
            'account_type' => 'detail',
            'normal_balance' => 'kredit',
            'currency_code' => 'IDR',
            'description' => 'Akumulasi surplus bebas dari tahun-tahun sebelumnya',
            'opening_balance' => 2500000000,
            'current_balance' => 2500000000,
            'is_active' => 1,
            'is_system' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $details[] = [
            'code' => '3-1200',
            'name' => 'Surplus/(Defisit) Tahun Berjalan',
            'account_category_id' => $categories['EQUITY'],
            'parent_id' => $headerIds['3-1000'],
            'account_type' => 'detail',
            'normal_balance' => 'kredit',
            'currency_code' => 'IDR',
            'description' => 'Selisih pendapatan - beban tahun ini (akan ditutup ke 3-1100)',
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_active' => 1,
            'is_system' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==========================================
        // G. ASET BERSIH TERIKAT TEMPORER (parent: 3-2000)
        // ==========================================
        $details[] = [
            'code' => '3-2100',
            'name' => 'Dana BOS Terikat',
            'account_category_id' => $categories['EQUITY'],
            'parent_id' => $headerIds['3-2000'],
            'account_type' => 'detail',
            'normal_balance' => 'kredit',
            'currency_code' => 'IDR',
            'description' => 'Dana BOS dari pemerintah yang penggunaannya diatur',
            'opening_balance' => 500000000,
            'current_balance' => 500000000,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $details[] = [
            'code' => '3-2200',
            'name' => 'Dana BOPTN Terikat',
            'account_category_id' => $categories['EQUITY'],
            'parent_id' => $headerIds['3-2000'],
            'account_type' => 'detail',
            'normal_balance' => 'kredit',
            'currency_code' => 'IDR',
            'description' => 'Dana BOPTN untuk universitas',
            'opening_balance' => 300000000,
            'current_balance' => 300000000,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $details[] = [
            'code' => '3-2300',
            'name' => 'Dana Hibah Beasiswa Terikat',
            'account_category_id' => $categories['EQUITY'],
            'parent_id' => $headerIds['3-2000'],
            'account_type' => 'detail',
            'normal_balance' => 'kredit',
            'currency_code' => 'IDR',
            'description' => 'Hibah khusus untuk beasiswa siswa/mahasiswa',
            'opening_balance' => 300000000,
            'current_balance' => 300000000,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $details[] = [
            'code' => '3-2400',
            'name' => 'Dana Pembangunan Gedung Terikat',
            'account_category_id' => $categories['EQUITY'],
            'parent_id' => $headerIds['3-2000'],
            'account_type' => 'detail',
            'normal_balance' => 'kredit',
            'currency_code' => 'IDR',
            'description' => 'Dana hibah khusus untuk pembangunan gedung',
            'opening_balance' => 200000000,
            'current_balance' => 200000000,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==========================================
        // H. ASET BERSIH TERIKAT PERMANEN (parent: 3-3000)
        // ==========================================
        $details[] = [
            'code' => '3-3100',
            'name' => 'Dana Abadi (Endowment Fund)',
            'account_category_id' => $categories['EQUITY'],
            'parent_id' => $headerIds['3-3000'],
            'account_type' => 'detail',
            'normal_balance' => 'kredit',
            'currency_code' => 'IDR',
            'description' => 'Pokok dana abadi yang tidak boleh dikurangi',
            'opening_balance' => 1000000000,
            'current_balance' => 1000000000,
            'is_active' => 1,
            'is_system' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ==========================================
        // I. PENDAPATAN SPP / UKT (parent: 4-1000)
        // ==========================================
        $sppData = [
            ['code' => '4-1100', 'name' => 'Pendapatan SPP TK', 'desc' => 'SPP siswa TK per bulan'],
            ['code' => '4-1101', 'name' => 'Pendapatan SPP SD', 'desc' => 'SPP siswa SD per bulan'],
            ['code' => '4-1102', 'name' => 'Pendapatan SPP SMP', 'desc' => 'SPP siswa SMP per bulan'],
            ['code' => '4-1103', 'name' => 'Pendapatan SPP SMA', 'desc' => 'SPP siswa SMA per bulan'],
            ['code' => '4-1104', 'name' => 'Pendapatan UKT Universitas', 'desc' => 'UKT mahasiswa per semester'],
        ];
        foreach ($sppData as $data) {
            $details[] = [
                'code' => $data['code'],
                'name' => $data['name'],
                'account_category_id' => $categories['REVENUE'],
                'parent_id' => $headerIds['4-1000'],
                'account_type' => 'detail',
                'normal_balance' => 'kredit',
                'currency_code' => 'IDR',
                'description' => $data['desc'],
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ==========================================
        // J. PENDAPATAN NON-SPP (parent: 4-2000)
        // ==========================================
        $nonSppData = [
            ['code' => '4-2100', 'name' => 'Pendapatan Uang Gedung', 'desc' => 'Pendapatan dari uang pembangunan/gedung (daftar ulang)'],
            ['code' => '4-2200', 'name' => 'Pendapatan Pendaftaran Siswa/Mahasiswa Baru', 'desc' => 'Biaya pendaftaran calon siswa/mahasiswa baru'],
            ['code' => '4-2300', 'name' => 'Pendapatan Sewa Fasilitas', 'desc' => 'Sewa gedung aula, lapangan, ruang pertemuan'],
            ['code' => '4-2400', 'name' => 'Pendapatan Kantin & Koperasi', 'desc' => 'Sewa kantin dan koperasi sekolah'],
            ['code' => '4-2500', 'name' => 'Pendapatan Kegiatan Khusus', 'desc' => 'Study tour, camping, outbound, dll'],
        ];
        foreach ($nonSppData as $data) {
            $details[] = [
                'code' => $data['code'],
                'name' => $data['name'],
                'account_category_id' => $categories['REVENUE'],
                'parent_id' => $headerIds['4-2000'],
                'account_type' => 'detail',
                'normal_balance' => 'kredit',
                'currency_code' => 'IDR',
                'description' => $data['desc'],
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ==========================================
        // K. PENDAPATAN HIBAH & DONASI (parent: 4-3000)
        // ==========================================
        $hibahData = [
            ['code' => '4-3100', 'name' => 'Pendapatan Hibah BOS', 'desc' => 'Dana BOS dari pemerintah'],
            ['code' => '4-3110', 'name' => 'Pendapatan Hibah BOP', 'desc' => 'Dana BOP untuk jenjang SD/SMP'],
            ['code' => '4-3120', 'name' => 'Pendapatan Hibah BOPTN', 'desc' => 'Dana BOPTN untuk universitas'],
            ['code' => '4-3200', 'name' => 'Pendapatan Donasi Alumni', 'desc' => 'Donasi dari alumni yayasan'],
            ['code' => '4-3210', 'name' => 'Pendapatan Donasi Corporate', 'desc' => 'Donasi dari perusahaan/swasta'],
            ['code' => '4-3220', 'name' => 'Pendapatan Donasi Orang Tua', 'desc' => 'Sumbangan sukarela dari orang tua siswa'],
        ];
        foreach ($hibahData as $data) {
            $details[] = [
                'code' => $data['code'],
                'name' => $data['name'],
                'account_category_id' => $categories['REVENUE'],
                'parent_id' => $headerIds['4-3000'],
                'account_type' => 'detail',
                'normal_balance' => 'kredit',
                'currency_code' => 'IDR',
                'description' => $data['desc'],
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ==========================================
        // L. PENDAPATAN LAIN-LAIN (parent: 4-4000)
        // ==========================================
        $lainData = [
            ['code' => '4-4100', 'name' => 'Pendapatan Bunga Bank', 'desc' => 'Bunga dari deposito dan tabungan'],
            ['code' => '4-4200', 'name' => 'Pendapatan Dividen', 'desc' => 'Dividen dari investasi saham'],
            ['code' => '4-4300', 'name' => 'Pendapatan Lain-lain', 'desc' => 'Pendapatan tidak terduga lainnya'],
        ];
        foreach ($lainData as $data) {
            $details[] = [
                'code' => $data['code'],
                'name' => $data['name'],
                'account_category_id' => $categories['REVENUE'],
                'parent_id' => $headerIds['4-4000'],
                'account_type' => 'detail',
                'normal_balance' => 'kredit',
                'currency_code' => 'IDR',
                'description' => $data['desc'],
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ==========================================
        // M. BEBAN PROGRAM PENDIDIKAN (parent: 5-1000)
        // ==========================================

        // Gaji per jenjang
        $gajiData = [
            ['code' => '5-1100', 'name' => 'Gaji Guru TK', 'desc' => 'Gaji dan tunjangan guru TK'],
            ['code' => '5-1101', 'name' => 'Gaji Guru SD', 'desc' => 'Gaji dan tunjangan guru SD'],
            ['code' => '5-1102', 'name' => 'Gaji Guru SMP', 'desc' => 'Gaji dan tunjangan guru SMP'],
            ['code' => '5-1103', 'name' => 'Gaji Guru SMA', 'desc' => 'Gaji dan tunjangan guru SMA'],
            ['code' => '5-1104', 'name' => 'Gaji Dosen Universitas', 'desc' => 'Gaji dan honorarium dosen'],
        ];
        foreach ($gajiData as $data) {
            $details[] = [
                'code' => $data['code'],
                'name' => $data['name'],
                'account_category_id' => $categories['EXPENSE'],
                'parent_id' => $headerIds['5-1000'],
                'account_type' => 'detail',
                'normal_balance' => 'debit',
                'currency_code' => 'IDR',
                'description' => $data['desc'],
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $programData = [
            ['code' => '5-1200', 'name' => 'Beban Praktikum & Laboratorium', 'desc' => 'Biaya bahan praktikum, alat lab, dan pemeliharaan lab'],
            ['code' => '5-1300', 'name' => 'Beban Perpustakaan', 'desc' => 'Pembelian buku, jurnal, langganan database, pemeliharaan perpustakaan'],
            ['code' => '5-1400', 'name' => 'Beban Kegiatan Ekstrakurikuler', 'desc' => 'Biaya pramuka, olahraga, seni, lomba, study tour'],
            ['code' => '5-1500', 'name' => 'Beban Wisuda & Pelepasan', 'desc' => 'Biaya wisuda siswa/mahasiswa dan pelepasan'],
            ['code' => '5-1600', 'name' => 'Beban Perlombaan & Kompetisi', 'desc' => 'Biaya mengikuti lomba akademik dan non-akademik'],
            ['code' => '5-1700', 'name' => 'Beban Beasiswa', 'desc' => 'Biaya pemberian beasiswa kepada siswa/mahasiswa berprestasi'],
        ];
        foreach ($programData as $data) {
            $details[] = [
                'code' => $data['code'],
                'name' => $data['name'],
                'account_category_id' => $categories['EXPENSE'],
                'parent_id' => $headerIds['5-1000'],
                'account_type' => 'detail',
                'normal_balance' => 'debit',
                'currency_code' => 'IDR',
                'description' => $data['desc'],
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ==========================================
        // N. BEBAN ADMINISTRASI & UMUM (parent: 5-2000)
        // ==========================================
        $adminData = [
            ['code' => '5-2100', 'name' => 'Gaji Staf Administrasi & TU', 'desc' => 'Gaji staf tata usaha, HRD, akuntan, IT'],
            ['code' => '5-2200', 'name' => 'Beban Listrik & Air', 'desc' => 'Tagihan listrik, air, dan internet'],
            ['code' => '5-2300', 'name' => 'Beban ATK & Peralatan Kantor', 'desc' => 'Pembelian alat tulis, kertas, tinta printer, dll'],
            ['code' => '5-2400', 'name' => 'Beban Perawatan Gedung & Fasilitas', 'desc' => 'Biaya perbaikan, renovasi ringan, kebersihan gedung'],
            ['code' => '5-2500', 'name' => 'Beban Transportasi & Perjalanan Dinas', 'desc' => 'Biaya perjalanan dinas guru/staf, BBM, tol'],
            ['code' => '5-2600', 'name' => 'Beban Asuransi', 'desc' => 'Premi asuransi aset dan kesehatan karyawan'],
            ['code' => '5-2700', 'name' => 'Beban Komunikasi & Internet', 'desc' => 'Tagihan telepon, internet, dan langganan sistem'],
            ['code' => '5-2800', 'name' => 'Beban Pelatihan & Pengembangan SDM', 'desc' => 'Biaya seminar, workshop, pelatihan guru/staf'],
            ['code' => '5-2900', 'name' => 'Beban Pemasaran & Promosi', 'desc' => 'Biaya promosi penerimaan siswa/mahasiswa baru'],
        ];
        foreach ($adminData as $data) {
            $details[] = [
                'code' => $data['code'],
                'name' => $data['name'],
                'account_category_id' => $categories['EXPENSE'],
                'parent_id' => $headerIds['5-2000'],
                'account_type' => 'detail',
                'normal_balance' => 'debit',
                'currency_code' => 'IDR',
                'description' => $data['desc'],
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ==========================================
        // O. BEBAN PENYUSUTAN (parent: 5-3000)
        // ==========================================
        $depresiasiData = [
            ['code' => '5-3100', 'name' => 'Beban Penyusutan Gedung', 'desc' => 'Penyusutan gedung sekolah dan kampus'],
            ['code' => '5-3200', 'name' => 'Beban Penyusutan Kendaraan', 'desc' => 'Penyusutan kendaraan operasional'],
            ['code' => '5-3300', 'name' => 'Beban Penyusutan Peralatan & IT', 'desc' => 'Penyusutan komputer, mesin, peralatan'],
        ];
        foreach ($depresiasiData as $data) {
            $details[] = [
                'code' => $data['code'],
                'name' => $data['name'],
                'account_category_id' => $categories['EXPENSE'],
                'parent_id' => $headerIds['5-3000'],
                'account_type' => 'detail',
                'normal_balance' => 'debit',
                'currency_code' => 'IDR',
                'description' => $data['desc'],
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ==========================================
        // P. BEBAN PAJAK & NON-OPERASIONAL (parent: 5-4000)
        // ==========================================
        $nonOpData = [
            ['code' => '5-4100', 'name' => 'Beban Pajak Penghasilan', 'desc' => 'PPh terutang yayasan'],
            ['code' => '5-4200', 'name' => 'Beban Administrasi Bank', 'desc' => 'Biaya admin bank, transfer, materai'],
            ['code' => '5-4300', 'name' => 'Beban Denda & Penalti', 'desc' => 'Denda keterlambatan pembayaran pajak atau pinjaman'],
            ['code' => '5-4400', 'name' => 'Beban Kerugian Piutang', 'desc' => 'Piutang SPP/UKT yang dihapuskan karena tidak tertagih'],
            ['code' => '5-4500', 'name' => 'Beban Lain-lain', 'desc' => 'Biaya tidak terduga lainnya'],
        ];
        foreach ($nonOpData as $data) {
            $details[] = [
                'code' => $data['code'],
                'name' => $data['name'],
                'account_category_id' => $categories['EXPENSE'],
                'parent_id' => $headerIds['5-4000'],
                'account_type' => 'detail',
                'normal_balance' => 'debit',
                'currency_code' => 'IDR',
                'description' => $data['desc'],
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_active' => 1,
                'is_system' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // ================================================================
        // TAHAP 3: INSERT SEMUA DETAIL
        // ================================================================
        DB::table('accounts')->insert($details);
    }
}