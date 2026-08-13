<?php

namespace Database\Seeders;

use Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run()
    { 
        $packages = [
            [
                'code' => 'starter',
                'name' => 'Paket Starter',
                'price' => 300000,
                'description' => 'Solusi tepat untuk sekolah yang baru memulai digitalisasi. Nikmati fitur dasar pengelolaan data siswa, guru, dan jadwal pelajaran dengan antarmuka yang mudah digunakan. Cocok untuk sekolah dengan jumlah siswa hingga 500.',
                'features' => json_encode([
                    'Manajemen Data Siswa (maks. 500)',
                    'Manajemen Data Guru',
                    'Jadwal Pelajaran Digital',
                    'Absensi Siswa',
                    'Laporan Dasar',
                    'Customer Support via Email'
                ])
            ],
            [
                'code' => 'professional',
                'name' => 'Paket Profesional',
                'price' => 700000,
                'description' => 'Tingkatkan efisiensi manajemen sekolah dengan fitur lengkap yang terintegrasi. Dapatkan akses ke modul keuangan, perpustakaan, dan sistem penilaian yang canggih. Ideal untuk sekolah menengah dengan kebutuhan administrasi yang lebih kompleks.',
                'features' => json_encode([
                    'Semua Fitur Paket Starter',
                    'Manajemen Data Siswa (tanpa batas)',
                    'Modul Keuangan & Pembayaran',
                    'Sistem Penilaian & Raport',
                    'Manajemen Perpustakaan',
                    'Modul Ekstrakurikuler',
                    'Notifikasi & Pengingat Otomatis',
                    'Customer Support Prioritas (WhatsApp)'
                ])
            ],
            [
                'code' => 'enterprise',
                'name' => 'Paket Enterprise',
                'price' => 0, // Harga khusus
                'description' => 'Solusi komprehensif untuk institusi pendidikan berskala besar dengan kebutuhan spesifik. Dapatkan semua fitur premium, integrasi dengan sistem yang sudah ada, dan pengembangan kustom sesuai kebutuhan. Tersedia dedicated support dan pelatihan untuk staf.',
                'features' => json_encode([
                    'Semua Fitur Paket Profesional',
                    'Integrasi dengan Aplikasi Pihak Ketiga',
                    'Kustomisasi Fitur Sesuai Kebutuhan',
                    'API Akses untuk Pengembangan Lanjutan',
                    'Multi-Cabang / Multi-Yayasan',
                    'Sistem Manajemen Aset & Inventaris',
                    'Dashboard Analitik Lanjutan',
                    'Dedicated Account Manager',
                    'Pelatihan & Onboarding Tim',
                    'Support 24/7 via Telepon & WhatsApp'
                ])
            ],
        ];

        foreach ($packages as $package) {
            Package::firstOrCreate(
                ['code' => $package['code']],
                $package
            );
        }
    }
}