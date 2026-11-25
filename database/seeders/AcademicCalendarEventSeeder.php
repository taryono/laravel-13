<?php

namespace Database\Seeders;

use Models\AcademicCalendarEvent;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AcademicCalendarEventSeeder extends Seeder
{
    public function run()
    {
        // ⚠️ Sesuaikan ID ini dengan data yang ada di tabel referensi kamu!
        $defaultSchoolId = 1;
        $defaultYearId = 1;
        $defaultSemesterId = 1;
        $defaultUserId = 1; // user/admin yang membuat data

        $events = [
            [
                'school_id' => $defaultSchoolId,
                'academic_year_id' => $defaultYearId,
                'academic_semester_id' => $defaultSemesterId,
                'title' => 'Pembukaan Tahun Ajaran Baru',
                'description' => 'Upacara pembukaan tahun ajaran baru untuk seluruh siswa dan guru',
                'event_type' => 'meeting',
                'start_date' => Carbon::create(2026, 7, 15),
                'end_date' => Carbon::create(2026, 7, 15),
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
                'location' => 'Aula Utama Sekolah',
                'is_holiday' => false,
                'is_all_day' => false,
                'color' => '#2563eb',
                'is_active' => true,
                'created_by' => $defaultUserId,
            ],
            [
                'school_id' => $defaultSchoolId,
                'academic_year_id' => $defaultYearId,
                'academic_semester_id' => $defaultSemesterId,
                'title' => 'Libur Idul Adha',
                'description' => 'Libur nasional memperingati hari raya Idul Adha',
                'event_type' => 'holiday',
                'start_date' => Carbon::create(2026, 7, 20),
                'end_date' => Carbon::create(2026, 7, 22),
                'is_holiday' => true,
                'is_all_day' => true,
                'color' => '#ef4444',
                'is_active' => true,
                'created_by' => $defaultUserId,
            ],
            [
                'school_id' => $defaultSchoolId,
                'academic_year_id' => $defaultYearId,
                'academic_semester_id' => $defaultSemesterId,
                'title' => 'Ujian Tengah Semester',
                'description' => 'Pelaksanaan Ujian Tengah Semester Ganjil',
                'event_type' => 'exam',
                'start_date' => Carbon::create(2026, 9, 15),
                'end_date' => Carbon::create(2026, 9, 20),
                'is_holiday' => false,
                'is_all_day' => true,
                'color' => '#f59e0b',
                'is_active' => true,
                'created_by' => $defaultUserId,
            ],
            [
                'school_id' => $defaultSchoolId,
                'academic_year_id' => $defaultYearId,
                'academic_semester_id' => $defaultSemesterId,
                'title' => 'Pendaftaran Siswa Baru',
                'description' => 'Masa pendaftaran calon siswa baru tahun ajaran berikutnya',
                'event_type' => 'registration',
                'start_date' => Carbon::create(2026, 11, 1),
                'end_date' => Carbon::create(2026, 12, 31),
                'start_time' => '08:00:00',
                'end_time' => '14:00:00',
                'location' => 'Bagian Administrasi / Ruang PSB',
                'is_holiday' => false,
                'is_all_day' => false,
                'color' => '#10b981',
                'is_active' => true,
                'created_by' => $defaultUserId,
            ],
            [
                'school_id' => $defaultSchoolId,
                'academic_year_id' => $defaultYearId,
                'academic_semester_id' => $defaultSemesterId,
                'title' => 'Libur Akhir Tahun',
                'description' => 'Libur akhir tahun kalender',
                'event_type' => 'holiday',
                'start_date' => Carbon::create(2026, 12, 25),
                'end_date' => Carbon::create(2027, 1, 5),
                'is_holiday' => true,
                'is_all_day' => true,
                'color' => '#ef4444',
                'is_active' => true,
                'created_by' => $defaultUserId,
            ],
        ];

        foreach ($events as $event) {
            AcademicCalendarEvent::create($event);
        }
    }
}
