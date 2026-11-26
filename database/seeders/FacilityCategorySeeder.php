<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilityCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('facility_categories')->insert([
            [
                'code' => 'EDU',
                'organization_id'=> 1,
                'name' => 'Sarana Pendidikan Inti',
                'description' => 'Fasilitas utama untuk kegiatan belajar-mengajar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SUPPORT',
                'organization_id'=> 1,
                'name' => 'Fasilitas Penunjang Kegiatan',
                'description' => 'Fasilitas untuk menunjang kegiatan non-akademik dan pengembangan diri',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'OPERATIONAL',
                'organization_id' => 1,
                'name' => 'Sarana Umum & Penunjang Operasional',
                'description' => 'Fasilitas untuk mendukung operasional yayasan dan kenyamanan',
                'created_at' => now(), 'updated_at' => now(),
            ],
            ['code' => 'sport','organization_id'=> 1, 'name' => 'Olahraga', 'description' => 'Fasilitas olahraga', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'lab', 'organization_id' => 1,'name' => 'Laboratorium', 'description' => 'Fasilitas laboratorium', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'class','organization_id' => 1, 'name' => 'Ruang Kelas', 'description' => 'Fasilitas ruang kelas', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}