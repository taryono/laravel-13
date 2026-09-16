<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClassLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizationId = 1;
        $now = Carbon::now();

        $classLevels = [
            [
                'code' => 'PAUD',
                'name' => 'Pendidikan Anak Usia Dini',
                'description' => 'Jenjang pendidikan anak usia dini sebelum memasuki pendidikan dasar.',
                'level_order' => 10,
            ],
            [
                'code' => 'TK',
                'name' => 'Taman Kanak-Kanak',
                'description' => 'Jenjang pendidikan anak usia dini yang ditujukan untuk anak sebelum memasuki sekolah dasar.',
                'level_order' => 20,
            ],
            [
                'code' => 'SD',
                'name' => 'Sekolah Dasar',
                'description' => 'Jenjang pendidikan dasar tingkat pertama yang umumnya ditempuh selama enam tahun.',
                'level_order' => 30,
            ],
            [
                'code' => 'MI',
                'name' => 'Madrasah Ibtidaiyah',
                'description' => 'Jenjang pendidikan dasar yang setara dengan Sekolah Dasar dan berciri khas pendidikan Islam.',
                'level_order' => 40,
            ],
            [
                'code' => 'SMP',
                'name' => 'Sekolah Menengah Pertama',
                'description' => 'Jenjang pendidikan dasar tingkat lanjutan setelah Sekolah Dasar.',
                'level_order' => 50,
            ],
            [
                'code' => 'MTs',
                'name' => 'Madrasah Tsanawiyah',
                'description' => 'Jenjang pendidikan dasar tingkat lanjutan yang setara dengan Sekolah Menengah Pertama dan berciri khas pendidikan Islam.',
                'level_order' => 60,
            ],
            [
                'code' => 'SMA',
                'name' => 'Sekolah Menengah Atas',
                'description' => 'Jenjang pendidikan menengah umum setelah pendidikan dasar.',
                'level_order' => 70,
            ],
            [
                'code' => 'MA',
                'name' => 'Madrasah Aliyah',
                'description' => 'Jenjang pendidikan menengah yang setara dengan Sekolah Menengah Atas dan berciri khas pendidikan Islam.',
                'level_order' => 80,
            ],
            [
                'code' => 'SMK',
                'name' => 'Sekolah Menengah Kejuruan',
                'description' => 'Jenjang pendidikan menengah yang berfokus pada pendidikan dan keterampilan kejuruan.',
                'level_order' => 90,
            ],
            [
                'code' => 'MAK',
                'name' => 'Madrasah Aliyah Kejuruan',
                'description' => 'Jenjang pendidikan menengah kejuruan yang setara dengan Sekolah Menengah Kejuruan dan berciri khas pendidikan Islam.',
                'level_order' => 100,
            ],
            [
                'code' => 'D1',
                'name' => 'Diploma I',
                'description' => 'Jenjang pendidikan tinggi program diploma dengan masa studi umumnya satu tahun.',
                'level_order' => 110,
            ],
            [
                'code' => 'D2',
                'name' => 'Diploma II',
                'description' => 'Jenjang pendidikan tinggi program diploma dengan masa studi umumnya dua tahun.',
                'level_order' => 120,
            ],
            [
                'code' => 'D3',
                'name' => 'Diploma III',
                'description' => 'Jenjang pendidikan tinggi program diploma dengan masa studi umumnya tiga tahun.',
                'level_order' => 130,
            ],
            [
                'code' => 'D4',
                'name' => 'Diploma IV / Sarjana Terapan',
                'description' => 'Jenjang pendidikan tinggi vokasi yang setara dengan program sarjana.',
                'level_order' => 140,
            ],
            [
                'code' => 'S1',
                'name' => 'Sarjana',
                'description' => 'Jenjang pendidikan tinggi program akademik tingkat sarjana.',
                'level_order' => 150,
            ],
            [
                'code' => 'PROF',
                'name' => 'Profesi',
                'description' => 'Program pendidikan tinggi setelah program sarjana untuk memperoleh keahlian profesi tertentu.',
                'level_order' => 160,
            ],
            [
                'code' => 'S2',
                'name' => 'Magister',
                'description' => 'Jenjang pendidikan tinggi program akademik tingkat magister.',
                'level_order' => 170,
            ],
            [
                'code' => 'SP1',
                'name' => 'Spesialis I',
                'description' => 'Jenjang pendidikan profesi spesialis tingkat pertama.',
                'level_order' => 180,
            ],
            [
                'code' => 'S3',
                'name' => 'Doktor',
                'description' => 'Jenjang pendidikan tinggi program akademik tingkat doktoral.',
                'level_order' => 190,
            ],
            [
                'code' => 'SP2',
                'name' => 'Spesialis II',
                'description' => 'Jenjang pendidikan profesi spesialis tingkat lanjutan.',
                'level_order' => 200,
            ],
        ];

        foreach ($classLevels as $level) {
            DB::table('class_levels')->updateOrInsert(
                [
                    'organization_id' => $organizationId,
                    'code' => $level['code'],
                ],
                [
                    'name' => $level['name'],
                    'description' => $level['description'],
                    'level_order' => $level['level_order'],
                    'is_active' => 1,
                    'updated_at' => $now,
                ]
            );
        }
    }
}