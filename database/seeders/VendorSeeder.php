<?php

namespace Database\Seeders;

use Models\ChartOfAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        // Sesuaikan jika school_id / organization_id tidak boleh diambil otomatis
        $schoolId       = DB::table('schools')->value('id');
        $organizationId = DB::table('organizations')->value('id');

        // Cari akun Hutang Usaha di COA (sesuaikan kolom 'name' / 'code' dengan tabel Anda)
        $payableAccountId = ChartOfAccount::where('name', 'like', '%Beban Lain-lain%')->value('id');

        $now = now();

        $vendors = [
            [
                'code'                => 'VND-0001',
                'name'                => 'ATK Sejahtera',
                'legal_name'          => 'PT Alat Tulis Sejahtera',
                'vendor_type'         => 'supplier',
                'npwp'                => '01.234.567.8-091.000',
                'is_pkp'              => 1,
                'pkp_number'          => 'PKP-001/2024',
                'ppn_rate'            => 11.00,
                'contact_person'      => 'Budi Santoso',
                'phone'               => '021-5551001',
                'email'               => 'sales@atksejahtera.test',
                'website'             => 'https://atksejahtera.test',
                'address'             => 'Jl. Raya Kebayoran Lama No. 10',
                'city'                => 'Jakarta Selatan',
                'province'            => 'DKI Jakarta',
                'postal_code'         => '12210',
                'bank_name'           => 'BCA',
                'bank_account_number' => '1234567890',
                'bank_account_name'   => 'PT Alat Tulis Sejahtera',
                'payment_terms_days'  => 30,
                'notes'               => 'Supplier alat tulis kantor dan perlengkapan kelas.',
                'is_active'           => 1,
            ],
            [
                'code'                => 'VND-0002',
                'name'                => 'Katering Sehat Bunda',
                'legal_name'          => 'CV Katering Sehat Bunda',
                'vendor_type'         => 'service',
                'npwp'                => '02.345.678.9-012.000',
                'is_pkp'              => 0,
                'pkp_number'          => null,
                'ppn_rate'            => 0,
                'contact_person'      => 'Siti Aminah',
                'phone'               => '0812-3456-7001',
                'email'               => 'order@kateringbunda.test',
                'website'             => null,
                'address'             => 'Jl. Melati No. 25',
                'city'                => 'Bekasi',
                'province'            => 'Jawa Barat',
                'postal_code'         => '17145',
                'bank_name'           => 'Mandiri',
                'bank_account_number' => '1310012345678',
                'bank_account_name'   => 'Siti Aminah',
                'payment_terms_days'  => 14,
                'notes'               => 'Penyedia katering makan siang siswa.',
                'is_active'           => 1,
            ],
            [
                'code'                => 'VND-0003',
                'name'                => 'Solusi Teknologi Nusantara',
                'legal_name'          => 'PT Solusi Teknologi Nusantara',
                'vendor_type'         => 'supplier',
                'npwp'                => '03.456.789.0-123.000',
                'is_pkp'              => 1,
                'pkp_number'          => 'PKP-045/2023',
                'ppn_rate'            => 11.00,
                'contact_person'      => 'Andi Wijaya',
                'phone'               => '021-5552002',
                'email'               => 'info@solusitek.test',
                'website'             => 'https://solusitek.test',
                'address'             => 'Gedung Graha Teknologi Lt. 3, Jl. Gatot Subroto Kav. 12',
                'city'                => 'Jakarta Selatan',
                'province'            => 'DKI Jakarta',
                'postal_code'         => '12930',
                'bank_name'           => 'BNI',
                'bank_account_number' => '0987654321',
                'bank_account_name'   => 'PT Solusi Teknologi Nusantara',
                'payment_terms_days'  => 45,
                'notes'               => 'Pengadaan komputer, jaringan, dan maintenance IT.',
                'is_active'           => 1,
            ],
            [
                'code'                => 'VND-0004',
                'name'                => 'Bersih Prima Service',
                'legal_name'          => 'PT Bersih Prima Service',
                'vendor_type'         => 'service',
                'npwp'                => '04.567.890.1-234.000',
                'is_pkp'              => 1,
                'pkp_number'          => 'PKP-112/2022',
                'ppn_rate'            => 11.00,
                'contact_person'      => 'Rahmat Hidayat',
                'phone'               => '021-5553003',
                'email'               => 'cs@bersihprima.test',
                'website'             => null,
                'address'             => 'Jl. Pemuda No. 88',
                'city'                => 'Jakarta Timur',
                'province'            => 'DKI Jakarta',
                'postal_code'         => '13220',
                'bank_name'           => 'BRI',
                'bank_account_number' => '002301000123456',
                'bank_account_name'   => 'PT Bersih Prima Service',
                'payment_terms_days'  => 30,
                'notes'               => 'Jasa kebersihan dan perawatan gedung sekolah.',
                'is_active'           => 1,
            ],
            [
                'code'                => 'VND-0005',
                'name'                => 'Toko Buku Cerdas',
                'legal_name'          => null,
                'vendor_type'         => 'supplier',
                'npwp'                => null,
                'is_pkp'              => 0,
                'pkp_number'          => null,
                'ppn_rate'            => 0,
                'contact_person'      => 'Lestari Dewi',
                'phone'               => '0813-9876-5002',
                'email'               => 'tokobukucerdas@example.test',
                'website'             => null,
                'address'             => 'Jl. Pendidikan No. 5',
                'city'                => 'Depok',
                'province'            => 'Jawa Barat',
                'postal_code'         => '16424',
                'bank_name'           => 'BCA',
                'bank_account_number' => '5550123456',
                'bank_account_name'   => 'Lestari Dewi',
                'payment_terms_days'  => 0,
                'notes'               => 'Pembelian buku pelajaran, bayar tunai/COD.',
                'is_active'           => 1,
            ],
            [
                'code'                => 'VND-0006',
                'name'                => 'Seragam Jaya Konveksi',
                'legal_name'          => 'CV Seragam Jaya',
                'vendor_type'         => 'supplier',
                'npwp'                => '05.678.901.2-345.000',
                'is_pkp'              => 0,
                'pkp_number'          => null,
                'ppn_rate'            => 0,
                'contact_person'      => 'Hendra Gunawan',
                'phone'               => '022-4204004',
                'email'               => 'hendra@seragamjaya.test',
                'website'             => null,
                'address'             => 'Jl. Cihampelas No. 150',
                'city'                => 'Bandung',
                'province'            => 'Jawa Barat',
                'postal_code'         => '40131',
                'bank_name'           => 'Mandiri',
                'bank_account_number' => '1320098765432',
                'bank_account_name'   => 'CV Seragam Jaya',
                'payment_terms_days'  => 21,
                'notes'               => 'Vendor tidak aktif, contoh data nonaktif.',
                'is_active'           => 0,
            ],
        ];

        foreach ($vendors as $vendor) {
            DB::table('vendors')->updateOrInsert(
                ['code' => $vendor['code']],
                array_merge($vendor, [
                    'school_id'          => $schoolId,
                    'organization_id'    => $organizationId,
                    'payable_account_id' => $payableAccountId,
                    'created_by'         => null,
                    'updated_by'         => null,
                    'created_at'         => $now,
                    'updated_at'         => $now,
                    'deleted_at'         => null,
                ])
            );
        }
    }
}