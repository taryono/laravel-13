<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizationId = 1;
        $schoolId = 1;
        $now = Carbon::now();

        $academicYears = [
            [
                'name'                  => 'Tahun Ajaran 2023/2024 Ganjil',
                'code'                  => '2023-2024-GANJIL',
                'start_date'            => '2023-07-01',
                'end_date'              => '2023-12-31',
                'semester_start_date'   => '2023-07-15',
                'semester_end_date'     => '2023-12-20',
                'is_registration_open'  => false,
                'registration_start_date' => '2023-05-01',
                'registration_end_date'   => '2023-06-30',
                'is_active'             => true,
                'is_current'            => false,
            ],
            [
                'name'                  => 'Tahun Ajaran 2023/2024 Genap',
                'code'                  => '2023-2024-GENAP',
                'start_date'            => '2024-01-01',
                'end_date'              => '2024-06-30',
                'semester_start_date'   => '2024-01-10',
                'semester_end_date'     => '2024-06-15',
                'is_registration_open'  => false,
                'registration_start_date' => '2023-11-01',
                'registration_end_date'   => '2023-12-15',
                'is_active'             => true,
                'is_current'            => false,
            ],
            [
                'name'                  => 'Tahun Ajaran 2024/2025 Ganjil',
                'code'                  => '2024-2025-GANJIL',
                'start_date'            => '2024-07-01',
                'end_date'              => '2024-12-31',
                'semester_start_date'   => '2024-07-15',
                'semester_end_date'     => '2024-12-20',
                'is_registration_open'  => false,
                'registration_start_date' => '2024-05-01',
                'registration_end_date'   => '2024-06-30',
                'is_active'             => true,
                'is_current'            => false,
            ],
            [
                'name'                  => 'Tahun Ajaran 2024/2025 Genap',
                'code'                  => '2024-2025-GENAP',
                'start_date'            => '2025-01-01',
                'end_date'              => '2025-06-30',
                'semester_start_date'   => '2025-01-10',
                'semester_end_date'     => '2025-06-15',
                'is_registration_open'  => false,
                'registration_start_date' => '2024-11-01',
                'registration_end_date'   => '2024-12-15',
                'is_active'             => true,
                'is_current'            => false,
            ],
            [
                'name'                  => 'Tahun Ajaran 2025/2026 Ganjil',
                'code'                  => '2025-2026-GANJIL',
                'start_date'            => '2025-07-01',
                'end_date'              => '2025-12-31',
                'semester_start_date'   => '2025-07-15',
                'semester_end_date'     => '2025-12-20',
                'is_registration_open'  => true,
                'registration_start_date' => '2025-05-01',
                'registration_end_date'   => '2025-06-30',
                'is_active'             => true,
                'is_current'            => true, // ← tahun ajaran aktif saat ini
            ],
        ];

        foreach ($academicYears as $year) {
            DB::table('academic_years')->updateOrInsert(
                [
                    'organization_id' => $organizationId,
                    'code'            => $year['code'],
                ],
                array_merge($year, [
                    'organization_id' => $organizationId,
                    'school_id'       => $schoolId,
                    'created_by'      => null,
                    'updated_by'      => null,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ])
            );
        }

        $this->command->info('✅ AcademicYearSeeder: ' . count($academicYears) . ' data berhasil di-seed.');
    }
}