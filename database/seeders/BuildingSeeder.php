<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('buildings')->insert([
            [
                'code' => 'GED-A',
                'name' => 'Gedung Utama (PAUD-SMA)',
                'address' => 'Jl. Pendidikan No. 1, Kota Pendidikan',
                'total_floors' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'GED-B',
                'name' => 'Gedung TK/PAUD',
                'address' => 'Jl. Pendidikan No. 2, Kota Pendidikan',
                'total_floors' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'GED-C',
                'name' => 'Gedung SMA/SMK',
                'address' => 'Jl. Pendidikan No. 3, Kota Pendidikan',
                'total_floors' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'GED-D',
                'name' => 'Gedung Fakultas Teknik (Universitas)',
                'address' => 'Jl. Kampus No. 1, Kota Pendidikan',
                'total_floors' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'GED-E',
                'name' => 'Gedung Fakultas Ekonomi (Universitas)',
                'address' => 'Jl. Kampus No. 2, Kota Pendidikan',
                'total_floors' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'GED-F',
                'name' => 'Gedung Olahraga & Aula',
                'address' => 'Jl. Pendidikan No. 4, Kota Pendidikan',
                'total_floors' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'GED-G',
                'name' => 'Gedung Perpustakaan Pusat',
                'address' => 'Jl. Pendidikan No. 5, Kota Pendidikan',
                'total_floors' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'GED-H',
                'name' => 'Gedung Administrasi & Kantor Yayasan',
                'address' => 'Jl. Pendidikan No. 6, Kota Pendidikan',
                'total_floors' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}