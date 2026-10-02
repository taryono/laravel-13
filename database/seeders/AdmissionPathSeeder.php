<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmissionPathSeeder extends Seeder
{
    public function run(): void
    {
        $paths = [
            [
                'id'              => 1,
                'admission_id'    => 1,
                'organization_id' => 1,
                'code'            => 'ZONASI',
                'name'            => 'Jalur Zonasi Domisili (50%)',
                'description'     => 'Diperuntukkan bagi calon siswa dengan domisili terdekat ke sekolah berdasarkan radius tempat tinggal pada Kartu Keluarga (KK).',
                'quota'           => 200,
                'min_score'       => 0.00,
                'max_score'       => 100.00,
                'requirements'    => json_encode([
                    'documents' => [
                        'Kartu Keluarga (KK) minimal 1 tahun sebelum pendaftaran',
                        'KK asli orang tua/wali',
                        'Akta kelahiran',
                        'Ijazah/SKL SMP/MTs',
                        'Rapor semester 1-5',
                    ],
                    'notes' => 'Domisili wajib berada dalam radius zona sekolah yang ditetapkan.',
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'sort_order'   => 1,
                'is_active'    => 1,
                'open_date'    => '2026-09-23',
                'close_date'   => '2026-10-01',
                'updated_by'   => null,
                'updated_at'   => '2026-09-29 15:10:45',
            ],
            [
                'id'              => 2,
                'admission_id'    => 1,
                'organization_id' => 1,
                'code'            => 'AFIRMASI',
                'name'            => 'Jalur Afirmasi / KETM / ABK (20%)',
                'description'     => 'Khusus calon peserta didik dari keluarga ekonomi tidak mampu (KETM) dan penyandang disabilitas berprestasi.',
                'quota'           => 80,
                'min_score'       => 0.00,
                'max_score'       => 100.00,
                'requirements'    => json_encode([
                    'documents' => [
                        'Kartu Indonesia Pintar (KIP) / PKH / KKS',
                        'Surat Keterangan Tidak Mampu (SKTM) dari desa/kelurahan',
                        'Kartu Keluarga (KK)',
                        'Akta kelahiran',
                        'Surat keterangan disabilitas dari dokter/psikolog (khusus ABK)',
                    ],
                    'notes' => 'Verifikasi lapangan dapat dilakukan untuk memvalidasi status KETM.',
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'sort_order'   => 2,
                'is_active'    => 1,
                'open_date'    => '2026-09-23',
                'close_date'   => '2026-10-01',
                'updated_by'   => null,
                'updated_at'   => '2026-09-29 15:10:45',
            ],
            [
                'id'              => 3,
                'admission_id'    => 1,
                'organization_id' => 1,
                'code'            => 'PRESTASI',
                'name'            => 'Jalur Prestasi Nilai & Kejuaraan (25%)',
                'description'     => 'Berdasarkan nilai akumulasi rapor semester 1-5 dan/atau sertifikat penghargaan kejuaraan akademik, olahraga, seni, & keagamaan.',
                'quota'           => 100,
                'min_score'       => 70.00,
                'max_score'       => 100.00,
                'requirements'    => json_encode([
                    'documents' => [
                        'Rapor semester 1-5 dengan nilai rata-rata minimal 70',
                        'Sertifikat kejuaraan minimal tingkat kabupaten/kota',
                        'Piagam penghargaan asli',
                        'Kartu Keluarga (KK)',
                        'Akta kelahiran',
                    ],
                    'criteria' => [
                        'academic_weight'    => 60,
                        'achievement_weight' => 40,
                    ],
                    'notes' => 'Sertifikat kejuaraan bersifat opsional namun menjadi nilai tambah.',
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'sort_order'   => 3,
                'is_active'    => 1,
                'open_date'    => '2026-09-23',
                'close_date'   => '2026-10-01',
                'updated_by'   => null,
                'updated_at'   => '2026-09-29 15:10:45',
            ],
            [
                'id'              => 4,
                'admission_id'    => 1,
                'organization_id' => 1,
                'code'            => 'PERPINDAHAN',
                'name'            => 'Perpindahan Tugas Orang Tua (5%)',
                'description'     => 'Bagi calon siswa yang berpindah domisili karena perpindahan tugas kedinasan orang tua/wali dari instansi resmi atau anak guru.',
                'quota'           => 20,
                'min_score'       => 0.00,
                'max_score'       => 100.00,
                'requirements'    => json_encode([
                    'documents' => [
                        'Surat keterangan pindah tugas dari instansi/lembaga resmi',
                        'SK mutasi orang tua/wali',
                        'Kartu Keluarga (KK) terbaru',
                        'Akta kelahiran',
                        'SK mengajar dari kepala sekolah (khusus anak guru)',
                    ],
                    'notes' => 'Perpindahan tugas maksimal 1 tahun sebelum tanggal pendaftaran.',
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'sort_order'   => 4,
                'is_active'    => 1,
                'open_date'    => '2026-09-23',
                'close_date'   => '2026-10-01',
                'updated_by'   => null,
                'updated_at'   => '2026-09-29 15:10:45',
            ],
        ];

        foreach ($paths as $path) {
            DB::table('admission_paths')
                ->where('id', $path['id'])
                ->update($path);
        }
    }
}
