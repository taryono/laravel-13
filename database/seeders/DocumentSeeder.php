<?php

namespace Database\Seeders;

use Models\Document;
use Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada jenis dokumen
        $ktpType = DocumentType::where('code', 'KTP')->first();
        $ijazahType = DocumentType::where('code', 'IJAZAH')->first();
        $suratSehatType = DocumentType::where('code', 'SURAT_SEHAT')->first();

        // Ambil contoh pengguna (sesuaikan dengan data kamu)
        $user = \App\Models\User::first();

        if (!$ktpType || !$user) {
            echo "Data rujukan belum lengkap — lewati DocumentSeeder.\n";
            return;
        }

        $now = now();

        $documents = [
            [
                'organization_id' => null, // global
                'document_type_id' => $ktpType->id,
                'owner_type' => 'App\\Models\\User',
                'owner_id' => $user->id,
                'file_name' => 'ktp_john_doe.pdf',
                'file_path' => 'documents/ktp_john_doe.pdf',
                'file_disk' => 'local',
                'file_extension' => 'pdf',
                'file_size_kb' => 1024,
                'mime_type' => 'application/pdf',
                'file_hash' => hash('sha256', 'contoh_ktp_1'),
                'status' => 'verified',
                'verification_note' => 'Dokumen sesuai, diverifikasi otomatis',
                'verified_by' => $user->id,
                'verified_at' => $now,
                'rejected_at' => null,
                'issued_at' => $now->copy()->subYears(5),
                'valid_until' => null,
                'signed_by' => null,
                'signed_at' => null,
                'signature_data' => null,
                'notes' => 'Dokumen utama identitas',
                'custom_data' => null,
                'is_archived' => 0,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Tambahkan Ijazah jika ada
        if ($ijazahType) {
            $documents[] = [
                'organization_id' => null,
                'document_type_id' => $ijazahType->id,
                'owner_type' => 'App\\Models\\User',
                'owner_id' => $user->id,
                'file_name' => 'ijazah_sma_john_doe.pdf',
                'file_path' => 'documents/ijazah_sma_john_doe.pdf',
                'file_disk' => 'local',
                'file_extension' => 'pdf',
                'file_size_kb' => 2048,
                'mime_type' => 'application/pdf',
                'file_hash' => hash('sha256', 'contoh_ijazah_1'),
                'status' => 'pending_verification',
                'verification_note' => null,
                'verified_by' => null,
                'verified_at' => null,
                'rejected_at' => null,
                'issued_at' => $now->copy()->subYears(3),
                'valid_until' => null,
                'signed_by' => null,
                'signed_at' => null,
                'signature_data' => null,
                'notes' => 'Ijazah pendidikan terakhir',
                'custom_data' => json_encode(['jenjang' => 'SMA', 'tahun_lulus' => 2023]),
                'is_archived' => 0,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Tambahkan Surat Sehat jika ada
        if ($suratSehatType) {
            $documents[] = [
                'organization_id' => null,
                'document_type_id' => $suratSehatType->id,
                'owner_type' => 'App\\Models\\User',
                'owner_id' => $user->id,
                'file_name' => 'surat_sehat_john_doe.pdf',
                'file_path' => 'documents/surat_sehat_john_doe.pdf',
                'file_disk' => 'local',
                'file_extension' => 'pdf',
                'file_size_kb' => 512,
                'mime_type' => 'application/pdf',
                'file_hash' => hash('sha256', 'contoh_sehat_1'),
                'status' => 'verified',
                'verification_note' => 'Surat sehat masih berlaku',
                'verified_by' => $user->id,
                'verified_at' => $now,
                'rejected_at' => null,
                'issued_at' => $now->copy()->subDays(10),
                'valid_until' => $now->copy()->addDays(80), // 90 hari total
                'signed_by' => $user->id,
                'signed_at' => $now->copy()->subDays(10),
                'signature_data' => json_encode(['signer' => 'dr. Andi', 'signed_at' => $now->copy()->subDays(10)->toISOString()]),
                'notes' => 'Dari Puskesmas Jakarta',
                'custom_data' => null,
                'is_archived' => 0,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach ($documents as $data) {
            Document::create($data);
        }
    }
}
