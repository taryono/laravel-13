<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizationId = 1;

        /*
         * Ambil Academic Year aktif.
         *
         * Seeder tidak menggunakan academic_year_id = 1
         * karena ID bisa berbeda pada setiap database.
         */
        $academicYear = DB::table('academic_years')
            ->where('organization_id', $organizationId)
            ->where('is_active', 1)
            ->orderByDesc('id')
            ->first();

        if (!$academicYear) {
            throw new RuntimeException(
                "Academic year aktif untuk organization_id {$organizationId} tidak ditemukan."
            );
        }

        /*
         * Data classroom.
         *
         * class_level_code harus sesuai dengan code
         * yang ada di tabel class_levels.
         */
        $classrooms = [
            // SD
            [ 
                'name' => 'Kelas 1 A',
                'code' => 'SD-A',
                'capacity' => 30,
            ],
            [ 
                'name' => 'Kelas 1 B',
                'code' => 'SD-B',
                'capacity' => 30,
            ],
            [ 
                'name' => 'Kelas 2 A',
                'code' => 'SD-A',
                'capacity' => 30,
            ],
            [ 
                'name' => 'Kelas 2 B',
                'code' => 'SD-B',
                'capacity' => 30,
            ],
            [
                
                'name' => 'Kelas 3 A',
                'code' => 'SD-A',
                'capacity' => 30,
            ],
            [
                
                'name' => 'Kelas 3 B',
                'code' => 'SD-B',
                'capacity' => 30,
            ],
            [
                
                'name' => 'Kelas 4 A',
                'code' => 'SD-A',
                'capacity' => 30,
            ],
            [
                
                'name' => 'Kelas 4 B',
                'code' => 'SD-B',
                'capacity' => 30,
            ],
            [
                
                'name' => 'Kelas 5 A',
                'code' => 'SD-A',
                'capacity' => 30,
            ],
            [
                
                'name' => 'Kelas 5 B',
                'code' => 'SD-B',
                'capacity' => 30,
            ],
            [
                
                'name' => 'Kelas 6 A',
                'code' => 'SD-A',
                'capacity' => 30,
            ],
            [
                
                'name' => 'Kelas 6 B',
                'code' => 'SD-B',
                'capacity' => 30,
            ],

            // SMP
            [
                 
                'name' => 'Kelas 7 A',
                'code' => 'SMP7-A',
                'capacity' => 32,
            ],
            [
                 
                'name' => 'Kelas 7 B',
                'code' => 'SMP7-B',
                'capacity' => 32,
            ],
            [
                 
                'name' => 'Kelas 8 A',
                'code' => 'SMP8-A',
                'capacity' => 32,
            ],
            [
                 
                'name' => 'Kelas 8 B',
                'code' => 'SMP8-B',
                'capacity' => 32,
            ],
            [
                 
                'name' => 'Kelas 9 A',
                'code' => 'SMP9-A',
                'capacity' => 32,
            ],
            [
                 
                'name' => 'Kelas 9 B',
                'code' => 'SMP9-B',
                'capacity' => 32,
            ],

            // SMA
            [
                
                'name' => 'Kelas 10 A',
                'code' => 'SMA10-A',
                'capacity' => 36,
            ],
            [
                
                'name' => 'Kelas 10 B',
                'code' => 'SMA10-B',
                'capacity' => 36,
            ],
            [
                
                'name' => 'Kelas 11 A',
                'code' => 'SMA11-A',
                'capacity' => 36,
            ],
            [ 
                'name' => 'Kelas 11 B',
                'code' => 'SMA11-B',
                'capacity' => 36,
            ],
            [ 
                'name' => 'Kelas 12 A',
                'code' => 'SMA12-A',
                'capacity' => 36,
            ],
            [
               
                'name' => 'Kelas 12 B',
                'code' => 'SMA12-B',
                'capacity' => 36,
            ],
        ];

        $classLevels = DB::table('class_levels')
                ->where('organization_id', $organizationId) 
                ->whereNull('deleted_at')
                ->get();

            if ($classLevels->count() == 0) {
                throw new RuntimeException(
                    "Class level dengan code '{$classroom['class_level_code']}' " .
                    "untuk organization_id {$organizationId} tidak ditemukan."
                );
            }
        foreach($classLevels as $classLevel) {
            
            foreach ($classrooms as $classroom) {
                /*
                 * Cari class_level berdasarkan code,
                 * bukan berdasarkan ID.
                 */
                
                /*
                 * Insert/update berdasarkan:
                 * organization_id + code
                 *
                 * Sesuai unique index:
                 * classrooms_org_code_unique
                 */
                DB::table('classrooms')->updateOrInsert(
                    [
                        'organization_id' => $organizationId,
                        'code' => $classroom['code'],
                    ],
                    [
                        'class_level_id' => $classLevel->id,
                        'name' => $classroom['name'],
                        'capacity' => $classroom['capacity'],
                        'academic_year_id' => $academicYear->id,
                        'is_active' => 1,
                        'deleted_at' => null,
                        'updated_at' => now(),
                    ]
                );
            }
        }
            

        $this->command?->info(
            'Classroom seeder berhasil dibuat/diperbarui untuk ' .
            "organization_id {$organizationId} dan academic year ID {$academicYear->id}."
        );
    }
}