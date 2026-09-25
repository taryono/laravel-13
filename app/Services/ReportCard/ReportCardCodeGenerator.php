<?php
// app/Services/ReportCard/ReportCardCodeGenerator.php

namespace App\Services\ReportCard;

use Illuminate\Support\Facades\DB;

class ReportCardCodeGenerator
{
    /**
     * Generate kode unik raport.
     */
    public function generate(int $studentId, int $periodId): string
    {
        $period = DB::table('report_periods')
            ->join('academic_years', 'academic_years.id', '=', 'report_periods.academic_year_id')
            ->where('report_periods.id', $periodId)
            ->select('report_periods.semester', 'academic_years.name', 'report_periods.code')
            ->first();

        $schoolId = DB::table('students')
            ->where('id', $studentId)
            ->value('school_id') ?? 1;

        $yearCode = $this->extractYearCode($period->name ?? null);
        $semester = $period->semester ?? 1;
        $periodCode = $period->code ?? sprintf('P%02d', $periodId);

        return sprintf(
            'RC-%03d-%s-S%d-%05d-%s',
            $schoolId,
            $yearCode,
            $semester,
            $studentId,
            $periodCode
        );
    }

    /**
     * Ekstrak "2024/2025" → "2425".
     */
    protected function extractYearCode(?string $yearName): string
    {
        if ($yearName && preg_match('/(\d{4})\D+(\d{4})/', $yearName, $m)) {
            return substr($m[1], -2) . substr($m[2], -2);
        }

        return now()->format('y') . now()->addYear()->format('y');
    }
}
