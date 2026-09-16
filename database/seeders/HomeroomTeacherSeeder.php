<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class HomeroomTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizationId = 1;

        /*
         * Ambil Academic Year aktif.
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
         * Ambil data homeroom teacher.
         */
       $classrooms = DB::table('classrooms')
                ->where('organization_id', $organizationId) 
                ->whereNull('deleted_at')
                ->get();
        $old_teacher_id = 0;
        foreach ($classrooms as $classroom) {

            /*
             * Cari guru berdasarkan NIP.
             */
            $teacher = DB::table('teachers')
                ->where('organization_id', $organizationId) 
                ->whereNull('deleted_at')
                ->where('id', '!=', $old_teacher_id)
                ->first();

            if (!$teacher) {
                throw new RuntimeException(
                    "Teacher dengan NIP '{$assignment['teacher_nip']}' " .
                    "untuk organization_id {$organizationId} tidak ditemukan."
                );
            }
  
              
            DB::table('homeroom_teachers')->updateOrInsert(
                [
                    'classroom_id' => $classroom->id,
                    'academic_year_id' => $academicYear->id,
                ],
                [
                    'organization_id' => $organizationId,
                    'teacher_id' => $teacher->id,
                    'is_active' => 1,
                    'assignment_date' => now(),
                    'end_date' => now(),
                    'notes' => null,
                    'deleted_at' => null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
            $old_teacher_id = $teacher->id;
        }

        $this->command?->info(
            'Homeroom teacher seeder berhasil dibuat/diperbarui. ' .
            'Academic Year ID: ' . $academicYear->id
        );
    }
}