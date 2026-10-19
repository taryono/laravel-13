<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel schools & organizations sudah punya data
        $schools = DB::table('schools')->select('id', 'organization_id')->get();
        $organizationIds = DB::table('organizations')->pluck('id')->toArray();

        if ($schools->isEmpty() || empty($organizationIds)) {
            $this->command->warn('Skip SubjectSeeder: tabel schools atau organizations masih kosong.');
            return;
        }

        // Master data mata pelajaran (kategori CORE / ELECTIVE / dll)
        $masterSubjects = [
            // CORE
            ['code' => 'MTK',  'name' => 'Matematika',              'abbreviation' => 'MTK', 'category' => 'CORE'],
            ['code' => 'BIN',  'name' => 'Bahasa Indonesia',        'abbreviation' => 'B.Indo', 'category' => 'CORE'],
            ['code' => 'BIG',  'name' => 'Bahasa Inggris',          'abbreviation' => 'B.Ing', 'category' => 'CORE'],
            ['code' => 'IPA',  'name' => 'Ilmu Pengetahuan Alam',   'abbreviation' => 'IPA', 'category' => 'CORE'],
            ['code' => 'IPS',  'name' => 'Ilmu Pengetahuan Sosial', 'abbreviation' => 'IPS', 'category' => 'CORE'],
            ['code' => 'PKN',  'name' => 'Pendidikan Kewarganegaraan', 'abbreviation' => 'PKn', 'category' => 'CORE'],

            // RELIGION
            ['code' => 'PAI',  'name' => 'Pendidikan Agama Islam',      'abbreviation' => 'PAI', 'category' => 'RELIGION'],
            ['code' => 'PAK',  'name' => 'Pendidikan Agama Kristen',    'abbreviation' => 'PAK', 'category' => 'RELIGION'],
            ['code' => 'PAH',  'name' => 'Pendidikan Agama Hindu',      'abbreviation' => 'PAH', 'category' => 'RELIGION'],
            ['code' => 'PAB',  'name' => 'Pendidikan Agama Buddha',     'abbreviation' => 'PAB', 'category' => 'RELIGION'],

            // ELECTIVE
            ['code' => 'FIS',  'name' => 'Fisika',   'abbreviation' => 'FIS', 'category' => 'ELECTIVE'],
            ['code' => 'KIM',  'name' => 'Kimia',    'abbreviation' => 'KIM', 'category' => 'ELECTIVE'],
            ['code' => 'BIO',  'name' => 'Biologi',  'abbreviation' => 'BIO', 'category' => 'ELECTIVE'],
            ['code' => 'EKO',  'name' => 'Ekonomi',  'abbreviation' => 'EKO', 'category' => 'ELECTIVE'],
            ['code' => 'SOS',  'name' => 'Sosiologi', 'abbreviation' => 'SOS', 'category' => 'ELECTIVE'],
            ['code' => 'GEO',  'name' => 'Geografi', 'abbreviation' => 'GEO', 'category' => 'ELECTIVE'],
            ['code' => 'SEJ',  'name' => 'Sejarah',  'abbreviation' => 'SEJ', 'category' => 'ELECTIVE'],

            // LOCAL_CONTENT
            ['code' => 'BJW',  'name' => 'Bahasa Jawa',   'abbreviation' => 'B.Jawa', 'category' => 'LOCAL_CONTENT'],
            ['code' => 'BSU',  'name' => 'Bahasa Sunda',  'abbreviation' => 'B.Sunda', 'category' => 'LOCAL_CONTENT'],
            ['code' => 'BBL',  'name' => 'Bahasa Bali',   'abbreviation' => 'B.Bali', 'category' => 'LOCAL_CONTENT'],

            // EXTRACURRICULAR
            ['code' => 'PRM',  'name' => 'Pramuka',       'abbreviation' => 'PRM', 'category' => 'EXTRACURRICULAR'],
            ['code' => 'PMR',  'name' => 'Palang Merah Remaja', 'abbreviation' => 'PMR', 'category' => 'EXTRACURRICULAR'],
        ];

        $now = now();
        $rows = [];

        foreach ($schools as $school) {
            foreach ($masterSubjects as $subject) {
                $rows[] = [
                    'school_id'       => $school->id,
                    'organization_id' => $school->organization_id ?? $organizationIds[array_rand($organizationIds)],
                    'code'            => $subject['code'],
                    'name'            => $subject['name'],
                    'abbreviation'    => $subject['abbreviation'],
                    'description'     => 'Mata pelajaran ' . $subject['name'],
                    'category'        => $subject['category'],
                    'is_active'       => 1,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                    'deleted_at'      => null,
                    'created_by'      => null,
                    'updated_by'      => null,
                ];
            }
        }

        // Insert per-chunk agar aman untuk banyak sekolah
        foreach (array_chunk($rows, 200) as $chunk) {
            // Gunakan insertOrIgnore agar tidak error jika subject sudah ada
            // (unique index organization_id + code)
            DB::table('subjects')->insertOrIgnore($chunk);
        }

        $this->command->info('SubjectSeeder: ' . count($rows) . ' baris diproses (insertOrIgnore).');
    }
}
