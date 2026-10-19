<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AcademicSemesterSeeder extends Seeder
{
    /**
     * Pola standar semester per tahun ajaran:
     * - Ganjil  : Juli - Desember
     * - Genap   : Januari - Juni (tahun berikutnya)
     * - Pendek  : Juli (libur pendek), opsional
     */
    public function run(): void
    {
        // ============ Ambil data referensi ============
        $schools = DB::table('schools')
            ->select('id', 'organization_id')
            ->get();

        $organizations = DB::table('organizations')->pluck('id')->toArray();

        // Academic years wajib ada
        $academicYears = DB::table('academic_years')
            ->select('id', 'name', 'start_date', 'end_date')
            ->get();

        if ($schools->isEmpty() || empty($organizations) || $academicYears->isEmpty()) {
            $this->command->warn('Skip AcademicSemesterSeeder: tabel schools, organizations, atau academic_years masih kosong.');
            return;
        }

        $now = now();
        $rows = [];

        // ============ Tracker unique (school_id + code) ============
        $usedCodes = []; // ['school_id' => ['GANJIL-2024' => true, ...]]

        foreach ($schools as $school) {
            $orgId = $school->organization_id ?? $organizations[array_rand($organizations)];

            // Ambil academic year yang relevan untuk sekolah ini.
            // Kalau academic_years punya school_id, filter; kalau global, pakai semua.
            $yearsForSchool = $academicYears;
            if (DB::getSchemaBuilder()->hasColumn('academic_years', 'school_id')) {
                $filtered = $academicYears->where('school_id', $school->id);
                if ($filtered->isNotEmpty()) {
                    $yearsForSchool = $filtered;
                }
            }

            foreach ($yearsForSchool as $year) {
                // Extract tahun dari nama, mis. "2024/2025" → 2024
                $startYear = $this->extractStartYear($year->name) ?? (int) Carbon::parse($year->start_date)->format('Y');

                // ---- Semester Ganjil ----
                $ganjilCode = 'GANJIL-' . $startYear;
                if (!isset($usedCodes[$school->id][$ganjilCode])) {
                    $rows[] = $this->buildRow(
                        schoolId: $school->id,
                        orgId: $orgId,
                        yearId: $year->id,
                        code: $ganjilCode,
                        name: 'Semester Ganjil ' . $year->name,
                        type: 'ganjil',
                        startDate: "{$startYear}-07-01",
                        endDate: "{$startYear}-12-31",
                        krsStart: "{$startYear}-06-15",
                        krsEnd: "{$startYear}-07-15",
                        isCurrent: false, // akan ditentukan belakangan
                        now: $now,
                    );
                    $usedCodes[$school->id][$ganjilCode] = true;
                }

                // ---- Semester Genap ----
                $genapCode = 'GENAP-' . $startYear;
                if (!isset($usedCodes[$school->id][$genapCode])) {
                    $nextYear = $startYear + 1;
                    $rows[] = $this->buildRow(
                        schoolId: $school->id,
                        orgId: $orgId,
                        yearId: $year->id,
                        code: $genapCode,
                        name: 'Semester Genap ' . $year->name,
                        type: 'genap',
                        startDate: "{$nextYear}-01-01",
                        endDate: "{$nextYear}-06-30",
                        krsStart: "{$nextYear}-01-02",
                        krsEnd: "{$nextYear}-01-15",
                        isCurrent: false,
                        now: $now,
                    );
                    $usedCodes[$school->id][$genapCode] = true;
                }

                // ---- Semester Pendek (opsional, 1 dari 3 tahun) ----
                if ($startYear % 3 === 0) {
                    $pendekCode = 'PENDEK-' . $startYear;
                    if (!isset($usedCodes[$school->id][$pendekCode])) {
                        $rows[] = $this->buildRow(
                            schoolId: $school->id,
                            orgId: $orgId,
                            yearId: $year->id,
                            code: $pendekCode,
                            name: 'Semester Pendek ' . $year->name,
                            type: 'pendek',
                            startDate: "{$startYear}-07-01",
                            endDate: "{$startYear}-08-15",
                            krsStart: "{$startYear}-06-20",
                            krsEnd: "{$startYear}-06-30",
                            isCurrent: false,
                            now: $now,
                        );
                        $usedCodes[$school->id][$pendekCode] = true;
                    }
                }
            }
        }

        if (empty($rows)) {
            $this->command->warn('AcademicSemesterSeeder: tidak ada baris yang dihasilkan.');
            return;
        }

        // ============ Insert per chunk ============
        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('academic_semesters')->insert($chunk);
        }

        $this->command->info('AcademicSemesterSeeder: ' . count($rows) . ' semester berhasil dibuat.');

        // ============ Set is_current untuk tiap sekolah ============
        $this->markCurrentSemester();

        $this->command->info('AcademicSemesterSeeder: is_current berhasil disetel per sekolah.');
    }

    /**
     * Bangun 1 baris semester.
     */
    protected function buildRow(
        int $schoolId,
        int $orgId,
        int $yearId,
        string $code,
        string $name,
        string $type,
        string $startDate,
        string $endDate,
        ?string $krsStart,
        ?string $krsEnd,
        bool $isCurrent,
        Carbon $now
    ): array {
        return [
            'organization_id'  => $orgId,
            'school_id'        => $schoolId,
            'academic_year_id' => $yearId,
            'code'             => $code,
            'name'             => $name,
            'type'             => $type,
            'start_date'       => $startDate,
            'end_date'         => $endDate,
            'krs_start_date'   => $krsStart,
            'krs_end_date'     => $krsEnd,
            'is_active'        => 1,
            'is_current'       => $isCurrent ? 1 : 0,
            'created_at'       => $now,
            'updated_at'       => $now,
        ];
    }

    /**
     * Tandai satu semester sebagai `is_current = 1` per sekolah.
     * Prioritas:
     *   1. Semester yang tanggalnya mencakup hari ini
     *   2. Jika tidak ada, semester terbaru yang sudah dimulai (start_date <= hari ini)
     *   3. Jika semua di masa depan, semester paling awal
     */
    protected function markCurrentSemester(): void
    {
        $today = Carbon::today()->format('Y-m-d');

        $schoolIds = DB::table('academic_semesters')
            ->distinct()
            ->pluck('school_id');

        foreach ($schoolIds as $schoolId) {
            // Reset dulu semua is_current untuk sekolah ini
            DB::table('academic_semesters')
                ->where('school_id', $schoolId)
                ->update(['is_current' => 0]);

            // 1. Cari yang mencakup hari ini
            $currentId = DB::table('academic_semesters')
                ->where('school_id', $schoolId)
                ->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->orderBy('start_date', 'desc')
                ->value('id');

            // 2. Fallback: semester terbaru yang sudah dimulai
            if (!$currentId) {
                $currentId = DB::table('academic_semesters')
                    ->where('school_id', $schoolId)
                    ->where('start_date', '<=', $today)
                    ->orderBy('start_date', 'desc')
                    ->value('id');
            }

            // 3. Fallback: semester paling awal (kalau semua di masa depan)
            if (!$currentId) {
                $currentId = DB::table('academic_semesters')
                    ->where('school_id', $schoolId)
                    ->orderBy('start_date', 'asc')
                    ->value('id');
            }

            if ($currentId) {
                DB::table('academic_semesters')
                    ->where('id', $currentId)
                    ->update(['is_current' => 1]);
            }
        }
    }

    /**
     * Extract "start year" dari nama academic year seperti "2024/2025".
     */
    protected function extractStartYear(?string $name): ?int
    {
        if (empty($name)) {
            return null;
        }

        if (preg_match('/(\d{4})/', $name, $m)) {
            return (int) $m[1];
        }

        return null;
    }
}
