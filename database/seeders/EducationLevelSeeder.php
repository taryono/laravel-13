<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationLevelSeeder extends Seeder
{
    /**
     * Jalankan untuk semua organisasi yang ada.
     */
    public function run(): void
    {
        DB::table('organizations')
            ->orderBy('id')
            ->pluck('id')
            ->each(fn ($organizationId) => self::seedForOrganization((int) $organizationId));
    }

    /**
     * Seed jenjang pendidikan untuk satu organisasi.
     * Aman dijalankan berulang (upsert berdasarkan organization_id + code).
     * Baris yang sudah di-soft-delete tidak otomatis dipulihkan.
     */
    public static function seedForOrganization(int $organizationId): void
    {
        $now = now();

        $rows = collect(self::levels())->map(fn (array $level) => [
            'organization_id' => $organizationId,
            'code'            => $level['code'],
            'name'            => $level['name'],
            'name_en'         => $level['name_en'],
            'stage'           => $level['stage'],
            'sort_order'      => $level['sort_order'],
            'duration_years'  => $level['duration_years'],
            'min_age'         => $level['min_age'],
            'max_age'         => $level['max_age'],
            'is_active'       => true,
            'created_at'      => $now,
            'updated_at'      => $now,
        ])->all();

        DB::table('education_levels')->upsert(
            $rows,
            ['organization_id', 'code'],
            ['name', 'name_en', 'stage', 'sort_order', 'duration_years', 'min_age', 'max_age', 'updated_at']
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function levels(): array
    {
        return [
            ['code' => 'TPA',  'name' => 'Taman Penitipan Anak',            'name_en' => 'Daycare',                'stage' => 'paud',     'sort_order' => 1,  'duration_years' => null, 'min_age' => 0,  'max_age' => 6],
            ['code' => 'KB',   'name' => 'Kelompok Bermain',                'name_en' => 'Playgroup',              'stage' => 'paud',     'sort_order' => 2,  'duration_years' => 2,    'min_age' => 2,  'max_age' => 4],
            ['code' => 'TK',   'name' => 'Taman Kanak-kanak / RA',          'name_en' => 'Kindergarten',           'stage' => 'paud',     'sort_order' => 3,  'duration_years' => 2,    'min_age' => 4,  'max_age' => 6],
            ['code' => 'SD',   'name' => 'Sekolah Dasar / MI',              'name_en' => 'Elementary School',      'stage' => 'dasar',    'sort_order' => 4,  'duration_years' => 6,    'min_age' => 6,  'max_age' => 12],
            ['code' => 'SMP',  'name' => 'Sekolah Menengah Pertama / MTs',  'name_en' => 'Junior High School',     'stage' => 'dasar',    'sort_order' => 5,  'duration_years' => 3,    'min_age' => 12, 'max_age' => 15],
            ['code' => 'SMA',  'name' => 'Sekolah Menengah Atas / MA',      'name_en' => 'Senior High School',     'stage' => 'menengah', 'sort_order' => 6,  'duration_years' => 3,    'min_age' => 15, 'max_age' => 18],
            ['code' => 'SMK',  'name' => 'Sekolah Menengah Kejuruan / MAK', 'name_en' => 'Vocational High School', 'stage' => 'menengah', 'sort_order' => 7,  'duration_years' => 3,    'min_age' => 15, 'max_age' => 19],
            ['code' => 'D1',   'name' => 'Diploma 1',                       'name_en' => 'Diploma 1',              'stage' => 'tinggi',   'sort_order' => 8,  'duration_years' => 1,    'min_age' => 18, 'max_age' => null],
            ['code' => 'D2',   'name' => 'Diploma 2',                       'name_en' => 'Diploma 2',              'stage' => 'tinggi',   'sort_order' => 9,  'duration_years' => 2,    'min_age' => 18, 'max_age' => null],
            ['code' => 'D3',   'name' => 'Diploma 3',                       'name_en' => 'Associate Degree',       'stage' => 'tinggi',   'sort_order' => 10, 'duration_years' => 3,    'min_age' => 18, 'max_age' => null],
            ['code' => 'D4',   'name' => 'Sarjana Terapan',                 'name_en' => 'Applied Bachelor',       'stage' => 'tinggi',   'sort_order' => 11, 'duration_years' => 4,    'min_age' => 18, 'max_age' => null],
            ['code' => 'S1',   'name' => 'Sarjana',                         'name_en' => 'Bachelor',               'stage' => 'tinggi',   'sort_order' => 12, 'duration_years' => 4,    'min_age' => 18, 'max_age' => null],
            ['code' => 'PROF', 'name' => 'Profesi',                         'name_en' => 'Professional Program',   'stage' => 'tinggi',   'sort_order' => 13, 'duration_years' => 1,    'min_age' => 22, 'max_age' => null],
            ['code' => 'S2',   'name' => 'Magister',                        'name_en' => 'Master',                 'stage' => 'tinggi',   'sort_order' => 14, 'duration_years' => 2,    'min_age' => 22, 'max_age' => null],
            ['code' => 'SP',   'name' => 'Spesialis',                       'name_en' => 'Specialist',             'stage' => 'tinggi',   'sort_order' => 15, 'duration_years' => null, 'min_age' => 24, 'max_age' => null],
            ['code' => 'S3',   'name' => 'Doktor',                          'name_en' => 'Doctorate',              'stage' => 'tinggi',   'sort_order' => 16, 'duration_years' => 3,    'min_age' => 24, 'max_age' => null],
        ];
    }
}