<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxSettingsSeeder extends Seeder
{
    public function run()
    {
        $taxSettings = [];

        // Ambil ID Organisasi
        $org1_id = DB::table('organizations')->where('id', 1)->value('id');

        // 📋 Ambil peta kode -> ID dari tabel tax_types
        // Pastikan TaxTypesSeeder sudah dijalankan SEBELUM seeder ini!
        $taxTypeMap = DB::table('tax_types')
            ->pluck('id', 'code')
            ->toArray();

        // Helper: ambil tax_type_id berdasarkan kode
        $getTaxTypeId = function (string $code) use ($taxTypeMap) {
            return $taxTypeMap[$code] ?? throw new \RuntimeException(
                "Kode jenis pajak '$code' tidak ditemukan di tabel tax_types!"
            );
        };

        // ================================================================
        // 1. PAJAK PERTAMBAHAN NILAI (PPN)
        // ================================================================
        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('ppn'), // ✅ Ganti dari tax_type => tax_type_id
            'tax_code'        => 'PPN-001',
            'tax_name'        => 'PPN 11% (Tarif Normal)',
            'tax_rate'        => 11.0000,
            'min_amount'      => null,
            'max_amount'      => null,
            'effective_date'  => '2022-04-01',
            'expiry_date'     => '2025-03-31',
            'is_active'       => 0,
            'description'     => 'Tarif PPN 11% berlaku mulai 1 April 2022 sesuai UU HPP',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('ppn'),
            'tax_code'        => 'PPN-002',
            'tax_name'        => 'PPN 12% (Tarif Baru)',
            'tax_rate'        => 12.0000,
            'min_amount'      => null,
            'max_amount'      => null,
            'effective_date'  => '2025-04-01',
            'expiry_date'     => null,
            'is_active'       => 1,
            'description'     => 'Tarif PPN 12% berlaku mulai 1 April 2025 sesuai UU HPP',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        // ================================================================
        // 2. PPH PASAL 21
        // ================================================================
        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('pph21'),
            'tax_code'        => 'PPh21-01',
            'tax_name'        => 'PPh Pasal 21 - 0% (PTKP)',
            'tax_rate'        => 0.0000,
            'min_amount'      => 0,
            'max_amount'      => 54000000,
            'effective_date'  => '2024-01-01',
            'expiry_date'     => null,
            'is_active'       => 1,
            'description'     => 'Penghasilan kena pajak sampai dengan Rp 54.000.000 (PTKP)',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('pph21'),
            'tax_code'        => 'PPh21-02',
            'tax_name'        => 'PPh Pasal 21 - 5%',
            'tax_rate'        => 5.0000,
            'min_amount'      => 54000000,
            'max_amount'      => 60000000,
            'effective_date'  => '2024-01-01',
            'expiry_date'     => null,
            'is_active'       => 1,
            'description'     => 'Tarif 5% untuk PKP Rp 54.000.001 - Rp 60.000.000',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('pph21'),
            'tax_code'        => 'PPh21-03',
            'tax_name'        => 'PPh Pasal 21 - 15%',
            'tax_rate'        => 15.0000,
            'min_amount'      => 60000000,
            'max_amount'      => 250000000,
            'effective_date'  => '2024-01-01',
            'expiry_date'     => null,
            'is_active'       => 1,
            'description'     => 'Tarif 15% untuk PKP Rp 60.000.001 - Rp 250.000.000',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('pph21'),
            'tax_code'        => 'PPh21-04',
            'tax_name'        => 'PPh Pasal 21 - 25%',
            'tax_rate'        => 25.0000,
            'min_amount'      => 250000000,
            'max_amount'      => 500000000,
            'effective_date'  => '2024-01-01',
            'expiry_date'     => null,
            'is_active'       => 1,
            'description'     => 'Tarif 25% untuk PKP Rp 250.000.001 - Rp 500.000.000',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('pph21'),
            'tax_code'        => 'PPh21-05',
            'tax_name'        => 'PPh Pasal 21 - 30%',
            'tax_rate'        => 30.0000,
            'min_amount'      => 500000000,
            'max_amount'      => 5000000000,
            'effective_date'  => '2024-01-01',
            'expiry_date'     => null,
            'is_active'       => 1,
            'description'     => 'Tarif 30% untuk PKP Rp 500.000.001 - Rp 5.000.000.000',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('pph21'),
            'tax_code'        => 'PPh21-06',
            'tax_name'        => 'PPh Pasal 21 - 35%',
            'tax_rate'        => 35.0000,
            'min_amount'      => 5000000000,
            'max_amount'      => null,
            'effective_date'  => '2024-01-01',
            'expiry_date'     => null,
            'is_active'       => 1,
            'description'     => 'Tarif 35% untuk PKP di atas Rp 5.000.000.000',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        // ================================================================
        // 3. PPH PASAL 23
        // ================================================================
        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('pph23'),
            'tax_code'        => 'PPh23-01',
            'tax_name'        => 'PPh Pasal 23 - 2% (Jasa)',
            'tax_rate'        => 2.0000,
            'min_amount'      => null,
            'max_amount'      => null,
            'effective_date'  => '2024-01-01',
            'expiry_date'     => null,
            'is_active'       => 1,
            'description'     => 'PPh 23 untuk jasa (2% dari bruto) - tidak termasuk PPN',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('pph23'),
            'tax_code'        => 'PPh23-02',
            'tax_name'        => 'PPh Pasal 23 - 15% (Sewa)',
            'tax_rate'        => 15.0000,
            'min_amount'      => null,
            'max_amount'      => null,
            'effective_date'  => '2024-01-01',
            'expiry_date'     => null,
            'is_active'       => 1,
            'description'     => 'PPh 23 untuk sewa (15% dari bruto)',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        // ================================================================
        // 4. PPH PASAL 22 — masuk ke jenis "other"
        // ================================================================
        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('other'),
            'tax_code'        => 'PPh22-01',
            'tax_name'        => 'PPh Pasal 22 - 1.5% (Pembelian)',
            'tax_rate'        => 1.5000,
            'min_amount'      => 2000000,
            'max_amount'      => null,
            'effective_date'  => '2024-01-01',
            'expiry_date'     => null,
            'is_active'       => 1,
            'description'     => 'PPh 22 untuk pembelian barang (1.5% dari harga) - minimal Rp 2.000.000',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        // ================================================================
        // 5. PPH PASAL 4 AYAT (2) — masuk ke jenis "other"
        // ================================================================
        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('other'),
            'tax_code'        => 'PPh4-2-01',
            'tax_name'        => 'PPh Final - 10% (Sewa Tanah/Bangunan)',
            'tax_rate'        => 10.0000,
            'min_amount'      => null,
            'max_amount'      => null,
            'effective_date'  => '2024-01-01',
            'expiry_date'     => null,
            'is_active'       => 1,
            'description'     => 'PPh Final 10% untuk sewa tanah dan/atau bangunan',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('other'),
            'tax_code'        => 'PPh4-2-02',
            'tax_name'        => 'PPh Final - 0.5% (UMKM)',
            'tax_rate'        => 0.5000,
            'min_amount'      => null,
            'max_amount'      => 4800000000,
            'effective_date'  => '2024-01-01',
            'expiry_date'     => null,
            'is_active'       => 1,
            'description'     => 'PPh Final 0.5% untuk UMKM dengan omzet maksimal Rp 4.8M per tahun',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        // ================================================================
        // 6. PAJAK DAERAH — masuk ke jenis "other"
        // ================================================================
        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('other'),
            'tax_code'        => 'PBB-001',
            'tax_name'        => 'Pajak Bumi dan Bangunan (PBB)',
            'tax_rate'        => 0.1000,
            'min_amount'      => null,
            'max_amount'      => null,
            'effective_date'  => '2024-01-01',
            'expiry_date'     => null,
            'is_active'       => 1,
            'description'     => 'PBB untuk bangunan yayasan (0.1% dari NJOP)',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('other'),
            'tax_code'        => 'BPHTB-001',
            'tax_name'        => 'Bea Perolehan Hak atas Tanah dan Bangunan',
            'tax_rate'        => 5.0000,
            'min_amount'      => null,
            'max_amount'      => null,
            'effective_date'  => '2024-01-01',
            'expiry_date'     => null,
            'is_active'       => 1,
            'description'     => 'BPHTB 5% untuk perolehan tanah/bangunan',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        // ================================================================
        // 7. PAJAK LAINNYA — Bea Meterai
        // ================================================================
        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('other'),
            'tax_code'        => 'MTR-001',
            'tax_name'        => 'Bea Meterai Rp 10.000',
            'tax_rate'        => 10000.0000,
            'min_amount'      => null,
            'max_amount'      => null,
            'effective_date'  => '2024-01-01',
            'expiry_date'     => null,
            'is_active'       => 1,
            'description'     => 'Bea meterai untuk dokumen tertentu (Rp 10.000)',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('other'),
            'tax_code'        => 'MTR-002',
            'tax_name'        => 'Bea Meterai Rp 6.000',
            'tax_rate'        => 6000.0000,
            'min_amount'      => null,
            'max_amount'      => null,
            'effective_date'  => '2024-01-01',
            'expiry_date'     => null,
            'is_active'       => 1,
            'description'     => 'Bea meterai untuk dokumen tertentu (Rp 6.000)',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        // ================================================================
        // 8. TARIF PAJAK HISTORIS
        // ================================================================
        $taxSettings[] = [
            'organization_id' => $org1_id,
            'tax_type_id'     => $getTaxTypeId('ppn'),
            'tax_code'        => 'PPN-OLD',
            'tax_name'        => 'PPN 10% (Tarif Lama)',
            'tax_rate'        => 10.0000,
            'min_amount'      => null,
            'max_amount'      => null,
            'effective_date'  => '2000-01-01',
            'expiry_date'     => '2022-03-31',
            'is_active'       => 0,
            'description'     => 'Tarif PPN 10% berlaku sebelum 1 April 2022 (sudah diganti 11%)',
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        // ================================================================
        // INSERT DENGAN CEK DUPLIKAT (berdasarkan tax_code)
        // ================================================================
        $existingTaxCodes = DB::table('tax_settings')
            ->where('organization_id', $org1_id)
            ->pluck('tax_code')
            ->toArray();

        $newData = [];
        $updatedData = [];

        foreach ($taxSettings as $tax) {
            if (!in_array($tax['tax_code'], $existingTaxCodes)) {
                $newData[] = $tax;
            } else {
                $updatedData[] = $tax;
            }
        }

        if (!empty($newData)) {
            DB::table('tax_settings')->insert($newData);
            $this->command->info('✅ ' . count($newData) . ' pengaturan pajak berhasil ditambahkan.');
        }

        if (!empty($updatedData)) {
            foreach ($updatedData as $data) {
                DB::table('tax_settings')
                    ->where('organization_id', $data['organization_id'])
                    ->where('tax_code', $data['tax_code'])
                    ->update([
                        'tax_type_id'    => $data['tax_type_id'],
                        'tax_name'       => $data['tax_name'],
                        'tax_rate'       => $data['tax_rate'],
                        'min_amount'     => $data['min_amount'],
                        'max_amount'     => $data['max_amount'],
                        'effective_date' => $data['effective_date'],
                        'expiry_date'    => $data['expiry_date'],
                        'is_active'      => $data['is_active'],
                        'description'    => $data['description'],
                        'updated_at'     => now(),
                    ]);
            }
            $this->command->info('🔄 ' . count($updatedData) . ' pengaturan pajak diperbarui.');
        }

        if (empty($newData) && empty($updatedData)) {
            $this->command->info('ℹ️ Tidak ada perubahan pada pengaturan pajak.');
        }
    }
}