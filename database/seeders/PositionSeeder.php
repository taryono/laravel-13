<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run()
    {
        // Kosongkan dulu tabel (opsional, jika ingin reset)
        // Position::truncate();

        $positions = [
            [
                'code' => 'chairman',
                'name' => 'Ketua Yayasan',
                'description' => 'Pemimpin tertinggi yayasan, bertanggung jawab atas seluruh kebijakan dan operasional yayasan.'
            ],
            [
                'code' => 'vice_chairman',
                'name' => 'Wakil Ketua Yayasan',
                'description' => 'Membantu Ketua Yayasan dan menggantikan posisi ketua jika berhalangan.'
            ],
            [
                'code' => 'secretary',
                'name' => 'Sekretaris Yayasan',
                'description' => 'Mengelola administrasi, surat-menyurat, kearsipan dan persiapan rapat.'
            ],
            [
                'code' => 'treasurer',
                'name' => 'Bendahara Yayasan',
                'description' => 'Mengelola keuangan, penyusunan anggaran, dan pelaporan keuangan yayasan.'
            ],
            [
                'code' => 'supervisor',
                'name' => 'Pengawas Yayasan',
                'description' => 'Mengawasi kinerja, kepatuhan dan jalannya operasional yayasan.'
            ],
            [
                'code' => 'school_principal',
                'name' => 'Kepala Sekolah',
                'description' => 'Pemimpin tertinggi di lingkungan sekolah, bertanggung jawab atas pendidikan & manajemen sekolah.'
            ],
            [
                'code' => 'vice_principal',
                'name' => 'Wakil Kepala Sekolah',
                'description' => 'Membantu Kepala Sekolah di bidang kurikulum, kesiswaan, sarpras, dll.'
            ],
            [
                'code' => 'head_administration',
                'name' => 'Kepala Tata Usaha',
                'description' => 'Pemimpin dan koordinator staf tata usaha sekolah.'
            ],
            [
                'code' => 'teacher',
                'name' => 'Guru',
                'description' => 'Tenaga pendidik yang bertugas merencanakan & melaksanakan pembelajaran.'
            ],
            [
                'code' => 'honorary_teacher',
                'name' => 'Guru Honorer',
                'description' => 'Guru dengan status tenaga pengajar tidak tetap/honorer.'
            ],
            [
                'code' => 'admin_staff',
                'name' => 'Staf Tata Usaha',
                'description' => 'Melaksanakan kegiatan administrasi dan ketatausahaan sekolah.'
            ],
            [
                'code' => 'finance_staff',
                'name' => 'Staf Keuangan',
                'description' => 'Membantu pengelolaan keuangan, pembukuan dan transaksi harian.'
            ],
            [
                'code' => 'it_staff',
                'name' => 'Staf IT / Admin IT',
                'description' => 'Mengelola sistem informasi, jaringan, dan perangkat teknologi.'
            ],
            [
                'code' => 'librarian',
                'name' => 'Petugas Perpustakaan',
                'description' => 'Mengelola layanan dan koleksi perpustakaan sekolah.'
            ],
            [
                'code' => 'cleaner',
                'name' => 'Petugas Kebersihan',
                'description' => 'Menjaga kebersihan dan kerapian lingkungan sekolah.'
            ],
            [
                'code' => 'security_staff',
                'name' => 'Satpam / Penjaga Sekolah',
                'description' => 'Menjaga keamanan dan ketertiban lingkungan sekolah.'
            ],
            [
                'code' => 'other',
                'name' => 'Lainnya',
                'description' => 'Jabatan lain yang belum tercantum dalam daftar.'
            ],
        ];

        // Masukkan semua data ke database
        foreach ($positions as $pos) {
            Position::firstOrCreate(
                ['code' => $pos['code']], // Cek jika sudah ada, jangan duplikat
                $pos
            );
        }
    }
}