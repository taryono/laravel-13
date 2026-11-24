<?php

namespace Database\Seeders;

use App\Enums\PostStatus;
use Models\CmsCategory;
use Models\CmsPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class CmsPostSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan minimal ada 1 user sebagai penulis
        $author = User::first() ?? User::factory()->create([
            'name' => 'Admin Sekolah',
            'email' => 'admin@sekolah.sch.id',
        ]);

        // 1. Buat Kategori Berita
        $categoriesData = [
            ['name' => 'Pengumuman', 'slug' => 'pengumuman', 'description' => 'Informasi dan pengumuman resmi sekolah'],
            ['name' => 'Prestasi Siswa', 'slug' => 'prestasi-siswa', 'description' => 'Catatan prestasi akademik dan non-akademik'],
            ['name' => 'Kegiatan Sekolah', 'slug' => 'kegiatan-sekolah', 'description' => 'Dokumentasi agenda dan kegiatan civitas sekolah'],
            ['name' => 'Info PPDB', 'slug' => 'info-ppdb', 'description' => 'Informasi Penerimaan Peserta Didik Baru'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = CmsCategory::updateOrCreate(
                ['slug' => $cat['slug']],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'is_active' => true,
                ]
            );
        }

        // 2. Buat Daftar Artikel Berita (status = published agar langsung tampil di halaman publik)
        $posts = [
            [
                'category_slug' => 'info-ppdb',
                'title' => 'Pembukaan Penerimaan Peserta Didik Baru (PPDB) Tahun Ajaran 2027/2028',
                'slug' => 'pembukaan-ppdb-tahun-ajaran-2027-2028',
                'excerpt' => 'Pendaftaran gelombang pertama untuk jalur prestasi dan reguler resmi dibuka secara daring melalui portal PPDB sekolah.',
                'content' => '<p>Sekolah resmi membuka pendaftaran peserta didik baru untuk Tahun Ajaran 2027/2028. Calon peserta didik dapat memilih jalur prestasi maupun jalur reguler sesuai ketentuan usia dan persyaratan administrasi yang berlaku.</p><h3>Jadwal Penting</h3><ul><li><strong>Gelombang 1 (Jalur Prestasi):</strong> 1 Januari – 28 Februari 2027</li><li><strong>Gelombang 2 (Jalur Reguler):</strong> 1 Maret – 30 April 2027</li></ul><p>Pastikan seluruh dokumen seperti Akta Kelahiran, Kartu Keluarga, dan rapor semester terakhir telah dipindai dengan jelas sebelum melakukan unggah berkas.</p>',
                'status' => PostStatus::PUBLISHED,
                'published_at' => now()->subHours(5),
                'is_featured' => true,
                'allow_comments' => true,
                'view_count' => 342,
                'meta_title' => 'Info PPDB Tahun Ajaran 2027/2028',
                'meta_description' => 'Jadwal lengkap dan syarat pendaftaran PPDB gelombang 1 dan 2.',
                'meta_keywords' => 'ppdb, pendaftaran siswa baru, sekolah',
            ],
            [
                'category_slug' => 'prestasi-siswa',
                'title' => 'Tim Olimpiade Sains Sekolah Raih Medali Emas Tingkat Nasional',
                'slug' => 'tim-olimpiade-sains-raih-medali-emas-nasional',
                'excerpt' => 'Kontingen siswa berhasil membawa pulang dua medali emas dan satu perak dalam ajang Olimpiade Sains Nasional tahun ini.',
                'content' => '<p>Prestasi membanggakan kembali ditorehkan oleh siswa-siswi terbaik sekolah dalam ajang kompetisi sains tingkat nasional. Setelah melalui pembinaan intensif selama empat bulan, tim berhasil unggul pada bidang Matematika dan Fisika.</p><blockquote>"Keberhasilan ini merupakan buah dari disiplin latihan siswa serta dukungan penuh dari para guru pembimbing dan orang tua," ujar Kepala Sekolah.</blockquote>',
                'status' => PostStatus::PUBLISHED,
                'published_at' => now()->subDays(2),
                'is_featured' => false,
                'allow_comments' => true,
                'view_count' => 218,
                'meta_title' => 'Medali Emas Olimpiade Sains Nasional',
                'meta_description' => 'Siswa sekolah meraih medali emas pada ajang Olimpiade Sains Nasional.',
                'meta_keywords' => 'prestasi, olimpiade sains, medali emas',
            ],
            [
                'category_slug' => 'pengumuman',
                'title' => 'Laporan Transparansi Realisasi Dana BOS Reguler Tahap I Tahun Anggaran 2026',
                'slug' => 'laporan-transparansi-realisasi-dana-bos-tahap-1-2026',
                'excerpt' => 'Sebagai wujud akuntabilitas pengelolaan keuangan satdik, berikut ringkasan realisasi penggunaan Dana BOS Reguler Tahap I.',
                'content' => '<p>Sesuai dengan Petunjuk Teknis Pengelolaan Dana Bantuan Operasional Sekolah (BOS), sekolah mempublikasikan ringkasan realisasi penggunaan anggaran Tahap I yang telah diverifikasi dan disinkronkan melalui sistem ARKAS.</p><p>Alokasi terbesar diprioritaskan pada pengembangan perpustakaan, pemeliharaan sarana prasarana kelas, serta penunjang kegiatan pembelajaran berbasis digital.</p>',
                'status' => PostStatus::PUBLISHED,
                'published_at' => now()->subDays(4),
                'is_featured' => false,
                'allow_comments' => false, // Komentar ditutup untuk laporan resmi
                'view_count' => 175,
                'meta_title' => 'Transparansi Dana BOS Tahap I',
                'meta_description' => 'Publikasi laporan pertanggungjawaban penggunaan Dana BOS Reguler.',
                'meta_keywords' => 'dana bos, rkas, transparansi sekolah',
            ],
            [
                'category_slug' => 'kegiatan-sekolah',
                'title' => 'Pelaksanaan Projek Penguatan Profil Pelajar Pancasila (P5) Tema Kewirausahaan',
                'slug' => 'pelaksanaan-p5-tema-kewirausahaan',
                'excerpt' => 'Siswa memamerkan puluhan produk kreatif hasil karya kelompok dalam gelar karya bazar kewirausahaan di aula sekolah.',
                'content' => '<p>Kegiatan Gelar Karya P5 berlangsung meriah dengan menampilkan beragam produk olahan pangan sehat dan kerajinan ramah lingkungan buatan siswa. Setiap kelompok mempresentasikan rencana bisnis hingga perhitungan modal sederhana di hadapan pengunjung.</p>',
                'status' => PostStatus::PUBLISHED,
                'published_at' => now()->subDays(6),
                'is_featured' => false,
                'allow_comments' => true,
                'view_count' => 129,
                'meta_title' => 'Gelar Karya P5 Kewirausahaan',
                'meta_description' => 'Dokumentasi bazar kewirausahaan siswa dalam program P5.',
                'meta_keywords' => 'p5, kewirausahaan, kegiatan siswa',
            ],
            [
                'category_slug' => 'pengumuman',
                'title' => 'Jadwal Penilaian Akhir Semester (PAS) Ganjil dan Pembagian Rapor',
                'slug' => 'jadwal-pas-ganjil-dan-pembagian-rapor',
                'excerpt' => 'Informasi pelaksanaan ujian berbasis CBT serta jadwal pengambilan laporan hasil belajar siswa oleh orang tua/wali.',
                'content' => '<p>Penilaian Akhir Semester (PAS) Ganjil akan dilaksanakan menggunakan sistem Computer Based Test (CBT) sekolah. Siswa diharapkan memastikan kartu peserta ujian dan akun login sudah aktif sebelum hari pelaksanaan.</p>',
                'status' => PostStatus::PUBLISHED,
                'published_at' => now()->subDays(9),
                'is_featured' => false,
                'allow_comments' => true,
                'view_count' => 410,
                'meta_title' => 'Jadwal PAS Ganjil',
                'meta_description' => 'Jadwal lengkap ujian PAS Ganjil dan pembagian rapor.',
                'meta_keywords' => 'pas ganjil, ujian cbt, rapor',
            ],
        ];

        foreach ($posts as $item) {
            $category = $categories[$item['category_slug']] ?? null;

            CmsPost::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'user_id' => $author->id,
                    'cms_category_id' => $category?->id,
                    'title' => $item['title'],
                    'excerpt' => $item['excerpt'],
                    'content' => $item['content'],
                    'featured_image' => null, // Akan otomatis menggunakan placeholder di view jika null
                    'status' => $item['status'],
                    'published_at' => $item['published_at'],
                    'is_featured' => $item['is_featured'],
                    'allow_comments' => $item['allow_comments'],
                    'view_count' => $item['view_count'],
                    'meta_title' => $item['meta_title'],
                    'meta_description' => $item['meta_description'],
                    'meta_keywords' => $item['meta_keywords'],
                ]
            );
        }
    }
}