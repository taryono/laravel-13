<?php
// app/Services/ReportCard/DataCollector.php

namespace App\Services\ReportCard;

use Models\AttitudeAssessment;
use Models\GeneratedReport;
use Models\ReportCardGrade;
use Models\ReportPeriod;
use Models\Student;
use Models\StudentAttendance;
use Models\StudentCurriculum;
use Models\StudentExtracurricular;
use Illuminate\Support\Facades\DB;

class DataCollector
{
    public function __construct(
        protected GradeCalculator $calculator,
    ) {}

    /**
     * Kumpulkan semua data untuk render raport.
     */
    public function collect(int $studentId, int $periodId): array
    {
        $period = ReportPeriod::findOrFail($periodId);
        $student = Student::with('classLevel')->findOrFail($studentId);

        $studentCurriculum = StudentCurriculum::where('student_id', $studentId)
            ->where('academic_year_id', $period->academic_year_id)
            ->where('deleted_at', null)
            ->firstOrFail();

        $curriculumId = $studentCurriculum->curriculum_id;
        $academicYearId = $period->academic_year_id;
        $semester = $period->semester;

        // 1. Hitung nilai per mapel
        $gradeRows = $this->calculator->calculateForStudent(
            $studentId,
            $academicYearId,
            $semester,
            $curriculumId
        );

        // 2. Ambil nama mapel & enrich dengan predikat + deskripsi
        $subjectIds = collect($gradeRows)->pluck('subject_id')->unique()->toArray();
        $subjects = DB::table('subjects')->whereIn('id', $subjectIds)->pluck('name', 'id');

        $grades = [];
        foreach ($gradeRows as $row) {
            $finalPred = $this->calculator->resolvePredicate($row['final_score'], $curriculumId);
            $knowPred  = $this->calculator->resolvePredicate($row['knowledge_score'], $curriculumId);
            $skillPred = $this->calculator->resolvePredicate($row['skill_score'], $curriculumId);

            $grades[] = [
                'subject_id'                => $row['subject_id'],
                'subject_name'              => $subjects[$row['subject_id']] ?? '-',
                'curriculum_structure_id'   => $row['curriculum_structure_id'],
                'knowledge_score'           => $row['knowledge_score'],
                'knowledge_predicate'       => $knowPred['letter'],
                'knowledge_description'     => $this->calculator->resolveDescription(
                    $studentId,
                    $row['subject_id'],
                    $academicYearId,
                    $semester
                ),
                'skill_score'               => $row['skill_score'],
                'skill_predicate'           => $skillPred['letter'],
                'skill_description'         => null,
                'final_score'               => $row['final_score'],
                'final_predicate'           => $finalPred['letter'],
            ];
        }

        // 3. Sikap
        $attitudes = AttitudeAssessment::where('student_id', $studentId)
            ->where('academic_year_id', $academicYearId)
            ->where('semester', $semester)
            ->where('deleted_at', null)
            ->get()
            ->keyBy('aspect');

        $attitude = [
            'spiritual_predicate'   => $attitudes['spiritual']->predicate ?? null,
            'spiritual_description' => $attitudes['spiritual']->description ?? null,
            'social_predicate'      => $attitudes['sosial']->predicate ?? null,
            'social_description'    => $attitudes['sosial']->description ?? null,
        ];

        // 4. Kehadiran
        $att = StudentAttendance::where('student_id', $studentId)
            ->where('academic_year_id', $academicYearId)
            ->where('semester', $semester)
            ->where('deleted_at', null)
            ->first();

        $attendance = [
            'sick'       => $att->sick ?? 0,
            'permission' => $att->permission ?? 0,
            'absent'     => $att->absent ?? 0,
            'late'       => $att->late ?? 0,
            'present'    => $att->present ?? 0,
        ];

        // 5. Ekskul
        $extracurricular = StudentExtracurricular::with('extracurricular')
            ->where('student_id', $studentId)
            ->where('academic_year_id', $academicYearId)
            ->where('deleted_at', null)
            ->get()
            ->map(fn($e) => [
                'name'        => $e->extracurricular->name ?? '-',
                'predicate'   => $e->predicate,
                'description' => $e->description,
            ])
            ->toArray();

        // 6. P5 (jika Merdeka)
        $p5 = [];
        if ($curriculumId === 3) {
            // ambil dari student_competencies yang element = 'sikap' atau tabel khusus P5
            $p5 = DB::table('student_competencies as sc')
                ->join('curriculum_learning_outcomes as clo', 'clo.id', '=', 'sc.curriculum_learning_outcome_id')
                ->where('sc.student_id', $studentId)
                ->where('sc.academic_year_id', $academicYearId)
                ->where('clo.code', 'like', 'MERDEKA-P5-%')
                ->where('sc.deleted_at', null)
                ->select('clo.description as project_name', 'sc.predicate', 'sc.description')
                ->get()
                ->map(fn($r) => [
                    'project_name' => $r->project_name,
                    'predicate'    => $r->predicate,
                    'description'  => $r->description,
                ])
                ->toArray();
        }

        // 7. Mastery (jika Montessori)
        $mastery = [];
        if ($curriculumId === 5) {
            $mastery = DB::table('mastery_tracking as mt')
                ->join('curriculum_objectives as co', 'co.id', '=', 'mt.curriculum_objective_id')
                ->join('curriculum_learning_outcomes as clo', 'clo.id', '=', 'co.learning_outcome_id')
                ->join('subjects as s', 's.id', '=', 'clo.subject_id')
                ->where('mt.student_id', $studentId)
                ->where('mt.academic_year_id', $academicYearId)
                ->where('mt.deleted_at', null)
                ->select([
                    's.name as area',
                    'co.description as objective',
                    'mt.status',
                    'mt.mastered_at',
                    'mt.observation_notes as notes',
                ])
                ->get()
                ->toArray();
        }

        return [
            'student' => [
                'id'            => $student->id,
                'name'          => $student->name,
                'nis'           => $student->nis,
                'class_level'   => $student->classLevel->name ?? '-',
                'academic_year' => $period->academicYear->name ?? '-',
                'semester'      => $semester,
                'phase'         => '-',
            ],
            'curriculum_id'   => $curriculumId,
            'grades'          => $grades,
            'attitude'        => $attitude,
            'attendance'      => $attendance,
            'extracurricular' => $extracurricular,
            'p5'              => $p5,
            'mastery'         => $mastery,
            'notes'           => [
                'homeroom_note' => null,
                'principal_note' => null,
            ],
            'signature' => [
                'homeroom_teacher' => null,
                'principal'        => null,
                'date'             => now()->translatedFormat('d F Y'),
            ],
        ];
    }

    /**
     * Persist hasil agregasi ke report_card_grades.
     */
    public function persistGrades(int $generatedReportId, int $schoolId, array $grades, int $curriculumId): void
    {
        ReportCardGrade::where('generated_report_id', $generatedReportId)->forceDelete();

        foreach ($grades as $g) {
            ReportCardGrade::create([
                'school_id'                 => $schoolId,
                'generated_report_id'       => $generatedReportId,
                'subject_id'                => $g['subject_id'],
                'curriculum_structure_id'   => $g['curriculum_structure_id'],
                'knowledge_score'           => $g['knowledge_score'],
                'knowledge_predicate'       => $g['knowledge_predicate'],
                'knowledge_description'     => $g['knowledge_description'],
                'skill_score'               => $g['skill_score'],
                'skill_predicate'           => $g['skill_predicate'],
                'skill_description'         => $g['skill_description'],
                'final_score'               => $g['final_score'],
                'final_predicate'           => $g['final_predicate'],
                'is_active'                 => 1,
            ]);
        }
    }
}
