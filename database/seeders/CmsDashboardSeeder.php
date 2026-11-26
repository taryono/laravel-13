<?php

namespace Database\Seeders;

use App\Enums\PostStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Models\CmsBanner;
use Models\CmsCategory;
use Models\CmsMedia;
use Models\CmsPage;
use Models\CmsPost;
use Models\CmsSetting;

class CmsDashboardSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->value('id') ?? 1;

        // 1. Pastikan Kategori CMS Lengkap
        $categories = [
            'pengumuman' => CmsCategory::firstOrCreate(['slug' => 'pengumuman'], [
                'name' => 'Pengumuman',
                'description' => 'Informasi dan pengumuman resmi sekolah',
                'is_active' => true,
            ]),
            'prestasi-siswa' => CmsCategory::firstOrCreate(['slug' => 'prestasi-siswa'], [
                'name' => 'Prestasi Siswa',
                'description' => 'Catatan prestasi akademik dan non-akademik siswa',
                'is_active' => true,
            ]),
            'kegiatan-sekolah' => CmsCategory::firstOrCreate(['slug' => 'kegiatan-sekolah'], [
                'name' => 'Kegiatan Sekolah',
                'description' => 'Dokumentasi agenda dan kegiatan civitas sekolah',
                'is_active' => true,
            ]),
            'info-ppdb' => CmsCategory::firstOrCreate(['slug' => 'info-ppdb'], [
                'name' => 'Info PPDB',
                'description' => 'Informasi Penerimaan Peserta Didik Baru',
                'is_active' => true,
            ]),
            'artikel-pendidikan' => CmsCategory::firstOrCreate(['slug' => 'artikel-pendidikan'], [
                'name' => 'Artikel Pendidikan',
                'description' => 'Artikel dan opini edukatif dari guru dan pengamat',
                'is_active' => true,
            ]),
        ];

        // 2. Tambahkan Konten Draft & Pending Review di cms_posts
        $draftPosts = [
            [
                'cms_category_id' => $categories['info-ppdb']->id,
                'title' => 'Petunjuk Teknis dan Jadwal Seleksi PPDB Jalur Zonasi 2027',
                'slug' => 'petunjuk-teknis-seleksi-ppdb-zonasi-2027',
                'excerpt' => 'Rincian syarat radius wilayah tempat tinggal, batas zonasi kelurahan, dan verifikasi berkas KK.',
                'content' => '<p>Draf petunjuk teknis seleksi zonasi tahun 2027 memerlukan peninjauan kepala sekolah sebelum dipublikasikan ke portal publik.</p>',
                'status' => PostStatus::PENDING_REVIEW,
                'is_featured' => false,
                'allow_comments' => true,
                'view_count' => 0,
                'created_at' => Carbon::now()->subHours(2),
            ],
            [
                'cms_category_id' => $categories['pengumuman']->id,
                'title' => 'Jadwal Libur Semester Ganjil dan Agenda Classmeeting Siswa',
                'slug' => 'jadwal-libur-semester-ganjil-classmeeting-siswa',
                'excerpt' => 'Informasi kalender libur semester dan cabang perlombaan antar kelas (classmeeting).',
                'content' => '<p>Pengumuman ini merangkum rangkaian kegiatan perlombaan olahraga dan seni antar kelas pasca ujian semester.</p>',
                'status' => PostStatus::PENDING_REVIEW,
                'is_featured' => false,
                'allow_comments' => false,
                'view_count' => 0,
                'created_at' => Carbon::now()->subHours(5),
            ],
            [
                'cms_category_id' => $categories['kegiatan-sekolah']->id,
                'title' => 'Rencana Kunjungan Studi Kolaboratif ke Perguruan Tinggi Negeri',
                'slug' => 'rencana-kunjungan-studi-kolaboratif-ptn',
                'excerpt' => 'Draft rute dan susunan acara campus tour kelas XII angkatan 2027.',
                'content' => '<p>Rencana campus visit mencakup tiga universitas negeri terkemuka di wilayah Jawa Barat dan DIY.</p>',
                'status' => PostStatus::DRAFT,
                'is_featured' => false,
                'allow_comments' => true,
                'view_count' => 0,
                'created_at' => Carbon::now()->subDays(1),
            ],
        ];

        foreach ($draftPosts as $pData) {
            CmsPost::updateOrCreate(
                ['slug' => $pData['slug']],
                array_merge($pData, ['user_id' => $userId])
            );
        }

        // 3. Tambahkan Halaman Profil Sekolah di cms_pages
        $pages = [
            [
                'title' => 'Profil dan Sejarah Sekolah',
                'slug' => 'profil-sekolah',
                'content' => '<p>Sekolah Menengah Atas Negeri 1 didirikan dengan komitmen mencetak insan unggul yang beriman, bertakwa, dan berdaya saing global.</p>',
                'status' => 'published',
                'template' => 'default',
                'sort_order' => 1,
                'published_at' => Carbon::now()->subMonths(6),
            ],
            [
                'title' => 'Visi, Misi, dan Tujuan Satuan Pendidikan',
                'slug' => 'visi-misi',
                'content' => '<p><strong>Visi:</strong> Terwujudnya generasi cerdas, berkarakter profil pelajar Pancasila, dan berwawasan lingkungan.</p>',
                'status' => 'published',
                'template' => 'default',
                'sort_order' => 2,
                'published_at' => Carbon::now()->subMonths(6),
            ],
            [
                'title' => 'Struktur Organisasi dan Dewan Guru',
                'slug' => 'struktur-organisasi',
                'content' => '<p>Bagan struktur kepemimpinan sekolah yang dipimpin oleh Kepala Sekolah, Komite, dan para Wakil Kepala Sekolah.</p>',
                'status' => 'published',
                'template' => 'default',
                'sort_order' => 3,
                'published_at' => Carbon::now()->subMonths(5),
            ],
            [
                'title' => 'Fasilitas dan Sarana Prasarana Kampus',
                'slug' => 'fasilitas-kampus',
                'content' => '<p>Informasi sarana laboratorium sains, multimedia, perpustakaan digital, sarana olahraga, dan masjid sekolah.</p>',
                'status' => 'published',
                'template' => 'default',
                'sort_order' => 4,
                'published_at' => Carbon::now()->subMonths(4),
            ],
            [
                'title' => 'Program Ekstrakurikuler Unggulan',
                'slug' => 'program-ekstrakurikuler',
                'content' => '<p>Daftar 18 kegiatan ekstrakurikuler bidang sains, seni, keolahragaan, kepramukaan, dan keagamaan.</p>',
                'status' => 'published',
                'template' => 'default',
                'sort_order' => 5,
                'published_at' => Carbon::now()->subMonths(3),
            ],
        ];

        foreach ($pages as $pageData) {
            CmsPage::updateOrCreate(
                ['slug' => $pageData['slug']],
                array_merge($pageData, [
                    'user_id' => $userId,
                    'meta_title' => $pageData['title'],
                    'meta_description' => strip_tags($pageData['content']),
                ])
            );
        }

        // 4. Tambahkan Banner & Slider di cms_banners
        $banners = [
            [
                'title' => 'Penerimaan Peserta Didik Baru (PPDB) 2027/2028',
                'subtitle' => 'Pendaftaran online resmi dibuka untuk Jalur Prestasi dan Jalur Zonasi',
                'image' => 'cms/banners/ppdb-banner.jpg',
                'link_url' => '/berita/pembukaan-ppdb-tahun-ajaran-2027-2028',
                'button_text' => 'Daftar Sekarang',
                'position' => 'home',
                'sort_order' => 1,
                'is_active' => 1,
            ],
            [
                'title' => 'Selamat Datang di Portal Resmi Sekolah',
                'subtitle' => 'Mewujudkan lingkungan belajar inovatif, religius, dan berprestasi tingkat nasional',
                'image' => 'cms/banners/welcome-banner.jpg',
                'link_url' => '/profil-sekolah',
                'button_text' => 'Tentang Kami',
                'position' => 'home',
                'sort_order' => 2,
                'is_active' => 1,
            ],
            [
                'title' => 'Gelar Prestasi Siswa Olimpiade Sains & Seni',
                'subtitle' => 'Apresiasi karya dan medali kejuaraan tingkat kota, provinsi, dan nasional',
                'image' => 'cms/banners/prestasi-banner.jpg',
                'link_url' => '/berita/tim-olimpiade-sains-raih-medali-emas-nasional',
                'button_text' => 'Lihat Prestasi',
                'position' => 'home',
                'sort_order' => 3,
                'is_active' => 1,
            ],
        ];

        foreach ($banners as $bData) {
            CmsBanner::updateOrCreate(
                ['title' => $bData['title']],
                $bData
            );
        }

        // 5. Tambahkan Pengaturan CMS di cms_settings
        $settings = [
            ['group' => 'general', 'key' => 'site_status', 'value' => 'Online', 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_title', 'value' => 'SMA Negeri 1 Teladan', 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Cerdas, Berkarakter, Berdaya Saing Global', 'type' => 'text'],
            ['group' => 'theme', 'key' => 'theme_primary_color', 'value' => '#006A61', 'type' => 'color'],
            ['group' => 'seo', 'key' => 'meta_description', 'value' => 'Website resmi portal informasi akademik, berita, dan kegiatan sekolah.', 'type' => 'textarea'],
            ['group' => 'system', 'key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean'],
        ];

        foreach ($settings as $sData) {
            CmsSetting::updateOrCreate(
                ['key' => $sData['key']],
                $sData
            );
        }

        // 6. Tambahkan Statistik Pengunjung di cms_visitor_stats (Bulan Ini & Bulan Lalu)
        if (Schema::hasTable('cms_visitor_stats')) {
            $now = Carbon::now();

            // Masukkan data 30 hari bulan lalu
            for ($i = 1; $i <= 30; $i++) {
                $date = $now->copy()->subMonth()->startOfMonth()->addDays($i - 1)->format('Y-m-d');
                $views = rand(450, 650);
                $visitors = (int) round($views * 0.65);

                DB::table('cms_visitor_stats')->updateOrInsert(
                    ['date' => $date],
                    ['page_views' => $views, 'unique_visitors' => $visitors]
                );
            }

            // Masukkan data bulan berjalan sampai hari ini
            $daysInCurrent = min($now->day, 28);
            for ($i = 1; $i <= $daysInCurrent; $i++) {
                $date = $now->copy()->startOfMonth()->addDays($i - 1)->format('Y-m-d');
                // Bobot agar total berkisar ~18.420 pengunjung/views
                $views = rand(600, 850);
                $visitors = (int) round($views * 0.7);

                DB::table('cms_visitor_stats')->updateOrInsert(
                    ['date' => $date],
                    ['page_views' => $views, 'unique_visitors' => $visitors]
                );
            }
        }

        // 7. Tambahkan Dokumen & Unduhan di cms_media
        $documents = [
            [
                'name' => 'Formulir Pendaftaran Ekstrakurikuler',
                'file_name' => 'formulir-ekstrakurikuler-2026.pdf',
                'path' => 'cms/documents/formulir-ekstrakurikuler-2026.pdf',
                'mime_type' => 'application/pdf',
                'size' => 1258291, // ~1.2 MB
                'document_category' => 'Formulir',
                'download_count' => 415,
            ],
            [
                'name' => 'Brosur Profil dan Program Sekolah 2026/2027',
                'file_name' => 'brosur-profil-sekolah.pdf',
                'path' => 'cms/documents/brosur-profil-sekolah.pdf',
                'mime_type' => 'application/pdf',
                'size' => 4718592, // ~4.5 MB
                'document_category' => 'Brosur',
                'download_count' => 892,
            ],
            [
                'name' => 'Buku Panduan Pembelajaran Kurikulum Merdeka',
                'file_name' => 'panduan-kurikulum-merdeka.pdf',
                'path' => 'cms/documents/panduan-kurikulum-merdeka.pdf',
                'mime_type' => 'application/pdf',
                'size' => 3145728, // ~3.0 MB
                'document_category' => 'Panduan',
                'download_count' => 620,
            ],
            [
                'name' => 'Petunjuk Teknis Asesmen Sumatif Akhir Semester',
                'file_name' => 'juknis-asesmen-semester.pdf',
                'path' => 'cms/documents/juknis-asesmen-semester.pdf',
                'mime_type' => 'application/pdf',
                'size' => 1887436, // ~1.8 MB
                'document_category' => 'Panduan',
                'download_count' => 350,
            ],
        ];

        foreach ($documents as $doc) {
            CmsMedia::withTrashed()->updateOrCreate(
                ['path' => $doc['path']],
                array_merge($doc, [
                    'user_id' => $userId,
                    'disk' => 'public',
                ])
            );
        }

        // 8. Tambahkan Prestasi Siswa di achievements jika ada student
        $studentId = DB::table('students')->value('id');
        if ($studentId && Schema::hasTable('achievements')) {
            $achievements = [
                [
                    'student_id' => $studentId,
                    'achievement_type' => 'academic',
                    'title' => 'Juara 1 Olimpiade Sains Nasional (OSN) Matematika',
                    'description' => 'Meraih medali emas pada kompetisi tingkat nasional tahun 2026',
                    'level' => 'national',
                    'date_achieved' => Carbon::now()->subMonths(2)->format('Y-m-d'),
                    'is_verified' => 1,
                    'is_active' => 1,
                ],
                [
                    'student_id' => $studentId,
                    'achievement_type' => 'non_academic',
                    'title' => 'Juara Umum Festival Seni Tari Pelajar Tingkat Provinsi',
                    'description' => 'Membawakan tari kreasi nusantara dengan apresiasi karya terbaik',
                    'level' => 'provincial',
                    'date_achieved' => Carbon::now()->subMonths(3)->format('Y-m-d'),
                    'is_verified' => 1,
                    'is_active' => 1,
                ],
                [
                    'student_id' => $studentId,
                    'achievement_type' => 'academic',
                    'title' => 'Medali Perak Lomba Karya Tulis Ilmiah Remaja (LKIR)',
                    'description' => 'Riset inovasi pengolahan limbah organik ramah lingkungan',
                    'level' => 'national',
                    'date_achieved' => Carbon::now()->subMonths(4)->format('Y-m-d'),
                    'is_verified' => 1,
                    'is_active' => 1,
                ],
            ];

            foreach ($achievements as $ach) {
                DB::table('achievements')->updateOrInsert(
                    ['title' => $ach['title']],
                    array_merge($ach, [
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ])
                );
            }
        }
    }
}
