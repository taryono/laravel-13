<?php

namespace Database\Seeders;

use Models\EducationLevel;
use Illuminate\Database\Seeder;

class EducationLevelSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['code' => 'TK', 'name' => 'Taman Kanak-kanak', 'level' => 1, 'sort_order' => 1],
            ['code' => 'SD', 'name' => 'Sekolah Dasar / Sederajat', 'level' => 2, 'sort_order' => 2],
            ['code' => 'SMP', 'name' => 'Sekolah Menengah Pertama / Sederajat', 'level' => 3, 'sort_order' => 3],
            ['code' => 'SMA', 'name' => 'Sekolah Menengah Atas / Sederajat', 'level' => 4, 'sort_order' => 4],
            ['code' => 'D1', 'name' => 'Diploma I', 'level' => 5, 'sort_order' => 5],
            ['code' => 'D2', 'name' => 'Diploma II', 'level' => 6, 'sort_order' => 6],
            ['code' => 'D3', 'name' => 'Diploma III', 'level' => 7, 'sort_order' => 7],
            ['code' => 'D4', 'name' => 'Diploma IV / Sarjana Terapan', 'level' => 8, 'sort_order' => 8],
            ['code' => 'S1', 'name' => 'Sarjana (S1)', 'level' => 9, 'sort_order' => 9],
            ['code' => 'S2', 'name' => 'Magister (S2)', 'level' => 10, 'sort_order' => 10],
            ['code' => 'S3', 'name' => 'Doktor (S3)', 'level' => 11, 'sort_order' => 11],
        ];

        foreach ($data as $item) {
            EducationLevel::updateOrCreate(['code' => $item['code']], $item);
        }
    }
}