<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [];

        // =============== GEDUNG A (GED-A) ===============
        // Lantai 1 (GED-A-01) - Kelas SD
        for ($i = 1; $i <= 6; $i++) {
            $rooms[] = [
                'floor_id' => 1, // GED-A-01
                'code' => 'GED-A-01-10' . $i,
                'name' => 'Ruang Kelas SD ' . $i,
                'room_number' => '10' . $i,
                'capacity' => 32,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Lantai 2 (GED-A-02) - Kelas SMP
        for ($i = 1; $i <= 6; $i++) {
            $rooms[] = [
                'floor_id' => 2, // GED-A-02
                'code' => 'GED-A-02-20' . $i,
                'name' => 'Ruang Kelas SMP ' . $i,
                'room_number' => '20' . $i,
                'capacity' => 32,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Lantai 3 (GED-A-03) - Ruang Pendukung
        $rooms[] = [
            'floor_id' => 3, // GED-A-03
            'code' => 'GED-A-03-301',
            'name' => 'Laboratorium IPA',
            'room_number' => '301',
            'capacity' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 3,
            'code' => 'GED-A-03-302',
            'name' => 'Laboratorium Komputer',
            'room_number' => '302',
            'capacity' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 3,
            'code' => 'GED-A-03-303',
            'name' => 'Ruang Guru',
            'room_number' => '303',
            'capacity' => 40,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 3,
            'code' => 'GED-A-03-304',
            'name' => 'Ruang Kepala Sekolah',
            'room_number' => '304',
            'capacity' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Lantai 4 (GED-A-04) - Ruang Pendukung
        $rooms[] = [
            'floor_id' => 4, // GED-A-04
            'code' => 'GED-A-04-401',
            'name' => 'Ruang BK/Konseling',
            'room_number' => '401',
            'capacity' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 4,
            'code' => 'GED-A-04-402',
            'name' => 'Ruang OSIS',
            'room_number' => '402',
            'capacity' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 4,
            'code' => 'GED-A-04-403',
            'name' => 'Ruang UKS',
            'room_number' => '403',
            'capacity' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 4,
            'code' => 'GED-A-04-404',
            'name' => 'Ruang Audio Visual',
            'room_number' => '404',
            'capacity' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // =============== GEDUNG B (GED-B) - TK/PAUD ===============
        // Lantai 1 (GED-B-01)
        for ($i = 1; $i <= 4; $i++) {
            $rooms[] = [
                'floor_id' => 5, // GED-B-01
                'code' => 'GED-B-01-10' . $i,
                'name' => 'Ruang Kelas TK ' . $i,
                'room_number' => '10' . $i,
                'capacity' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $rooms[] = [
            'floor_id' => 5,
            'code' => 'GED-B-01-105',
            'name' => 'Ruang Bermain Indoor',
            'room_number' => '105',
            'capacity' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Lantai 2 (GED-B-02)
        for ($i = 1; $i <= 4; $i++) {
            $rooms[] = [
                'floor_id' => 6, // GED-B-02
                'code' => 'GED-B-02-20' . $i,
                'name' => 'Ruang Kelas PAUD ' . $i,
                'room_number' => '20' . $i,
                'capacity' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $rooms[] = [
            'floor_id' => 6,
            'code' => 'GED-B-02-205',
            'name' => 'Ruang Guru TK/PAUD',
            'room_number' => '205',
            'capacity' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // =============== GEDUNG C (GED-C) - SMA/SMK ===============
        // Lantai 1 (GED-C-01)
        for ($i = 1; $i <= 4; $i++) {
            $rooms[] = [
                'floor_id' => 7, // GED-C-01
                'code' => 'GED-C-01-10' . $i,
                'name' => 'Ruang Kelas SMA ' . $i,
                'room_number' => '10' . $i,
                'capacity' => 36,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Lantai 2 (GED-C-02)
        for ($i = 1; $i <= 4; $i++) {
            $rooms[] = [
                'floor_id' => 8, // GED-C-02
                'code' => 'GED-C-02-20' . $i,
                'name' => 'Ruang Kelas SMK ' . $i,
                'room_number' => '20' . $i,
                'capacity' => 36,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Lantai 3 (GED-C-03)
        $rooms[] = [
            'floor_id' => 9, // GED-C-03
            'code' => 'GED-C-03-301',
            'name' => 'Laboratorium Komputer SMK',
            'room_number' => '301',
            'capacity' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 9,
            'code' => 'GED-C-03-302',
            'name' => 'Ruang Praktik Teknik',
            'room_number' => '302',
            'capacity' => 25,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // =============== GEDUNG D (GED-D) - Fakultas Teknik ===============
        // Lantai 1 (GED-D-01)
        for ($i = 1; $i <= 3; $i++) {
            $rooms[] = [
                'floor_id' => 10, // GED-D-01
                'code' => 'GED-D-01-10' . $i,
                'name' => 'Ruang Kuliah Teknik ' . $i,
                'room_number' => '10' . $i,
                'capacity' => 80,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Lantai 2-3 (GED-D-02 & GED-D-03)
        for ($floor = 2; $floor <= 3; $floor++) {
            $floorId = 10 + ($floor - 1); // 11, 12
            for ($i = 1; $i <= 3; $i++) {
                $rooms[] = [
                    'floor_id' => $floorId,
                    'code' => 'GED-D-0' . $floor . '-' . $floor . '0' . $i,
                    'name' => 'Ruang Kuliah Teknik ' . $floor . $i,
                    'room_number' => $floor . '0' . $i,
                    'capacity' => 80,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Lantai 4 (GED-D-04) - Laboratorium
        $rooms[] = [
            'floor_id' => 13, // GED-D-04
            'code' => 'GED-D-04-401',
            'name' => 'Laboratorium Riset Teknik',
            'room_number' => '401',
            'capacity' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Lantai 5 (GED-D-05) - Ruang Dosen & Dekan
        $rooms[] = [
            'floor_id' => 14, // GED-D-05
            'code' => 'GED-D-05-501',
            'name' => 'Ruang Dosen Teknik',
            'room_number' => '501',
            'capacity' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 14,
            'code' => 'GED-D-05-502',
            'name' => 'Ruang Dekan Teknik',
            'room_number' => '502',
            'capacity' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // =============== GEDUNG E (GED-E) - Fakultas Ekonomi ===============
        for ($floor = 1; $floor <= 4; $floor++) {
            $floorId = 15 + ($floor - 1); // 15, 16, 17, 18
            for ($i = 1; $i <= 2; $i++) {
                $rooms[] = [
                    'floor_id' => $floorId,
                    'code' => 'GED-E-0' . $floor . '-' . $floor . '0' . $i,
                    'name' => 'Ruang Kuliah Ekonomi ' . $floor . $i,
                    'room_number' => $floor . '0' . $i,
                    'capacity' => 60,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // =============== GEDUNG F (GED-F) - Olahraga & Aula ===============
        $rooms[] = [
            'floor_id' => 19, // GED-F-01
            'code' => 'GED-F-01-101',
            'name' => 'Lapangan Basket Indoor',
            'room_number' => '101',
            'capacity' => 200,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 19,
            'code' => 'GED-F-01-102',
            'name' => 'Lapangan Futsal',
            'room_number' => '102',
            'capacity' => 150,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 20, // GED-F-02
            'code' => 'GED-F-02-201',
            'name' => 'Aula Serbaguna',
            'room_number' => '201',
            'capacity' => 500,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 20,
            'code' => 'GED-F-02-202',
            'name' => 'Ruang Rapat',
            'room_number' => '202',
            'capacity' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // =============== GEDUNG G (GED-G) - Perpustakaan Pusat ===============
        $rooms[] = [
            'floor_id' => 21, // GED-G-01
            'code' => 'GED-G-01-101',
            'name' => 'Ruang Baca Umum',
            'room_number' => '101',
            'capacity' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 22, // GED-G-02
            'code' => 'GED-G-02-201',
            'name' => 'Ruang Koleksi Buku',
            'room_number' => '201',
            'capacity' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 22,
            'code' => 'GED-G-02-202',
            'name' => 'Ruang Digital Library',
            'room_number' => '202',
            'capacity' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 23, // GED-G-03
            'code' => 'GED-G-03-301',
            'name' => 'Ruang Arsip',
            'room_number' => '301',
            'capacity' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // =============== GEDUNG H (GED-H) - Administrasi & Kantor Yayasan ===============
        $rooms[] = [
            'floor_id' => 24, // GED-H-01
            'code' => 'GED-H-01-101',
            'name' => 'Kantor Yayasan',
            'room_number' => '101',
            'capacity' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 24,
            'code' => 'GED-H-01-102',
            'name' => 'Ruang Administrasi',
            'room_number' => '102',
            'capacity' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 25, // GED-H-02
            'code' => 'GED-H-02-201',
            'name' => 'Ruang Keuangan',
            'room_number' => '201',
            'capacity' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 25,
            'code' => 'GED-H-02-202',
            'name' => 'Ruang IT / Teknisi',
            'room_number' => '202',
            'capacity' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 26, // GED-H-03
            'code' => 'GED-H-03-301',
            'name' => 'Ruang Staff Umum',
            'room_number' => '301',
            'capacity' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $rooms[] = [
            'floor_id' => 26,
            'code' => 'GED-H-03-302',
            'name' => 'Gudang Penyimpanan',
            'room_number' => '302',
            'capacity' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('rooms')->insert($rooms);
    }
}