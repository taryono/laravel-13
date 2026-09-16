<?php
// database/seeders/AcademicSeeder.php

namespace Database\Seeders;

use Models\Classroom;
use Models\HomeroomTeacher;
use Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Data Guru
            $teachers = [
                ['name' => 'Budi Santoso, S.Pd', 'nip' => '198001012001011001', 'subject' => 'Matematika'],
                ['name' => 'Siti Aminah, S.Pd', 'nip' => '198505152005022002', 'subject' => 'Bahasa Indonesia'],
                ['name' => 'Ahmad Zulkifli, S.T', 'nip' => '198203102003011003', 'subject' => 'IPA'],
                ['name' => 'Dewi Lestari, S.Pd', 'nip' => '198709202007032004', 'subject' => 'IPS'],
                ['name' => 'Hendra Pratama, S.Kom', 'nip' => '198412052004011005', 'subject' => 'TIK'],
            ];

            foreach ($teachers as $t) {
                Teacher::create([
                    'organization_id' => 1,
                    'name' => $t['name'],
                    'nip' => $t['nip'],
                    'subject' => $t['subject'],
                    'is_active' => true,
                    'created_by' => 1,
                ]);
            }

            // 2. Data Kelas
            $classrooms = [
                ['name' => 'Kelas 1-A', 'code' => 'KL1A', 'capacity' => 32],
                ['name' => 'Kelas 1-B', 'code' => 'KL1B', 'capacity' => 30],
                ['name' => 'Kelas 1-C', 'code' => 'KL1C', 'capacity' => 28],
                ['name' => 'Kelas 1-D', 'code' => 'KL1D', 'capacity' => 32],
                ['name' => 'Kelas 1-E', 'code' => 'KL1E', 'capacity' => 30],
            ];

            foreach ($classrooms as $c) {
                Classroom::create([
                    'organization_id' => 1,
                    'class_level_id' => 1,
                    'academic_year_id' => 1,
                    'name' => $c['name'],
                    'code' => $c['code'],
                    'capacity' => $c['capacity'],
                    'is_active' => true,
                    'created_by' => 1,
                ]);
            }

            // 3. Penugasan Wali Kelas
            $assignments = [
                ['teacher_id' => 1, 'classroom_id' => 1, 'assignment_date' => '2025-07-01'],
                ['teacher_id' => 2, 'classroom_id' => 2, 'assignment_date' => '2025-07-01'],
                ['teacher_id' => 3, 'classroom_id' => 3, 'assignment_date' => '2025-07-01'],
                ['teacher_id' => 4, 'classroom_id' => 4, 'assignment_date' => '2025-07-01'],
                ['teacher_id' => 5, 'classroom_id' => 5, 'assignment_date' => '2025-07-01'],
            ];

            foreach ($assignments as $a) {
                HomeroomTeacher::create([
                    'organization_id' => 1,
                    'teacher_id' => $a['teacher_id'],
                    'classroom_id' => $a['classroom_id'],
                    'academic_year_id' => 1,
                    'is_active' => true,
                    'assignment_date' => $a['assignment_date'],
                    'created_by' => 1,
                ]);
            }
        });
    }
}