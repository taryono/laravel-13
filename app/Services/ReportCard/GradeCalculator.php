<?php
// app/Services/ReportCard/GradeCalculator.php

namespace App\Services\ReportCard;

use Models\Assessment;
use Models\GradeScale;
use Models\GradeWeightConfig;
use Models\ReportCardGrade;
use Models\StudentAssessment;
use Illuminate\Support\Facades\DB;

class GradeCalculator
{
    /**
     * Hitung nilai akhir per mapel untuk seorang siswa.
     * Return array of ['subject_id', 'curriculum_structure_id', 'final_score', ...]
     */
    public function calculateForStudent(int $studentId, int $academicYearId, int $semester, int $curriculumId): array
    {
        // 1. Ambil bobot default per assessment type
        $weights = GradeWeightConfig::where('curriculum_id', $curriculumId)
            ->where('academic_year_id', $academicYearId)
            ->where('deleted_at', null)
            ->pluck('weight', 'assessment_type_id')
            ->toArray();

        // 2. Ambil semua nilai siswa beserta info assessment & structure
        $rows = DB::table('student_assessments as sa')
            ->join('assessments as a', 'a.id', '=', 'sa.assessment_id')
            ->join('curriculum_structures as cs', 'cs.id', '=', 'a.curriculum_structure_id')
            ->leftJoin('curriculum_learning_outcomes as clo', function ($join) {
                $join->on('clo.subject_id', '=', 'cs.subject_id')
                    ->on('clo.curriculum_phase_id', '=', 'cs.curriculum_phase_id');
            })
            ->where('sa.student_id', $studentId)
            ->where('sa.deleted_at', null)
            ->where('a.deleted_at', null)
            ->where('cs.academic_year_id', $academicYearId)
            ->where('cs.semester', $semester)
            ->where('cs.curriculum_id', $curriculumId)
            ->select([
                'cs.subject_id',
                'cs.id as curriculum_structure_id',
                'cs.curriculum_id',
                'a.assessment_type_id',
                'a.weight as assessment_weight',
                'sa.score',
                'clo.element',
            ])
            ->get();

        // 3. Group per subject
        $grouped = $rows->groupBy('subject_id');
        $result = [];

        foreach ($grouped as $subjectId => $items) {
            $weightedSum = 0;
            $weightTotal = 0;
            $knowledgeScores = [];
            $skillScores = [];
            $allScores = [];

            foreach ($items as $item) {
                $w = $weights[$item->assessment_type_id]
                    ?? $item->assessment_weight
                    ?? 0;

                if ($w > 0) {
                    $weightedSum += $item->score * ($w / 100);
                    $weightTotal += ($w / 100);
                }

                $allScores[] = $item->score;

                if ($item->element === 'pengetahuan') $knowledgeScores[] = $item->score;
                if ($item->element === 'keterampilan') $skillScores[] = $item->score;
            }

            $finalScore = $weightTotal > 0
                ? round($weightedSum / $weightTotal, 2)
                : round(array_sum($allScores) / max(count($allScores), 1), 2);

            $result[] = [
                'subject_id'                => $subjectId,
                'curriculum_structure_id'   => $items->first()->curriculum_structure_id,
                'curriculum_id'             => $items->first()->curriculum_id,
                'knowledge_score'           => $knowledgeScores ? round(array_sum($knowledgeScores) / count($knowledgeScores), 2) : null,
                'skill_score'               => $skillScores ? round(array_sum($skillScores) / count($skillScores), 2) : null,
                'final_score'               => $finalScore,
            ];
        }

        return $result;
    }

    /**
     * Cari predikat dari grade_scales.
     */
    public function resolvePredicate(?float $score, int $curriculumId): array
    {
        if ($score === null) {
            return ['letter' => null, 'point' => null, 'description' => null];
        }

        $scale = GradeScale::where('curriculum_id', $curriculumId)
            ->where('deleted_at', null)
            ->where('is_active', 1)
            ->where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->orderBy('order')
            ->first();

        return $scale ? [
            'letter'      => $scale->grade_letter,
            'point'       => $scale->grade_point,
            'description' => $scale->description,
        ] : ['letter' => null, 'point' => null, 'description' => null];
    }

    /**
     * Ambil deskripsi capaian (dari CP/KD) tertinggi & terendah.
     */
    public function resolveDescription(int $studentId, int $subjectId, int $academicYearId, int $semester): ?string
    {
        $rows = DB::table('student_competencies as sc')
            ->join('curriculum_learning_outcomes as clo', 'clo.id', '=', 'sc.curriculum_learning_outcome_id')
            ->where('sc.student_id', $studentId)
            ->where('sc.academic_year_id', $academicYearId)
            ->where('sc.semester', $semester)
            ->where('clo.subject_id', $subjectId)
            ->where('sc.deleted_at', null)
            ->orderByDesc('sc.score')
            ->limit(2)
            ->pluck('clo.description')
            ->toArray();

        if (empty($rows)) return null;

        return 'Menguasai ' . implode('; ', $rows) . '.';
    }
}
