<?php

namespace Database\Seeders;

use Models\Curriculum;
use Illuminate\Database\Seeder;

class CurriculumSeeder extends Seeder
{
    public function run()
    {
        // ⚠️ Sesuaikan ID organisasi dengan data di sistem kamu!
        $defaultOrgId = 1;

        $curriculums = [
            [
                'organization_id' => $defaultOrgId,
                'name' => 'Kurikulum Merdeka',
                'code' => 'KMDK',
                'type' => 'national',
                'description' => 'Kurikulum nasional terbaru yang memberikan kebebasan kepada siswa untuk belajar sesuai dengan kemampuan dan minatnya',
                'start_year' => 2022,
                'end_year' => null,
            ],
            [
                'organization_id' => $defaultOrgId,
                'name' => 'Kurikulum 2013',
                'code' => 'K13',
                'type' => 'national',
                'description' => 'Kurikulum nasional sebelumnya dengan pendekatan pembelajaran berbasis kompetensi dan penilaian autentik',
                'start_year' => 2013,
                'end_year' => 2026,
            ],
            [
                'organization_id' => $defaultOrgId,
                'name' => 'Cambridge International Curriculum',
                'code' => 'CIE',
                'type' => 'international',
                'description' => 'Kurikulum internasional dari Cambridge Assessment International Education',
                'start_year' => 2021,
                'end_year' => null,
            ],
            [
                'organization_id' => $defaultOrgId,
                'name' => 'Kurikulum Diniyah Berbasis Pesantren',
                'code' => 'KDP',
                'type' => 'local',
                'description' => 'Kurikulum lokal berbasis nilai-nilai pesantren dan keunggulan daerah',
                'start_year' => 2020,
                'end_year' => null,
            ],
            [
                'organization_id' => $defaultOrgId,
                'name' => 'Kurikulum Hybrid Nasional & Internasional',
                'code' => 'HYBRID',
                'type' => 'hybrid',
                'description' => 'Gabungan kurikulum nasional dengan elemen standar internasional',
                'start_year' => 2024,
                'end_year' => null,
            ],
        ];

        foreach ($curriculums as $curriculum) {
            Curriculum::create($curriculum);
        }
    }
}
