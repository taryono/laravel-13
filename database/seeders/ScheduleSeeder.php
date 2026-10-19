<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ScheduleSeeder extends Seeder
{
    /**
     * Slot jam pelajaran standar (mulai, selesai).
     * Sesuaikan dengan jam sekolah Anda.
     */
    protected array $timeSlots = [
        ['07:00:00', '07:45:00'],
        ['07:45:00', '08:30:00'],
        ['08:30:00', '09:15:00'],
        ['09:15:00', '10:00:00'], // istirahat di 10:00-10:15
        ['10:15:00', '11:00:00'],
        ['11:00:00', '11:45:00'],
        ['11:45:00', '12:30:00'], // istirahat di 12:30-13:00
        ['13:00:00', '13:45:00'],
        ['13:45:00', '14:30:00'],
    ];

    protected array $days = ['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY'];

    public function run(): void
    {
        // ============ Ambil data referensi ============
        $classrooms     = DB::table('classrooms')->pluck('id')->toArray();
        $subjects       = DB::table('subjects')->pluck('id')->toArray();
        $teachers       = DB::table('teachers')->pluck('id')->toArray();
        $academicYears  = DB::table('academic_years')->pluck('id')->toArray();
        $rooms          = DB::table('timetable_rooms')->pluck('id')->toArray();
        $generations    = DB::table('timetable_generations')->pluck('id')->toArray();

        // Validasi dependency wajib
        if (empty($classrooms) || empty($subjects) || empty($academicYears)) {
            $this->command->warn('Skip ScheduleSeeder: tabel classrooms, subjects, atau academic_years masih kosong.');
            return;
        }

        $now = now();
        $rows = [];

        // ============ Set pelacak bentrok jadwal ============
        // Struktur: ['teacher' => [teacher_id][day][time] => true, ...]
        $teacherBusy = []; // teacher_id => day => "HH:MM-HH:MM" => true
        $roomBusy    = []; // room_id    => day => "HH:MM-HH:MM" => true
        $classBusy   = []; // classroom_id => day => "HH:MM-HH:MM" => true

        // ============ Generate jadwal per kelas per hari ============
        foreach ($classrooms as $classroomId) {
            foreach ($this->days as $day) {
                // Ambil 4-6 slot acak untuk tiap hari (bukan semua slot)
                $maxSlots = $day === 'FRIDAY' ? 4 : rand(4, 6);
                $slots = $this->timeSlots;
                shuffle($slots);
                $slots = array_slice($slots, 0, $maxSlots);

                foreach ($slots as $slot) {
                    [$start, $end] = $slot;
                    $timeKey = "$start-$end";

                    // Pilih subject & teacher yang tidak bentrok
                    $subjectId = $this->pickNonConflicting(
                        $subjects,
                        fn($s) => !$this->hasConflict($classBusy[$classroomId][$day][$timeKey] ?? false)
                    );

                    // Pilih teacher acak (opsional, boleh null)
                    $teacherId = null;
                    if (!empty($teachers)) {
                        $candidates = array_values(array_filter($teachers, function ($tid) use ($teacherBusy, $day, $timeKey) {
                            return !isset($teacherBusy[$tid][$day][$timeKey]);
                        }));

                        // Jika ada guru tersedia untuk slot ini, pilih; kalau tidak, biarkan null
                        if (!empty($candidates)) {
                            shuffle($candidates);
                            $teacherId = $candidates[0];
                        }
                    }

                    // Cek bentrok guru — kalau terpaksa, cari yang lain; kalau tidak ada, skip
                    if ($teacherId !== null && isset($teacherBusy[$teacherId][$day][$timeKey])) {
                        $this->command->warn("Skip slot $day $timeKey di kelas $classroomId (bentrok guru).");
                        continue;
                    }

                    // Pilih room acak yang tidak bentrok
                    $roomId = null;
                    if (!empty($rooms)) {
                        $availableRooms = array_values(array_filter($rooms, function ($rid) use ($roomBusy, $day, $timeKey) {
                            return !isset($roomBusy[$rid][$day][$timeKey]);
                        }));

                        if (!empty($availableRooms)) {
                            shuffle($availableRooms);
                            $roomId = $availableRooms[0];
                        }
                    }

                    // Pilih generation_id (boleh null)
                    $generationId = !empty($generations) ? $generations[array_rand($generations)] : null;

                    // Susun nama jadwal
                    $dayLabel = ucfirst(strtolower($day));
                    $name = "Jadwal {$dayLabel} Kelas #{$classroomId} - " . substr($start, 0, 5);

                    $rows[] = [
                        'name'             => $name,
                        'classroom_id'     => $classroomId,
                        'subject_id'       => $subjectId,
                        'teacher_id'       => $teacherId,
                        'academic_year_id' => $academicYears[array_rand($academicYears)],
                        'day_of_week'      => $day,
                        'start_time'       => $start,
                        'end_time'         => $end,
                        'room_id'          => $roomId,
                        'generation_id'    => $generationId,
                        'is_locked'        => 0,
                        'notes'            => null,
                        'is_active'        => 1,
                        'created_at'       => $now,
                        'updated_at'       => $now,
                        'deleted_at'       => null,
                        'created_by'       => null,
                        'updated_by'       => null,
                    ];

                    // Tandai busy
                    $classBusy[$classroomId][$day][$timeKey] = true;
                    if ($teacherId !== null) {
                        $teacherBusy[$teacherId][$day][$timeKey] = true;
                    }
                    if ($roomId !== null) {
                        $roomBusy[$roomId][$day][$timeKey] = true;
                    }
                }
            }
        }

        if (empty($rows)) {
            $this->command->warn('ScheduleSeeder: tidak ada baris yang dihasilkan.');
            return;
        }

        // ============ Insert per chunk ============
        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('schedules')->insert($chunk);
        }

        $this->command->info('ScheduleSeeder: ' . count($rows) . ' jadwal berhasil dibuat.');
    }

    /**
     * Ambil satu item acak dari array.
     */
    protected function pickNonConflicting(array $items, callable $isFree): mixed
    {
        shuffle($items);
        return $items[0];
    }

    protected function hasConflict(bool $busy): bool
    {
        return $busy;
    }
}
