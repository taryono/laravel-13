<?php
// app/Services/ReportCard/ReportCardRenderer.php

namespace App\Services\ReportCard;

use Models\GeneratedReport;
use Models\ReportCard;
use Models\ReportCardTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportCardRenderer
{
    public function __construct(
        protected DataCollector $collector,
        protected TemplateParser $parser,
        protected PdfGenerator $pdf,
        protected ReportCardCalculator $calculator,
        protected ReportCardCodeGenerator $codeGenerator,
    ) {}

    public function generate(int $studentId, int $periodId, ?int $templateId = null): ReportCard
    {
        return DB::transaction(function () use ($studentId, $periodId, $templateId) {

            $data = $this->collector->collect($studentId, $periodId);

            $template = $templateId
                ? ReportCardTemplate::findOrFail($templateId)
                : $this->pickTemplate($data['curriculum_id']);

            // 1. Upsert report_cards
            $reportCard = ReportCard::updateOrCreate(
                ['student_id' => $studentId, 'period_id' => $periodId],
                [
                    'school_id'               => $template->school_id,
                    'academic_year_id'        => $data['period']['academic_year_id'],
                    'class_level_id'          => $data['student']['class_level_id'],
                    'curriculum_id'           => $data['curriculum_id'],
                    'report_card_template_id' => $template->id,
                    'student_curriculum_id'   => $data['student_curriculum_id'] ?? null,
                    'code'                    => $this->codeGenerator->generate($studentId, $periodId),
                    'semester'                => $data['student']['semester'],
                    'status'                  => 'draft',
                    'average_score'           => $this->calculator->average($data['grades']),
                    'total_score'             => $this->calculator->total($data['grades']),
                    'total_subjects'          => count($data['grades']),
                    'homeroom_teacher_id'     => $data['homeroom_teacher_id'] ?? null,
                    'principal_id'            => $data['principal_id'] ?? null,
                    'is_active'               => 1,
                ]
            );

            // 2. Snapshot nilai
            $this->collector->persistGrades(
                $reportCard->id,
                $template->school_id,
                $data['grades'],
                $data['curriculum_id']
            );

            // 3. Nonaktifkan versi lama
            $reportCard->generatedReports()
                ->where('is_current', 1)
                ->update(['is_current' => 0]);

            // 4. Versi baru
            $nextVersion = ($reportCard->generatedReports()->max('version') ?? 0) + 1;

            $generated = GeneratedReport::create([
                'school_id'               => $template->school_id,
                'report_card_id'          => $reportCard->id,
                'student_id'              => $studentId,
                'period_id'               => $periodId,
                'report_card_template_id' => $template->id,
                'curriculum_id'           => $data['curriculum_id'],
                'version'                 => $nextVersion,
                'is_current'              => 1,
                'report_data'             => $data,
                'generated_at'            => now(),
                'is_active'               => 1,
            ]);

            // 5. Render PDF
            $filename = sprintf(
                'raport_%d_%d_v%d_%s.pdf',
                $studentId,
                $periodId,
                $nextVersion,
                now()->format('YmdHis')
            );

            $pdfPath = $this->pdf->generate($template->template_data, $data, $filename);
            $generated->update(['pdf_path' => $pdfPath]);

            Log::info('Report card generated', [
                'report_card_id' => $reportCard->id,
                'generated_id'   => $generated->id,
                'version'        => $nextVersion,
            ]);

            return $reportCard->fresh();
        });
    }

    // ============================================================
    // HELPER: calculateAverage
    // ============================================================
    /**
     * Hitung rata-rata nilai akhir dari semua mapel.
     * Hanya mapel dengan final_score tidak null yang dihitung.
     *
     * @param  array  $grades  Array of ['final_score' => float, ...]
     * @return float|null
     */
    protected function calculateAverage(array $grades): ?float
    {
        $scores = array_filter(
            array_column($grades, 'final_score'),
            fn($v) => $v !== null && $v !== ''
        );

        if (empty($scores)) {
            return null;
        }

        return round(array_sum($scores) / count($scores), 2);
    }

    // ============================================================
    // HELPER: calculateTotal
    // ============================================================
    /**
     * Hitung total nilai akhir dari semua mapel.
     *
     * @param  array  $grades
     * @return float|null
     */
    protected function calculateTotal(array $grades): ?float
    {
        $scores = array_filter(
            array_column($grades, 'final_score'),
            fn($v) => $v !== null && $v !== ''
        );

        return empty($scores) ? null : round(array_sum($scores), 2);
    }

    // ============================================================
    // HELPER: generateCode
    // ============================================================
    /**
     * Generate kode unik raport.
     * Format: RC-{SCHOOL}-{YEAR}-{SEMESTER}-{STUDENT}-{PERIOD}
     * Contoh: RC-001-2425-S1-00042-P01
     *
     * @param  int  $studentId
     * @param  int  $periodId
     * @return string
     */
    protected function generateCode(int $studentId, int $periodId): string
    {
        $period = DB::table('report_periods')
            ->join('academic_years', 'academic_years.id', '=', 'report_periods.academic_year_id')
            ->where('report_periods.id', $periodId)
            ->select('report_periods.semester', 'academic_years.name', 'report_periods.code')
            ->first();

        $schoolId = DB::table('students')
            ->where('id', $studentId)
            ->value('school_id') ?? 1;

        // Ambil tahun ajaran → format 2425 (dari 2024/2025)
        $yearName = $period->name ?? '';
        preg_match('/(\d{4})\D+(\d{4})/', $yearName, $m);
        $yearCode = $m
            ? substr($m[1], -2) . substr($m[2], -2)
            : now()->format('y') . now()->addYear()->format('y');

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
     * Pilih template default berdasarkan kurikulum.
     */
    protected function pickTemplate(int $curriculumId): ReportCardTemplate
    {
        $template = ReportCardTemplate::query()
            ->whereHas('curriculums', fn($q) => $q->where('curriculums.id', $curriculumId))
            ->where('is_active', 1)
            ->where('deleted_at', null)
            ->orderByDesc('is_default')
            ->orderByDesc('version')
            ->first();

        if (!$template) {
            throw new \RuntimeException("Template raport tidak ditemukan untuk curriculum_id={$curriculumId}");
        }

        return $template;
    }

    public function generateBulk(array $studentIds, int $periodId): array
    {
        $results = [];
        foreach ($studentIds as $studentId) {
            try {
                $results[] = $this->generate($studentId, $periodId);
            } catch (\Throwable $e) {
                Log::error("Gagal generate raport student_id={$studentId}", [
                    'error' => $e->getMessage(),
                ]);
                $results[] = null;
            }
        }

        // ← Setelah semua ter-generate, hitung ranking
        $rankCalculator = app(ReportCardRankCalculator::class);
        $classIds = collect($results)
            ->filter()
            ->pluck('class_level_id')
            ->unique()
            ->toArray();

        foreach ($classIds as $classId) {
            $rankCalculator->calculateForClass($classId, $periodId);
        }

        return $results;
    }
}
