<?php

namespace App\Services\Report\Providers;

use Models\FinalScore;
use App\Services\Grading\GradingService;
use App\Services\Report\Contracts\ReportDataProvider;
use App\Services\Report\ReportContext;

class AcademicScoresProvider implements ReportDataProvider
{
    public function __construct(
        protected GradingService $grading
    ) {
    }

    public function key(): string
    {
        return 'academic_scores';
    }

    public function label(): string
    {
        return 'Nilai Akademik';
    }

    public function supports(ReportContext $ctx): bool
    {
        return true;
    }

    public function build(ReportContext $ctx): array
    {
        $rule = $ctx->gradingRule;

        $rows = FinalScore::with('classroomSubject.subject')
            ->where('student_id', $ctx->student->id)
            ->where('academic_year_id', $ctx->academicYearId)
            ->where('semester', $ctx->semester) // filter semester dari context, bukan relasi "active"
            ->get()
            ->sortBy([
                fn($a, $b) => ($a->classroomSubject->subject->sort_order ?? PHP_INT_MAX)
                    <=> ($b->classroomSubject->subject->sort_order ?? PHP_INT_MAX),
                fn($a, $b) => strcmp(
                    $a->classroomSubject->subject->name ?? '',
                    $b->classroomSubject->subject->name ?? ''
                ),
            ])
            ->values();

        $items = $rows->map(function (FinalScore $s, int $i) use ($rule) {
            $numeric = $s->numeric_score !== null
                ? $this->grading->round((float) $s->numeric_score, $rule)
                : null;

            $grade = $this->grading->resolve($numeric, $rule);

            return [
                'index' => $i + 1,
                'subject' => ['name' => $s->classroomSubject->subject->name ?? '-'],
                // komponen (tetap dipertahankan agar template lama tidak rusak)
                'assignment_score' => $s->assignment_score ?? 0,
                'midterm_score' => $s->midterm_score ?? 0,
                'final_score' => $s->final_score ?? 0,
                // hasil akhir
                'numeric_score' => $numeric,
                'letter' => $grade['letter'],
                'predicate' => $grade['predicate'],
                'description' => $grade['description'],
            ];
        })->all();

        $numericValues = collect($items)->pluck('numeric_score')->filter(fn($v) => $v !== null);

        $average = $numericValues->isNotEmpty()
            ? $this->grading->round((float) $numericValues->avg(), $rule)
            : null;

        $averageGrade = $this->grading->resolve($average, $rule);

        return [
            'academic_scores' => $items,
            'total_score' => $numericValues->isNotEmpty() ? $numericValues->sum() : null,
            'average_score' => $average ?? 0,
            'average_predicate' => $averageGrade['predicate'],
            'average_letter' => $averageGrade['letter'],
        ];
    }

    public function schema(): array
    {
        return [
            'academic_scores' => [
                'type' => 'collection',
                'label' => 'Daftar Nilai Mata Pelajaran',
                'fields' => [
                    ['field' => 'index', 'label' => 'No', 'type' => 'number'],
                    ['field' => 'subject.name', 'label' => 'Mata Pelajaran', 'type' => 'string'],
                    ['field' => 'assignment_score', 'label' => 'Nilai Tugas', 'type' => 'number'],
                    ['field' => 'midterm_score', 'label' => 'Nilai UTS', 'type' => 'number'],
                    ['field' => 'final_score', 'label' => 'Nilai UAS', 'type' => 'number'],
                    ['field' => 'numeric_score', 'label' => 'Nilai Akhir', 'type' => 'number'],
                    ['field' => 'letter', 'label' => 'Huruf', 'type' => 'string'],
                    ['field' => 'predicate', 'label' => 'Predikat', 'type' => 'string'],
                    ['field' => 'description', 'label' => 'Deskripsi', 'type' => 'string'],
                ],
            ],
            'total_score' => [
                'type' => 'scalar',
                'label' => 'Jumlah Nilai',
                'valueType' => 'number',
            ],
            'average_score' => [
                'type' => 'scalar',
                'label' => 'Rata-rata',
                'valueType' => 'number',
            ],
            'average_predicate' => [
                'type' => 'scalar',
                'label' => 'Predikat Rata-rata',
                'valueType' => 'string',
            ],
            'average_letter' => [
                'type' => 'scalar',
                'label' => 'Huruf Rata-rata',
                'valueType' => 'string',
            ],
        ];
    }
}