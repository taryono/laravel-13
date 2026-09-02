<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan sementara Foreign Key Check agar bisa melakukan truncate
        // pada tabel yang memiliki self-referencing foreign key.
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Department::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // =====================================================================
        // 1. PUSAT YAYASAN (Parent ID = NULL)
        //    Governance & unit-unit pusat yang menaungi seluruh unit sekolah.
        // =====================================================================
        $yayasan = Department::create([
            'code' => 'YAY',
            'name' => 'Pengurus Yayasan',
            'description' => 'Manajemen tertinggi yayasan (Pembina, Pengurus, Pengawas).',
            'parent_id' => null,
            'is_active' => true,
        ]);

        $hrPusat = Department::create([
            'code' => 'HR-PST',
            'name' => 'HR & GA Pusat',
            'description' => 'Human Resources & General Affairs tingkat yayasan.',
            'parent_id' => null,
            'is_active' => true,
        ]);

        $financePusat = Department::create([
            'code' => 'FIN-PST',
            'name' => 'Keuangan Pusat',
            'description' => 'Konsolidasi keuangan, akuntansi, dan perpajakan seluruh unit.',
            'parent_id' => null,
            'is_active' => true,
        ]);

        $itPusat = Department::create([
            'code' => 'IT-PST',
            'name' => 'IT Pusat',
            'description' => 'Mengelola infrastruktur dan sistem teknologi seluruh unit yayasan.',
            'parent_id' => null,
            'is_active' => true,
        ]);

        $marketingPusat = Department::create([
            'code' => 'MKT-PST',
            'name' => 'Marketing & PPDB',
            'description' => 'Promosi yayasan dan penerimaan peserta didik baru seluruh unit.',
            'parent_id' => null,
            'is_active' => true,
        ]);

        // Sub-departemen IT Pusat
        Department::create([
            'code' => 'IT-DEV',
            'name' => 'Software Development',
            'description' => 'Tim pengembang aplikasi dan sistem internal.',
            'parent_id' => $itPusat->id,
            'is_active' => true,
        ]);

        Department::create([
            'code' => 'IT-SUP',
            'name' => 'IT Support & Infra',
            'description' => 'Bertanggung jawab atas jaringan, hardware, dan helpdesk.',
            'parent_id' => $itPusat->id,
            'is_active' => true,
        ]);

        // Sub-departemen HR Pusat
        Department::create([
            'code' => 'HR-REC',
            'name' => 'Recruitment & Training',
            'description' => 'Perekrutan karyawan baru dan pelatihan seluruh unit.',
            'parent_id' => $hrPusat->id,
            'is_active' => true,
        ]);

        // Sub-departemen Finance Pusat
        Department::create([
            'code' => 'FIN-TAX',
            'name' => 'Tax & Compliance',
            'description' => 'Perpajakan dan kepatuhan regulasi keuangan.',
            'parent_id' => $financePusat->id,
            'is_active' => true,
        ]);

        // =====================================================================
        // 2. UNIT SEKOLAH (Parent ID = NULL, sebagai Unit Utama)
        // =====================================================================
        $unitTk = Department::create([
            'code' => 'UNIT-TK',
            'name' => 'Unit Taman Kanak-Kanak',
            'description' => 'Operasional TK.',
            'parent_id' => null,
            'is_active' => true,
        ]);

        $unitSd = Department::create([
            'code' => 'UNIT-SD',
            'name' => 'Unit Sekolah Dasar',
            'description' => 'Operasional SD.',
            'parent_id' => null,
            'is_active' => true,
        ]);

        $unitSmp = Department::create([
            'code' => 'UNIT-SMP',
            'name' => 'Unit Sekolah Menengah Pertama',
            'description' => 'Operasional SMP.',
            'parent_id' => null,
            'is_active' => true,
        ]);

        $unitSma = Department::create([
            'code' => 'UNIT-SMA',
            'name' => 'Unit Sekolah Menengah Atas',
            'description' => 'Operasional SMA.',
            'parent_id' => null,
            'is_active' => true,
        ]);

        $unitUniv = Department::create([
            'code' => 'UNIT-UNIV',
            'name' => 'Universitas',
            'description' => 'Operasional Pendidikan Tinggi.',
            'parent_id' => null,
            'is_active' => true,
        ]);

        // =====================================================================
        // 3. SUB-DEPARTEMEN SEKOLAH (Child dari masing-masing Unit)
        //    Pola per unit: Kurikulum & Guru, Kesiswaan/BK, Sarpras & Umum,
        //    Tata Usaha & Administrasi.
        // =====================================================================

        // --- TK ---
        Department::create(['code' => 'TK-KUR', 'name' => 'Kurikulum & Guru TK', 'description' => 'Guru kelas TK.', 'parent_id' => $unitTk->id, 'is_active' => true]);
        Department::create(['code' => 'TK-TU', 'name' => 'Tata Usaha TK', 'description' => 'Administrasi dan tata usaha unit TK.', 'parent_id' => $unitTk->id, 'is_active' => true]);

        // --- SD ---
        Department::create(['code' => 'SD-KUR', 'name' => 'Kurikulum & Guru SD', 'description' => 'Guru kelas dan mapel SD.', 'parent_id' => $unitSd->id, 'is_active' => true]);
        Department::create(['code' => 'SD-TU', 'name' => 'Tata Usaha SD', 'description' => 'Administrasi dan tata usaha unit SD.', 'parent_id' => $unitSd->id, 'is_active' => true]);
        Department::create(['code' => 'SD-SAR', 'name' => 'Sarpras & Umum SD', 'description' => 'Staff pendukung, perpustakaan, lab SD.', 'parent_id' => $unitSd->id, 'is_active' => true]);

        // --- SMP ---
        Department::create(['code' => 'SMP-KUR', 'name' => 'Kurikulum & Guru SMP', 'description' => 'Guru mapel dan wali kelas SMP.', 'parent_id' => $unitSmp->id, 'is_active' => true]);
        Department::create(['code' => 'SMP-BK', 'name' => 'Kesiswaan & BK SMP', 'description' => 'Kesiswaan dan bimbingan konseling SMP.', 'parent_id' => $unitSmp->id, 'is_active' => true]);
        Department::create(['code' => 'SMP-TU', 'name' => 'Tata Usaha SMP', 'description' => 'Administrasi dan tata usaha unit SMP.', 'parent_id' => $unitSmp->id, 'is_active' => true]);

        // --- SMA ---
        Department::create(['code' => 'SMA-KUR', 'name' => 'Kurikulum & Guru SMA', 'description' => 'Guru mapel dan wali kelas SMA.', 'parent_id' => $unitSma->id, 'is_active' => true]);
        Department::create(['code' => 'SMA-BK', 'name' => 'Kesiswaan & BK SMA', 'description' => 'Kesiswaan dan bimbingan konseling SMA.', 'parent_id' => $unitSma->id, 'is_active' => true]);
        Department::create(['code' => 'SMA-TU', 'name' => 'Tata Usaha SMA', 'description' => 'Administrasi dan tata usaha unit SMA.', 'parent_id' => $unitSma->id, 'is_active' => true]);

        // --- Universitas ---
        Department::create(['code' => 'UNIV-REK', 'name' => 'Rektorat', 'description' => 'Rektor dan Wakil Rektor.', 'parent_id' => $unitUniv->id, 'is_active' => true]);
        Department::create(['code' => 'UNIV-DSN', 'name' => 'Dosen & Fakultas', 'description' => 'Dosen tetap, DLB, dan Dekan.', 'parent_id' => $unitUniv->id, 'is_active' => true]);
        Department::create(['code' => 'UNIV-LPPM', 'name' => 'Lembaga Penelitian & Pengabdian Masyarakat (LPPM)', 'description' => 'Peneliti dan staff pengabdian masyarakat.', 'parent_id' => $unitUniv->id, 'is_active' => true]);
        Department::create(['code' => 'UNIV-LPM', 'name' => 'Lembaga Penjaminan Mutu (LPM)', 'description' => 'Penjaminan mutu akademik universitas.', 'parent_id' => $unitUniv->id, 'is_active' => true]);
        Department::create(['code' => 'UNIV-BAAK', 'name' => 'BAAK & Administrasi Akademik', 'description' => 'Administrasi akademik, KRS, dan registrasi.', 'parent_id' => $unitUniv->id, 'is_active' => true]);
        Department::create(['code' => 'UNIV-KMHS', 'name' => 'Kemahasiswaan & Alumni', 'description' => 'Organisasi kemahasiswaan dan hubungan alumni.', 'parent_id' => $unitUniv->id, 'is_active' => true]);

        // =====================================================================
        // 4. DEPARTEMEN PENDUKUNG BERSAMA (Shared Services)
        //    Menaungi role-role operasional lapangan yang melayani semua unit.
        // =====================================================================
        $shared = Department::create([
            'code' => 'SHARED',
            'name' => 'Layanan Bersama',
            'description' => 'Departemen yang melayani seluruh unit yayasan.',
            'parent_id' => null,
            'is_active' => true,
        ]);

        Department::create(['code' => 'SHD-SEC', 'name' => 'Keamanan (Satpam)', 'description' => 'Personil keamanan seluruh area yayasan.', 'parent_id' => $shared->id, 'is_active' => true]);
        Department::create(['code' => 'SHD-CLN', 'name' => 'Kebersihan (Cleaning Service)', 'description' => 'Staff kebersihan seluruh area yayasan.', 'parent_id' => $shared->id, 'is_active' => true]);
        Department::create(['code' => 'SHD-GDN', 'name' => 'Perawatan Taman (Gardening)', 'description' => 'Tukang kebun dan perawatan taman/landscape seluruh area yayasan.', 'parent_id' => $shared->id, 'is_active' => true]);
        Department::create(['code' => 'SHD-MNT', 'name' => 'Maintenance & Teknisi', 'description' => 'Perawatan gedung, listrik, dan fasilitas fisik seluruh area yayasan.', 'parent_id' => $shared->id, 'is_active' => true]);
        Department::create(['code' => 'SHD-DRV', 'name' => 'Transportasi (Driver)', 'description' => 'Pengemudi dan operasional kendaraan yayasan.', 'parent_id' => $shared->id, 'is_active' => true]);
        Department::create(['code' => 'SHD-LIB', 'name' => 'Perpustakaan Bersama', 'description' => 'Pengelolaan perpustakaan yang melayani lintas unit (opsional, jika tidak dipisah per unit).', 'parent_id' => $shared->id, 'is_active' => true]);
    }
}