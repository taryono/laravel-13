<?php

namespace Database\Seeders;

use Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil organization_id dari tabel organizations (sesuaikan jika perlu)
        $organizationId = DB::table('organizations')->value('id');

        if (!$organizationId) {
            $this->command?->error('Tabel organizations masih kosong. Jalankan seeder organisasi terlebih dahulu.');
            return;
        }

        foreach ($this->tree() as $node) {
            $this->seedNode($organizationId, $node, null);
        }
    }

    /**
     * Simpan satu departemen beserta seluruh anaknya (rekursif).
     * Memakai updateOrCreate (kunci: organization_id + code) sehingga
     * seeder aman dijalankan berulang tanpa truncate dan tanpa data ganda.
     */
    private function seedNode(int $organizationId, array $node, ?int $parentId): void
    {
        $department = Department::updateOrCreate(
            [
                'organization_id' => $organizationId,
                'code'            => $node['code'],
            ],
            [
                'name'             => $node['name'],
                'parent_id'        => $parentId,
                'manager_id'       => null, // diisi setelah data karyawan/user tersedia
                'cost_center_code' => $node['cost_center_code'] ?? 'CC-' . $node['code'],
                'description'      => $node['description'] ?? null,
                'is_active'        => true,
            ]
        );

        foreach ($node['children'] ?? [] as $child) {
            $this->seedNode($organizationId, $child, $department->id);
        }
    }

    /**
     * Struktur departemen dalam bentuk pohon.
     */
    private function tree(): array
    {
        return [

            // =============================================================
            // 1. PUSAT YAYASAN
            // =============================================================
            [
                'code' => 'YAY',
                'name' => 'Pengurus Yayasan',
                'description' => 'Manajemen tertinggi yayasan (Pembina, Pengurus, Pengawas).',
            ],
            [
                'code' => 'HR-PST',
                'name' => 'HR & GA Pusat',
                'description' => 'Human Resources & General Affairs tingkat yayasan.',
                'children' => [
                    ['code' => 'HR-REC', 'name' => 'Recruitment & Training', 'description' => 'Perekrutan karyawan baru dan pelatihan seluruh unit.'],
                ],
            ],
            [
                'code' => 'FIN-PST',
                'name' => 'Keuangan Pusat',
                'description' => 'Konsolidasi keuangan, akuntansi, dan perpajakan seluruh unit.',
                'children' => [
                    ['code' => 'FIN-TAX', 'name' => 'Tax & Compliance', 'description' => 'Perpajakan dan kepatuhan regulasi keuangan.'],
                ],
            ],
            [
                'code' => 'IT-PST',
                'name' => 'IT Pusat',
                'description' => 'Mengelola infrastruktur dan sistem teknologi seluruh unit yayasan.',
                'children' => [
                    ['code' => 'IT-DEV', 'name' => 'Software Development', 'description' => 'Tim pengembang aplikasi dan sistem internal.'],
                    ['code' => 'IT-SUP', 'name' => 'IT Support & Infra', 'description' => 'Bertanggung jawab atas jaringan, hardware, dan helpdesk.'],
                ],
            ],
            [
                'code' => 'MKT-PST',
                'name' => 'Marketing & PPDB',
                'description' => 'Promosi yayasan dan penerimaan peserta didik baru seluruh unit.',
            ],

            // =============================================================
            // 2. UNIT SEKOLAH + 3. SUB-DEPARTEMEN SEKOLAH
            // =============================================================
            [
                'code' => 'UNIT-TK',
                'name' => 'Unit Taman Kanak-Kanak',
                'description' => 'Operasional TK.',
                'children' => [
                    ['code' => 'TK-KUR', 'name' => 'Kurikulum & Guru TK', 'description' => 'Guru kelas TK.'],
                    ['code' => 'TK-TU', 'name' => 'Tata Usaha TK', 'description' => 'Administrasi dan tata usaha unit TK.'],
                ],
            ],
            [
                'code' => 'UNIT-SD',
                'name' => 'Unit Sekolah Dasar',
                'description' => 'Operasional SD.',
                'children' => [
                    ['code' => 'SD-KUR', 'name' => 'Kurikulum & Guru SD', 'description' => 'Guru kelas dan mapel SD.'],
                    ['code' => 'SD-TU', 'name' => 'Tata Usaha SD', 'description' => 'Administrasi dan tata usaha unit SD.'],
                    ['code' => 'SD-SAR', 'name' => 'Sarpras & Umum SD', 'description' => 'Staff pendukung, perpustakaan, lab SD.'],
                ],
            ],
            [
                'code' => 'UNIT-SMP',
                'name' => 'Unit Sekolah Menengah Pertama',
                'description' => 'Operasional SMP.',
                'children' => [
                    ['code' => 'SMP-KUR', 'name' => 'Kurikulum & Guru SMP', 'description' => 'Guru mapel dan wali kelas SMP.'],
                    ['code' => 'SMP-BK', 'name' => 'Kesiswaan & BK SMP', 'description' => 'Kesiswaan dan bimbingan konseling SMP.'],
                    ['code' => 'SMP-TU', 'name' => 'Tata Usaha SMP', 'description' => 'Administrasi dan tata usaha unit SMP.'],
                ],
            ],
            [
                'code' => 'UNIT-SMA',
                'name' => 'Unit Sekolah Menengah Atas',
                'description' => 'Operasional SMA.',
                'children' => [
                    ['code' => 'SMA-KUR', 'name' => 'Kurikulum & Guru SMA', 'description' => 'Guru mapel dan wali kelas SMA.'],
                    ['code' => 'SMA-BK', 'name' => 'Kesiswaan & BK SMA', 'description' => 'Kesiswaan dan bimbingan konseling SMA.'],
                    ['code' => 'SMA-TU', 'name' => 'Tata Usaha SMA', 'description' => 'Administrasi dan tata usaha unit SMA.'],
                ],
            ],
            [
                'code' => 'UNIT-UNIV',
                'name' => 'Universitas',
                'description' => 'Operasional Pendidikan Tinggi.',
                'children' => [
                    ['code' => 'UNIV-REK', 'name' => 'Rektorat', 'description' => 'Rektor dan Wakil Rektor.'],
                    ['code' => 'UNIV-DSN', 'name' => 'Dosen & Fakultas', 'description' => 'Dosen tetap, DLB, dan Dekan.'],
                    ['code' => 'UNIV-LPPM', 'name' => 'Lembaga Penelitian & Pengabdian Masyarakat (LPPM)', 'description' => 'Peneliti dan staff pengabdian masyarakat.'],
                    ['code' => 'UNIV-LPM', 'name' => 'Lembaga Penjaminan Mutu (LPM)', 'description' => 'Penjaminan mutu akademik universitas.'],
                    ['code' => 'UNIV-BAAK', 'name' => 'BAAK & Administrasi Akademik', 'description' => 'Administrasi akademik, KRS, dan registrasi.'],
                    ['code' => 'UNIV-KMHS', 'name' => 'Kemahasiswaan & Alumni', 'description' => 'Organisasi kemahasiswaan dan hubungan alumni.'],
                ],
            ],

            // =============================================================
            // 4. LAYANAN BERSAMA (Shared Services)
            // =============================================================
            [
                'code' => 'SHARED',
                'name' => 'Layanan Bersama',
                'description' => 'Departemen yang melayani seluruh unit yayasan.',
                'children' => [
                    ['code' => 'SHD-SEC', 'name' => 'Keamanan (Satpam)', 'description' => 'Personil keamanan seluruh area yayasan.'],
                    ['code' => 'SHD-CLN', 'name' => 'Kebersihan (Cleaning Service)', 'description' => 'Staff kebersihan seluruh area yayasan.'],
                    ['code' => 'SHD-GDN', 'name' => 'Perawatan Taman (Gardening)', 'description' => 'Tukang kebun dan perawatan taman/landscape seluruh area yayasan.'],
                    ['code' => 'SHD-MNT', 'name' => 'Maintenance & Teknisi', 'description' => 'Perawatan gedung, listrik, dan fasilitas fisik seluruh area yayasan.'],
                    ['code' => 'SHD-DRV', 'name' => 'Transportasi (Driver)', 'description' => 'Pengemudi dan operasional kendaraan yayasan.'],
                    ['code' => 'SHD-LIB', 'name' => 'Perpustakaan Bersama', 'description' => 'Pengelolaan perpustakaan yang melayani lintas unit (opsional, jika tidak dipisah per unit).'],
                ],
            ],
        ];
    }
}