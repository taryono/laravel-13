<?php

namespace Database\Seeders;

use Models\Facility;
use Models\FacilityCategory;
use Models\FacilityImage;
use Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil organization & user pertama sebagai default (sesuaikan jika multi-tenant)
        $organization = Organization::first();
        $user = User::first();

        if (! $organization) {
            $this->command->warn('Data Organization tidak ditemukan. Seeder dibatalkan.');
            return;
        }

        $orgId = $organization->id;
        $userId = $user?->id;

        DB::transaction(function () use ($orgId, $userId) {
            // 1. Pastikan kategori fasilitas tersedia
            $categories = [
                'LAB' => FacilityCategory::updateOrCreate(
                    ['organization_id' => $orgId, 'code' => 'CAT-LAB'],
                    [
                        'name' => 'Laboratorium & Teknologi',
                        'description' => 'Fasilitas praktikum komputer, sains, dan teknologi informasi.',
                        'is_active' => true,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ]
                ),
                'LIB' => FacilityCategory::updateOrCreate(
                    ['organization_id' => $orgId, 'code' => 'CAT-LIB'],
                    [
                        'name' => 'Perpustakaan & Literasi',
                        'description' => 'Pusat sumber belajar, koleksi buku fisik, dan akses literasi digital.',
                        'is_active' => true,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ]
                ),
                'PUB' => FacilityCategory::updateOrCreate(
                    ['organization_id' => $orgId, 'code' => 'CAT-PUB'],
                    [
                        'name' => 'Fasilitas Umum & Penunjang',
                        'description' => 'Sarana pendukung aktivitas harian siswa di lingkungan sekolah.',
                        'is_active' => true,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ]
                ),
                'SPT' => FacilityCategory::updateOrCreate(
                    ['organization_id' => $orgId, 'code' => 'CAT-SPT'],
                    [
                        'name' => 'Olahraga & Seni',
                        'description' => 'Sarana pengembangan bakat olahraga dan kegiatan ekstrakurikuler.',
                        'is_active' => true,
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ]
                ),
            ];

            // 2. Data Fasilitas Unggulan sesuai tampilan Website
            $facilitiesData = [
                [
                    'code' => 'FAC-LAB-01',
                    'category_key' => 'LAB',
                    'room_id' => null, // Set null atau sesuaikan dengan ID dari tabel rooms
                    'name' => 'Lab Komputer & CBT',
                    'description' => '120 unit PC berkecepatan tinggi dengan koneksi fiber optik.',
                    'quantity' => 120,
                    'is_available' => true,
                    'is_active' => true,
                    'is_featured' => true,
                    'sort_order' => 1,
                    'images' => [
                        [
                            'file_path' => 'facilities/lab-komputer-main.jpg',
                            'caption' => 'Ruang utama Lab Komputer & CBT berkapasitas 120 siswa',
                            'is_primary' => true,
                            'sort_order' => 1,
                        ],
                        [
                            'file_path' => 'facilities/lab-komputer-server.jpg',
                            'caption' => 'Perangkat PC spesifikasi tinggi dengan jaringan fiber optik',
                            'is_primary' => false,
                            'sort_order' => 2,
                        ],
                        [
                            'file_path' => 'facilities/lab-komputer-cbt.jpg',
                            'caption' => 'Suasana pelaksanaan ujian berbasis komputer (CBT)',
                            'is_primary' => false,
                            'sort_order' => 3,
                        ],
                    ],
                ],
                [
                    'code' => 'FAC-LIB-01',
                    'category_key' => 'LIB',
                    'room_id' => null,
                    'name' => 'Perpustakaan Digital',
                    'description' => 'Koleksi 15.000+ buku fisik dan akses e-book nasional.',
                    'quantity' => 1,
                    'is_available' => true,
                    'is_active' => true,
                    'is_featured' => true,
                    'sort_order' => 2,
                    'images' => [
                        [
                            'file_path' => 'facilities/perpustakaan-main.jpg',
                            'caption' => 'Area baca modern Perpustakaan Digital',
                            'is_primary' => true,
                            'sort_order' => 1,
                        ],
                        [
                            'file_path' => 'facilities/perpustakaan-kiosk.jpg',
                            'caption' => 'Kiosk pencarian katalog dan akses e-book nasional',
                            'is_primary' => false,
                            'sort_order' => 2,
                        ],
                        [
                            'file_path' => 'facilities/perpustakaan-pod.jpg',
                            'caption' => 'Reading pod nyaman untuk diskusi kelompok',
                            'is_primary' => false,
                            'sort_order' => 3,
                        ],
                    ],
                ],
                [
                    'code' => 'FAC-CNT-01',
                    'category_key' => 'PUB',
                    'room_id' => null,
                    'name' => 'Smart Canteen Cashless',
                    'description' => 'Kantin sehat higienis terintegrasi kartu pelajar EduPortal.',
                    'quantity' => 1,
                    'is_available' => true,
                    'is_active' => true,
                    'is_featured' => true,
                    'sort_order' => 3,
                    'images' => [
                        [
                            'file_path' => 'facilities/smart-canteen-main.jpg',
                            'caption' => 'Area makan bersih dan nyaman di Smart Canteen',
                            'is_primary' => true,
                            'sort_order' => 1,
                        ],
                        [
                            'file_path' => 'facilities/smart-canteen-tap.jpg',
                            'caption' => 'Transaksi non-tunai menggunakan kartu pelajar EduPortal',
                            'is_primary' => false,
                            'sort_order' => 2,
                        ],
                    ],
                ],
                [
                    'code' => 'FAC-SPT-01',
                    'category_key' => 'SPT',
                    'room_id' => null,
                    'name' => 'Lapangan Olahraga Indoor',
                    'description' => 'Arena standar nasional untuk basket, futsal, dan bulu tangkis.',
                    'quantity' => 1,
                    'is_available' => true,
                    'is_active' => true,
                    'is_featured' => true,
                    'sort_order' => 4,
                    'images' => [
                        [
                            'file_path' => 'facilities/lapangan-indoor-main.jpg',
                            'caption' => 'Lapangan olahraga indoor multi-fungsi dengan tribun penonton',
                            'is_primary' => true,
                            'sort_order' => 1,
                        ],
                        [
                            'file_path' => 'facilities/lapangan-indoor-basket.jpg',
                            'caption' => 'Area lapangan basket dengan lantai standar kompetisi',
                            'is_primary' => false,
                            'sort_order' => 2,
                        ],
                    ],
                ],
            ];

            // 3. Simpan ke tabel facilities & facility_images
            foreach ($facilitiesData as $item) {
                $images = $item['images'];
                $categoryId = $categories[$item['category_key']]->id;

                $facility = Facility::updateOrCreate(
                    [
                        'organization_id' => $orgId,
                        'code' => $item['code'],
                    ],
                    [
                        'facility_category_id' => $categoryId,
                        'room_id' => $item['room_id'],
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'quantity' => $item['quantity'],
                        'is_available' => $item['is_available'],
                        'is_active' => $item['is_active'],
                        'is_featured' => $item['is_featured'],
                        'sort_order' => $item['sort_order'],
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ]
                );

                // Bersihkan foto lama jika seeder dijalankan ulang agar tidak duplikat
                $facility->images()->forceDelete();

                foreach ($images as $img) {
                    FacilityImage::create([
                        'organization_id' => $orgId,
                        'facility_id' => $facility->id,
                        'file_path' => $img['file_path'],
                        'caption' => $img['caption'],
                        'is_primary' => $img['is_primary'],
                        'sort_order' => $img['sort_order'],
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ]);
                }
            }
        });

        $this->command->info('Seeder Facility & FacilityImage berhasil dijalankan.');
    }
}