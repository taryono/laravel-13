<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationStepSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            [
                'id'              => 1,
                'organization_id' => 1,
                'admission_id'    => 1,
                'step_number'     => 1,
                'title'           => 'Pilih Gelombang & Isi Data',
                'description'     => 'Pilih gelombang aktif dan lengkapi formulir pendaftaran: biodata siswa, NIK, NISN, alamat domisili, dan data orang tua/wali.',
                'badge_color'     => 'blue',
                'icon'            => null,
                'sort_order'      => 1,
                'is_active'       => 1,
                'created_by'      => null,
                'updated_by'      => null,
                'created_at'      => '2026-09-29 15:10:45',
                'updated_at'      => '2026-09-29 15:10:45',
                'deleted_at'      => null,
            ],
            [
                'id'              => 2,
                'organization_id' => 1,
                'admission_id'    => 1,
                'step_number'     => 2,
                'title'           => 'Unggah Dokumen Berkas',
                'description'     => 'Upload hasil scan/foto dokumen asli (KK, Akta Kelahiran, Rapor/SKL, dan sertifikat prestasi jika ada) dalam format PDF/JPG.',
                'badge_color'     => 'cyan',
                'icon'            => null,
                'sort_order'      => 2,
                'is_active'       => 1,
                'created_by'      => null,
                'updated_by'      => null,
                'created_at'      => '2026-09-29 15:10:45',
                'updated_at'      => '2026-09-29 15:10:45',
                'deleted_at'      => null,
            ],
            [
                'id'              => 3,
                'organization_id' => 1,
                'admission_id'    => 1,
                'step_number'     => 3,
                'title'           => 'Verifikasi oleh Panitia',
                'description'     => 'Tim verifikator PPDB memeriksa keabsahan dan kelengkapan dokumen. Calon siswa menerima bukti tanda bukti pendaftaran resmi.',
                'badge_color'     => 'green',
                'icon'            => null,
                'sort_order'      => 3,
                'is_active'       => 1,
                'created_by'      => null,
                'updated_by'      => null,
                'created_at'      => '2026-09-29 15:10:45',
                'updated_at'      => '2026-09-29 15:10:45',
                'deleted_at'      => null,
            ],
            [
                'id'              => 4,
                'organization_id' => 1,
                'admission_id'    => 1,
                'step_number'     => 4,
                'title'           => 'Pengumuman & Daftar Ulang',
                'description'     => 'Cek status kelulusan di portal PPDB online dan lakukan registrasi ulang fisik/online sesuai jadwal yang ditentukan.',
                'badge_color'     => 'dark',
                'icon'            => null,
                'sort_order'      => 4,
                'is_active'       => 1,
                'created_by'      => null,
                'updated_by'      => null,
                'created_at'      => '2026-09-29 15:10:45',
                'updated_at'      => '2026-09-29 15:10:45',
                'deleted_at'      => null,
            ],
        ];

        foreach ($steps as $step) {
            DB::table('registration_steps')->updateOrInsert(
                ['id' => $step['id']],
                $step
            );
        }
    }
}
