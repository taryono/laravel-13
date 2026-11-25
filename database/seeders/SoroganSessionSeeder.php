<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Models\AcademicSemester;
use Models\AcademicYear;
use Models\Dormitory;
use Models\Employee;
use Models\KitabReference;
use Models\Organization;
use Models\Room;
use Models\SoroganSchedule;
use Models\SoroganSession;
use Models\Teacher;

class SoroganSessionSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = Teacher::first();
        $employee = Employee::first();

        if (!$teacher && !$employee) {
            $this->command?->warn('SoroganSessionSeeder dilewati: belum ada teachers/employees.');
            return;
        }

        $organization = Organization::first();
        $kitab = KitabReference::first();
        $dormitory = Dormitory::first();
        $room = Room::where('code', 'like', 'AULA-%')->first();
        $year = AcademicYear::where('is_current', 1)->first() ?? AcademicYear::first();
        $semester = AcademicSemester::first();
        $schedule = SoroganSchedule::first();

        $base = [
            'sorogan_schedule_id' => $schedule?->id,
            'organization_id' => $organization?->id,
            'kitab_reference_id' => $kitab?->id,
            'teacher_id' => $teacher?->id,
            'employee_id' => $teacher ? null : $employee?->id,
            'dormitory_id' => $dormitory?->id,
            'room_id' => $room?->id,
            'academic_year_id' => $year?->id,
            'academic_semester_id' => $semester?->id,
            'start_time' => '19:30:00',
            'end_time' => '20:30:00',
        ];

        $sessions = [
            ['title' => 'Sorogan Pekan 1', 'days' => -21, 'status' => 'completed', 'chapter' => 'Bab 1', 'page_from' => 1, 'page_to' => 10, 'total' => 20, 'present' => 18],
            ['title' => 'Sorogan Pekan 2', 'days' => -14, 'status' => 'completed', 'chapter' => 'Bab 1', 'page_from' => 11, 'page_to' => 20, 'total' => 20, 'present' => 19],
            ['title' => 'Sorogan Pekan 3', 'days' => -7, 'status' => 'cancelled', 'chapter' => null, 'page_from' => null, 'page_to' => null, 'total' => 20, 'present' => 0, 'reason' => 'Libur kegiatan pondok'],
            ['title' => 'Sorogan Pekan 4', 'days' => 0, 'status' => 'ongoing', 'chapter' => 'Bab 2', 'page_from' => 21, 'page_to' => 30, 'total' => 20, 'present' => 17],
            ['title' => 'Sorogan Pekan 5', 'days' => 7, 'status' => 'scheduled', 'chapter' => 'Bab 2', 'page_from' => 31, 'page_to' => 40, 'total' => 20, 'present' => 0],
        ];

        foreach ($sessions as $s) {
            $date = now()->addDays($s['days'])->toDateString();

            SoroganSession::updateOrCreate(
                ['title' => $s['title'], 'session_date' => $date],
                $base + [
                    'session_date' => $date,
                    'location' => $room ? null : 'Serambi masjid',
                    'chapter' => $s['chapter'],
                    'page_from' => $s['page_from'],
                    'page_to' => $s['page_to'],
                    'topic' => $s['chapter'] ? "Pembahasan {$s['chapter']}" : null,
                    'status' => $s['status'],
                    'cancellation_reason' => $s['reason'] ?? null,
                    'total_participants' => $s['total'],
                    'present_count' => $s['present'],
                    'actual_start_at' => in_array($s['status'], ['completed', 'ongoing']) ? "{$date} 19:30:00" : null,
                    'actual_end_at' => $s['status'] === 'completed' ? "{$date} 20:30:00" : null,
                ]
            );
        }
    }
}