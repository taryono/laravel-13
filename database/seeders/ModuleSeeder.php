<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        // 1. Modul Utama / Paket Unggulan (Core Featured Modules)
        $coreModules = [
            [
                'code' => 'akademik',
                'name' => 'Akademik & Kurikulum Terpadu',
                'category' => 'Akademik & Rapor',
                'description' => 'Kurikulum Merdeka/K13, jadwal pelajaran, pembagian kelas, e-Learning, dan cetak rapor digital.',
                'icon' => 'fa-solid fa-graduation-cap',
                'price' => 150000,
                'is_active' => true,
                'organization_id' => 1,
            ],
            [
                'code' => 'kesiswaan',
                'name' => 'Kesiswaan & PPDB Online',
                'category' => 'Kesiswaan & Alumni',
                'description' => 'Penerimaan Peserta Didik Baru (PPDB), database biodata siswa, rekam prestasi, bimbingan konseling & alumni.',
                'icon' => 'fa-solid fa-user-group',
                'price' => 125000,
                'is_active' => true,
                'organization_id' => 1,
            ],
            [
                'code' => 'keuangan',
                'name' => 'Keuangan, SPP & Kasir Online',
                'category' => 'Keuangan & Akuntansi',
                'description' => 'Tagihan SPP otomatis, pembayaran QRIS/VA otomatis, pencatatan kas/bank, laporan BOS & RKAS.',
                'icon' => 'fa-solid fa-wallet',
                'price' => 175000,
                'is_active' => true,
                'organization_id' => 1,
            ],
            [
                'code' => 'absensi',
                'name' => 'Presensi Siswa & Pegawai Modern',
                'category' => 'Kepegawaian & HRD',
                'description' => 'Presensi QR Code, RFID scanner, Face Recognition, geolokasi mobile, serta integrasi notifikasi WhatsApp wali murid.',
                'icon' => 'fa-solid fa-calendar-check',
                'price' => 95000,
                'is_active' => true,
                'organization_id' => 1,
            ],
            [
                'code' => 'cbt',
                'name' => 'CBT & Bank Soal Online',
                'category' => 'Ujian & CBT',
                'description' => 'Ujian berbasis komputer/ponsel, acak soal & opsi, timer anti-curang, penilaian instan & analisis butir butir soal.',
                'icon' => 'fa-solid fa-laptop-file',
                'price' => 120000,
                'is_active' => true,
                'organization_id' => 1,
            ],
            [
                'code' => 'perpustakaan',
                'name' => 'Perpustakaan Digital (e-Library)',
                'category' => 'Perpustakaan (Library)',
                'description' => 'Katalog buku OPAC, cetak barcode buku/kartu anggota, sirkulasi peminjaman, denda otomatis & koleksi e-Book.',
                'icon' => 'fa-solid fa-book',
                'price' => 75000,
                'is_active' => true,
                'organization_id' => 1,
            ],
            [
                'code' => 'pesantren',
                'name' => 'Kepesantrenan & Manajemen Asrama',
                'category' => 'Pesantren & Asrama',
                'description' => 'Manajemen kamar santri, setoran tahfidz Al-Quran, rekam pelanggaran/kedisiplinan, dan perizinan keluar pondok.',
                'icon' => 'fa-solid fa-mosque',
                'price' => 110000,
                'is_active' => true,
                'organization_id' => 1,
            ],
            [
                'code' => 'kepegawaian',
                'name' => 'Kepegawaian & Penggajian (HR & Payroll)',
                'category' => 'Kepegawaian & HRD',
                'description' => 'Data pokok guru/tendik, riwayat kepangkatan, penghitungan jam mengajar, slip gaji digital & perhitungan PPh21.',
                'icon' => 'fa-solid fa-id-badge',
                'price' => 135000,
                'is_active' => true,
                'organization_id' => 1,
            ],
            [
                'code' => 'parent_portal',
                'name' => 'Parent Portal & Siswa Mobile',
                'category' => 'E-Learning & Media',
                'description' => 'Aplikasi dashboard orang tua untuk memantau nilai anak, kehadiran harian, pengumuman sekolah, dan riwayat bayar.',
                'icon' => 'fa-solid fa-people-roof',
                'price' => 85000,
                'is_active' => true,
                'organization_id' => 1,
            ],
            [
                'code' => 'analitik_ai',
                'name' => 'Analitik AI & Prediksi Akademik',
                'category' => 'Akademik & Rapor',
                'description' => 'Sistem peringatan dini (early warning system), prediksi kelulusan, dan rekomendasi minat bakat berbasis AI.',
                'icon' => 'fa-solid fa-chart-line',
                'price' => 160000,
                'is_active' => true,
                'organization_id' => 1,
            ],
            [
                'code' => 'sarpras',
                'name' => 'Sarana Prasarana & Aset Inventaris',
                'category' => 'Inventaris & Sarpras',
                'description' => 'Inventarisasi aset ruangan, label QR Code fisik barang, jadwal perawatan berkala, mutasi & pencatatan penyusutan.',
                'icon' => 'fa-solid fa-boxes-stacked',
                'price' => 90000,
                'is_active' => true,
                'organization_id' => 1,
            ],
            [
                'code' => 'layanan_kesehatan',
                'name' => 'Klinik Sekolah & UKS Terpadu',
                'category' => 'Fasilitas & Layanan',
                'description' => 'Rekam medis siswa, catatan alergi/imunisasi, inventaris obat-obatan UKS, dan rujukan fasilitas kesehatan.',
                'icon' => 'fa-solid fa-hospital',
                'price' => 50000,
                'is_active' => true,
                'organization_id' => 1,
            ],
        ];

        // 2. Baca daftar modul lengkap dari docs/modules_list.md
        $docPath = base_path('docs/modules_list.md');
        $docModules = [];

        if (file_exists($docPath)) {
            $content = file_get_contents($docPath);
            $lines = explode("\n", $content);

            $categoryMap = [
                1 => ['name' => 'Keuangan & Akuntansi', 'icon' => 'fa-solid fa-wallet', 'base_price' => 75000],
                2 => ['name' => 'Akademik & Rapor', 'icon' => 'fa-solid fa-graduation-cap', 'base_price' => 85000],
                3 => ['name' => 'Kesiswaan & Alumni', 'icon' => 'fa-solid fa-user-group', 'base_price' => 65000],
                4 => ['name' => 'Kepegawaian & HRD', 'icon' => 'fa-solid fa-id-badge', 'base_price' => 60000],
                5 => ['name' => 'Perpustakaan (Library)', 'icon' => 'fa-solid fa-book', 'base_price' => 45000],
                6 => ['name' => 'Pesantren & Asrama', 'icon' => 'fa-solid fa-mosque', 'base_price' => 70000],
                7 => ['name' => 'Inventaris & Sarpras', 'icon' => 'fa-solid fa-boxes-stacked', 'base_price' => 55000],
                8 => ['name' => 'Ujian & CBT', 'icon' => 'fa-solid fa-laptop-file', 'base_price' => 90000],
                9 => ['name' => 'Fasilitas & Layanan', 'icon' => 'fa-solid fa-hospital', 'base_price' => 40000],
                10 => ['name' => 'E-Learning & Media', 'icon' => 'fa-solid fa-comments', 'base_price' => 50000],
                11 => ['name' => 'Workflow & Otomasi', 'icon' => 'fa-solid fa-network-wired', 'base_price' => 45000],
                12 => ['name' => 'Sistem & Keamanan', 'icon' => 'fa-solid fa-shield-halved', 'base_price' => 50000],
            ];

            $catNum = 0;
            $currentCategory = 'Umum';

            foreach ($lines as $line) {
                $line = trim($line);
                if (preg_match('/^##\s+(\d+)\.\s*([^(\n\r]+)/', $line, $m)) {
                    $catNum = (int)$m[1];
                    $currentCategory = $categoryMap[$catNum]['name'] ?? trim($m[2]);
                    continue;
                }

                // Abaikan kategori pivot (13) & internal laravel (14)
                if ($catNum >= 13) {
                    continue;
                }

                if (preg_match('/^\|\s*\d+\s*\|\s*`([^`]+)`\s*\|\s*\[`([^`]+)`\]\([^)]+\)\s*\|\s*([^|]+)\|\s*([^|]+)\|/', $line, $matches)) {
                    $table = trim($matches[1]);
                    $studly = trim($matches[2]);
                    $desc = trim($matches[4]);

                    $humanName = trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $studly));
                    $catInfo = $categoryMap[$catNum] ?? ['icon' => 'fa-solid fa-cube', 'base_price' => 50000, 'name' => $currentCategory];

                    // Variasi harga dummy yang rapi: kelipatan 5.000 antara 35.000 - 145.000
                    $offset = (abs(crc32($table)) % 10) * 10000;
                    $price = max(35000, $catInfo['base_price'] + $offset - 20000);

                    $docModules[] = [
                        'organization_id' => 1,
                        'code' => $table,
                        'name' => $humanName,
                        'category' => $catInfo['name'],
                        'description' => $desc,
                        'icon' => $catInfo['icon'],
                        'price' => $price,
                        'is_active' => true,
                    ];
                }
            }
        }

        // Gabungkan modul utama & modul dari dokumen
        $allModules = array_merge($coreModules, $docModules);
        $totalSeeded = 0;

        foreach (array_chunk($allModules, 100) as $chunk) {
            $rows = array_map(function ($item) use ($now) {
                return array_merge($item, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }, $chunk);

            DB::table('modules')->upsert(
                $rows,
                ['code'],
                ['name', 'category', 'description', 'icon', 'price', 'is_active', 'updated_at']
            );

            $totalSeeded += count($rows);
        }

        $this->command->info("Berhasil melakukan seeding {$totalSeeded} modul ke dalam tabel 'modules'.");
    }
}
