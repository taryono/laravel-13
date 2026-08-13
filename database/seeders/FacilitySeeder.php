<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [];

        // =============== RUANG KELAS SD (room_id 1-6) ===============
        for ($i = 1; $i <= 6; $i++) {
            // Fasilitas dasar untuk setiap kelas
            $facilities[] = [
                'facility_category_id' => 2, // Fasilitas Penunjang
                'room_id' => $i,
                'code' => 'FAS-KLS-SD-' . $i . '-001',
                'name' => 'Whiteboard',
                'description' => 'Whiteboard ukuran 120x200 cm',
                'is_active' => 1,
                'quantity' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $facilities[] = [
                'facility_category_id' => 2,
                'room_id' => $i,
                'code' => 'FAS-KLS-SD-' . $i . '-002',
                'name' => 'Proyektor',
                'description' => 'Proyektor LCD 4000 lumen',
                'is_active' => 1,
                'quantity' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $facilities[] = [
                'facility_category_id' => 2,
                'room_id' => $i,
                'code' => 'FAS-KLS-SD-' . $i . '-003',
                'name' => 'AC',
                'description' => 'AC Split 2 PK',
                'is_active' => 1,
                'quantity' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $facilities[] = [
                'facility_category_id' => 2,
                'room_id' => $i,
                'code' => 'FAS-KLS-SD-' . $i . '-004',
                'name' => 'Meja Siswa',
                'description' => 'Meja siswa kayu ukuran 60x40 cm',
                'is_active' => 1,
                'quantity' => 32,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $facilities[] = [
                'facility_category_id' => 2,
                'room_id' => $i,
                'code' => 'FAS-KLS-SD-' . $i . '-005',
                'name' => 'Kursi Siswa',
                'description' => 'Kursi siswa plastik',
                'is_active' => 1,
                'quantity' => 32,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // =============== LABORATORIUM IPA (room_id = 7) ===============
        $facilities[] = [
            'facility_category_id' => 1, // Sarana Pendidikan Inti
            'room_id' => 7,
            'code' => 'FAS-LAB-IPA-001',
            'name' => 'Mikroskop',
            'description' => 'Mikroskop binokuler',
            'is_active' => 1,
            'quantity' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $facilities[] = [
            'facility_category_id' => 1,
            'room_id' => 7,
            'code' => 'FAS-LAB-IPA-002',
            'name' => 'Meja Laboratorium',
            'description' => 'Meja laboratorium tahan asam',
            'is_active' => 1,
            'quantity' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $facilities[] = [
            'facility_category_id' => 1,
            'room_id' => 7,
            'code' => 'FAS-LAB-IPA-003',
            'name' => 'Alat Peraga IPA',
            'description' => 'Set alat peraga fisika dan biologi',
            'is_active' => 1,
            'quantity' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // =============== LABORATORIUM KOMPUTER (room_id = 8) ===============
        $facilities[] = [
            'facility_category_id' => 1,
            'room_id' => 8,
            'code' => 'FAS-LAB-KOM-001',
            'name' => 'Komputer PC',
            'description' => 'PC Core i5, RAM 8GB, SSD 256GB',
            'is_active' => 1,
            'quantity' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $facilities[] = [
            'facility_category_id' => 1,
            'room_id' => 8,
            'code' => 'FAS-LAB-KOM-002',
            'name' => 'Meja Komputer',
            'description' => 'Meja komputer dengan rak CPU',
            'is_active' => 1,
            'quantity' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $facilities[] = [
            'facility_category_id' => 1,
            'room_id' => 8,
            'code' => 'FAS-LAB-KOM-003',
            'name' => 'Proyektor',
            'description' => 'Proyektor LCD untuk presentasi',
            'is_active' => 1,
            'quantity' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // =============== RUANG GURU (room_id = 9) ===============
        $facilities[] = [
            'facility_category_id' => 2,
            'room_id' => 9,
            'code' => 'FAS-RG-001',
            'name' => 'Meja Guru',
            'description' => 'Meja kerja guru ukuran 120x60 cm',
            'is_active' => 1,
            'quantity' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $facilities[] = [
            'facility_category_id' => 2,
            'room_id' => 9,
            'code' => 'FAS-RG-002',
            'name' => 'Kursi Guru',
            'description' => 'Kursi kerja ergonomis',
            'is_active' => 1,
            'quantity' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $facilities[] = [
            'facility_category_id' => 2,
            'room_id' => 9,
            'code' => 'FAS-RG-003',
            'name' => 'Lemari Arsip',
            'description' => 'Lemari arsip 4 laci',
            'is_active' => 1,
            'quantity' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $facilities[] = [
            'facility_category_id' => 3, // Sarana Umum & Operasional
            'room_id' => 9,
            'code' => 'FAS-RG-004',
            'name' => 'Dispenser Air',
            'description' => 'Dispenser air minum',
            'is_active' => 1,
            'quantity' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // =============== RUANG KEPALA SEKOLAH (room_id = 10) ===============
        $facilities[] = [
            'facility_category_id' => 2,
            'room_id' => 10,
            'code' => 'FAS-KS-001',
            'name' => 'Meja Kerja',
            'description' => 'Meja kerja eksekutif',
            'is_active' => 1,
            'quantity' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $facilities[] = [
            'facility_category_id' => 2,
            'room_id' => 10,
            'code' => 'FAS-KS-002',
            'name' => 'Kursi Kerja',
            'description' => 'Kursi kerja eksekutif',
            'is_active' => 1,
            'quantity' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $facilities[] = [
            'facility_category_id' => 2,
            'room_id' => 10,
            'code' => 'FAS-KS-003',
            'name' => 'Komputer PC',
            'description' => 'PC Core i7 untuk administrasi',
            'is_active' => 1,
            'quantity' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $facilities[] = [
            'facility_category_id' => 2,
            'room_id' => 10,
            'code' => 'FAS-KS-004',
            'name' => 'Lemari Arsip',
            'description' => 'Lemari arsip 4 laci',
            'is_active' => 1,
            'quantity' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // =============== PERPUSTAKAAN (room_id = 27-30) ===============
        $facilities[] = [
            'facility_category_id' => 1,
            'room_id' => 27, // Ruang Baca Umum
            'code' => 'FAS-PUST-001',
            'name' => 'Rak Buku',
            'description' => 'Rak buku besi 5 susun',
            'is_active' => 1,
            'quantity' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $facilities[] = [
            'facility_category_id' => 1,
            'room_id' => 27,
            'code' => 'FAS-PUST-002',
            'name' => 'Meja Baca',
            'description' => 'Meja baca panjang',
            'is_active' => 1,
            'quantity' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $facilities[] = [
            'facility_category_id' => 1,
            'room_id' => 27,
            'code' => 'FAS-PUST-003',
            'name' => 'Kursi Baca',
            'description' => 'Kursi baca',
            'is_active' => 1,
            'quantity' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $facilities[] = [
            'facility_category_id' => 2,
            'room_id' => 29, // Digital Library
            'code' => 'FAS-PUST-004',
            'name' => 'Komputer PC',
            'description' => 'PC untuk akses digital library',
            'is_active' => 1,
            'quantity' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // =============== FASILITAS UMUM ===============
        // Kantin (jika ada ruangan khusus)
        $facilities[] = [
            'facility_category_id' => 2,
            'room_id' => 1, // asumsikan di lantai 1
            'code' => 'FAS-UMUM-001',
            'name' => 'Kantin',
            'description' => 'Area kantin dengan meja dan kursi',
            'is_active' => 1,
            'quantity' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Masjid/Musholla
        $facilities[] = [
            'facility_category_id' => 2,
            'room_id' => 1,
            'code' => 'FAS-UMUM-002',
            'name' => 'Musholla',
            'description' => 'Tempat ibadah',
            'is_active' => 1,
            'quantity' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Area Parkir
        $facilities[] = [
            'facility_category_id' => 3,
            'room_id' => 1,
            'code' => 'FAS-UMUM-003',
            'name' => 'Area Parkir',
            'description' => 'Area parkir mobil dan motor',
            'is_active' => 1,
            'quantity' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('facilities')->insert($facilities);
    }
}