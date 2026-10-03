<?php

namespace Database\Seeders;

use Models\DocumentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan dulu (opsional, hapus jika tidak perlu)
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DocumentType::truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $types = [
            // === GLOBAL (organization_id = NULL) ===
            [
                'organization_id' => null,
                'parent_id' => null,
                'code' => 'KTP',
                'name' => 'Kartu Tanda Penduduk',
                'slug' => 'ktp',
                'category' => 'identity',
                'description' => 'Dokumen identitas resmi warga negara',
                'applicable_to' => 'all',
                'is_required' => 1,
                'is_expirable' => 0,
                'default_validity_days' => null,
                'allowed_extensions' => json_encode(['pdf', 'jpg', 'jpeg', 'png']),
                'max_file_size_mb' => 5,
                'requires_verification' => 1,
                'requires_signature' => 0,
                'template_path' => null,
                'sort_order' => 1,
                'is_active' => 1,
                'is_system' => 1,
            ],
            [
                'organization_id' => null,
                'parent_id' => null,
                'code' => 'IJAZAH',
                'name' => 'Ijazah Pendidikan',
                'slug' => 'ijazah',
                'category' => 'academic',
                'description' => 'Bukti kelulusan pendidikan formal',
                'applicable_to' => 'student',
                'is_required' => 1,
                'is_expirable' => 0,
                'default_validity_days' => null,
                'allowed_extensions' => json_encode(['pdf']),
                'max_file_size_mb' => 10,
                'requires_verification' => 1,
                'requires_signature' => 0,
                'template_path' => null,
                'sort_order' => 2,
                'is_active' => 1,
                'is_system' => 1,
            ],
            [
                'organization_id' => null,
                'parent_id' => null,
                'code' => 'AKTA_KELAHIRAN',
                'name' => 'Akta Kelahiran',
                'slug' => 'akta-kelahiran',
                'category' => 'identity',
                'description' => 'Akta kelahiran resmi',
                'applicable_to' => 'applicant',
                'is_required' => 1,
                'is_expirable' => 0,
                'default_validity_days' => null,
                'allowed_extensions' => json_encode(['pdf', 'jpg']),
                'max_file_size_mb' => 5,
                'requires_verification' => 1,
                'requires_signature' => 0,
                'template_path' => null,
                'sort_order' => 3,
                'is_active' => 1,
                'is_system' => 1,
            ],
            [
                'organization_id' => null,
                'parent_id' => null,
                'code' => 'SURAT_SEHAT',
                'name' => 'Surat Keterangan Sehat',
                'slug' => 'surat-keterangan-sehat',
                'category' => 'medical',
                'description' => 'Surat keterangan sehat dari dokter/rumah sakit',
                'applicable_to' => 'all',
                'is_required' => 0,
                'is_expirable' => 1,
                'default_validity_days' => 90, // 3 bulan
                'allowed_extensions' => json_encode(['pdf']),
                'max_file_size_mb' => 5,
                'requires_verification' => 1,
                'requires_signature' => 1,
                'template_path' => null,
                'sort_order' => 4,
                'is_active' => 1,
                'is_system' => 0,
            ],
            [
                'organization_id' => null,
                'parent_id' => null,
                'code' => 'SLIP_GAJI',
                'name' => 'Slip Gaji',
                'slug' => 'slip-gaji',
                'category' => 'financial',
                'description' => 'Bukti pendapatan pegawai',
                'applicable_to' => 'employee',
                'is_required' => 0,
                'is_expirable' => 1,
                'default_validity_days' => 30,
                'allowed_extensions' => json_encode(['pdf', 'png']),
                'max_file_size_mb' => 3,
                'requires_verification' => 1,
                'requires_signature' => 0,
                'template_path' => null,
                'sort_order' => 5,
                'is_active' => 1,
                'is_system' => 0,
            ],
            [
                'organization_id' => null,
                'parent_id' => null,
                'code' => 'SURAT_PERNYATAAN',
                'name' => 'Surat Pernyataan',
                'slug' => 'surat-pernyataan',
                'category' => 'legal',
                'description' => 'Surat pernyataan bermaterai',
                'applicable_to' => 'all',
                'is_required' => 0,
                'is_expirable' => 0,
                'default_validity_days' => null,
                'allowed_extensions' => json_encode(['pdf']),
                'max_file_size_mb' => 5,
                'requires_verification' => 1,
                'requires_signature' => 1,
                'template_path' => 'templates/surat-pernyataan.blade.php',
                'sort_order' => 6,
                'is_active' => 1,
                'is_system' => 0,
            ],
        ];

        foreach ($types as $data) {
            DocumentType::updateOrCreate(
                ['code' => $data['code'], 'organization_id' => $data['organization_id']],
                $data
            );
        }
    }
}
