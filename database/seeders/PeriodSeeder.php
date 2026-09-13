<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Ambil semua academic_years yang sudah di-seed
        $academicYears = DB::table('academic_years')
            ->whereNull('deleted_at')
            ->orderBy('start_date')
            ->get();

        if ($academicYears->isEmpty()) {
            $this->command->warn('⚠️  PeriodSeeder: Tidak ada academic_years. Jalankan AcademicYearSeeder dulu.');
            return;
        }

        $totalInserted = 0;

        foreach ($academicYears as $year) {
            $isGanjil = str_contains($year->code, 'GANJIL');

            // Tentukan range tanggal period berdasarkan semester
            if ($isGanjil) {
                $startDate    = $year->semester_start_date ?? $year->start_date;
                $endDate      = $year->semester_end_date   ?? $year->end_date;
                $gradingStart = Carbon::parse($startDate)->addWeeks(8)->toDateString();
                $gradingEnd   = Carbon::parse($endDate)->subWeeks(2)->toDateString();
                $semester     = 1;
                $periodName   = 'Semester Ganjil';
                $periodCode   = $year->code . '-S1';
            } else {
                $startDate    = $year->semester_start_date ?? $year->start_date;
                $endDate      = $year->semester_end_date   ?? $year->end_date;
                $gradingStart = Carbon::parse($startDate)->addWeeks(8)->toDateString();
                $gradingEnd   = Carbon::parse($endDate)->subWeeks(2)->toDateString();
                $semester     = 2;
                $periodName   = 'Semester Genap';
                $periodCode   = $year->code . '-S2';
            }

            DB::table('periods')->updateOrInsert(
                [
                    'academic_year_id' => $year->id,
                    'code'             => $periodCode,
                ],
                [
                    'academic_year_id' => $year->id,
                    'name'             => $periodName,
                    'code'             => $periodCode,
                    'type'             => 'semester',
                    'semester'         => $semester,
                    'start_date'       => $startDate,
                    'end_date'         => $endDate,
                    'grading_start'    => $gradingStart,
                    'grading_end'      => $gradingEnd,
                    'assessment_type'  => 'summative',
                    'weight'           => 1,
                    'is_active'        => $year->is_current ? 1 : 0,
                    'created_by'       => null,
                    'updated_by'       => null,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ]
            );

            $totalInserted++;
        }

        $this->command->info("✅ PeriodSeeder: {$totalInserted} period berhasil di-seed.");
    }
}