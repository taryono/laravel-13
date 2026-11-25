<?php

namespace App\Services;

use Models\ReportTemplate;
use Models\Student;
use Models\FinalScore;
use Models\SoroganSession;
use Models\MemorizationRecord;
use Models\PesantrenScore;
use Models\ParentStudent;
use Models\ParentGuardian;
use LightnCandy\LightnCandy;
use LightnCandy\Flags;
use App\Services\Report\ReportContext;
use App\Services\Report\ReportDataRegistry;
use Models\ReportCard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportRenderService
{
    public function __construct(protected ReportCompilerService $compiler, protected ReportDataRegistry $registry)
    {
    }

    public function renderForStudent(ReportTemplate $template, Student $student, int $academicYearId, int $semester): string
    {
        $ctx = ReportContext::forTemplate($template, $student, $academicYearId, $semester);
        $compiled = $this->compiler->compile($template->configuration, $template);

        return $this->bindData($compiled['full'], $this->registry->build($ctx));
    }

    /**
     * Bind data ke HTML menggunakan LightnCandy (Handlebars).
     * Delimiter pakai [[ ]] supaya tidak konflik Blade.
     */
    public function bindData(string $html, array $data): string
    {
        $strict = false;
        try {
            // Split HTML: pisahkan <style> dari body supaya tidak di-parse Handlebars
            // (CSS bisa berisi karakter yang bikin Handlebars bingung)
            $parts = preg_split('/(<style\b[^>]*>.*?<\/style>)/is', $html, -1, PREG_SPLIT_DELIM_CAPTURE);

            $rendered = '';
            foreach ($parts as $part) {
                // Skip <style> blocks — jangan compile
                if (preg_match('/^<style\b/i', $part)) {
                    $rendered .= $part;
                    continue;
                }

                $rendered .= $this->renderHandlebars($part, $data);
            }

            return $rendered;
        } catch (\Throwable $th) {
            if ($strict)
                throw $th;
            return $html;
        }
    }

    /**
     * Render satu potongan HTML dengan LightnCandy.
     */
    protected function renderHandlebars(string $template, array $data): string
    {
        try {
            $php = LightnCandy::compile($template, [
                'flags' => Flags::FLAG_HANDLEBARS
                    | Flags::FLAG_HANDLEBARSJS
                    | Flags::FLAG_ERROR_EXCEPTION
                    | Flags::FLAG_RUNTIMEPARTIAL,
                'delimiters' => ['[[', ']]'],
                'helpers' => [
                    'formatDate' => function ($context, $options) {
                        $date = $context;
                        if ($date instanceof \DateTimeInterface) {
                            return $date->format('d F Y');
                        }
                        return (string) $date;
                    },
                    'safeString' => function ($context, $options) {
                        if (is_array($context)) {
                            return implode(', ', array_filter($context, 'is_scalar'));
                        }
                        if (is_object($context)) {
                            return method_exists($context, '__toString')
                                ? (string) $context
                                : json_encode($context);
                        }
                        return (string) $context;
                    },
                ],
            ]);

            $render = LightnCandy::prepare($php);

            return $render($data);
        } catch (\Throwable $e) { // @phpstan-ignore-line
            Log::error('LightnCandy render error: ' . $e->getMessage(), [
                'template' => substr($template, 0, 500),
            ]);
            return $template; // fallback: return raw
        }
    }

    /**
     * Build data lengkap untuk semua binding.
     */
    public function buildData(Student $student, int $academicYearId, ReportTemplate $template): array
    {
        $student->loadMissing(['dormitory', 'activeClassroom.homeroomTeacher']);

        $academicScores = FinalScore::with(['classroomSubject.subject', 'predicate'])
            ->where('student_id', $student->id)
            ->where('academic_year_id', $academicYearId)
            ->get()
            ->map(function ($s, $i) {
                return [
                    'index' => $i + 1,
                    'subject' => ['name' => $s->classroomSubject->subject->name ?? '-'],
                    'assignment_score' => $s->assignment_score ?? 0,
                    'midterm_score' => $s->midterm_score ?? 0,
                    'final_score' => $s->final_score ?? 0,
                    'numeric_score' => $s->numeric_score,
                    'predicate' => $s->predicate->name ?? '-',
                ];
            })->toArray();

        $sorogan = SoroganSession::with('book')
            ->where('student_id', $student->id)
            ->where('academic_year_id', $academicYearId)
            ->get()
            ->map(fn($s, $i) => [
                'index' => $i + 1,
                'book' => ['title' => $s->book->title ?? '-'],
                'reading_score' => $s->reading_score,
                'translation_score' => $s->translation_score,
                'comprehension_score' => $s->comprehension_score,
            ])->toArray();

        $memorization = MemorizationRecord::with('memorizationType')
            ->where('student_id', $student->id)
            ->where('academic_year_id', $academicYearId)
            ->get()
            ->map(fn($m, $i) => [
                'index' => $i + 1,
                'type' => $m->memorizationType->name ?? '-',
                'portion' => $m->portion,
                'fluency_score' => $m->fluency_score,
                'tajweed_score' => $m->tajweed_score,
                'etiquette_score' => $m->etiquette_score,
            ])->toArray();

        $pesantrenScores = PesantrenScore::with('aspect')
            ->where('student_id', $student->id)
            ->where('academic_year_id', $academicYearId)
            ->get()
            ->map(fn($p, $i) => [
                'index' => $i + 1,
                'aspect' => $p->aspect->name ?? '-',
                'score' => $p->score,
                'predicate' => $this->predicateFromScore($p->score),
            ])->toArray();

        $average = count($academicScores) > 0
            ? round(collect($academicScores)->avg('numeric_score'), 2)
            : 0;

        return [
            'student' => [
                'full_name' => $student->full_name,
                'student_number' => $student->student_number,
                'national_student_number' => $student->national_student_number,
                'gender' => $student->gender,
                'place_of_birth' => $student->place_of_birth,
                'date_of_birth' => optional($student->date_of_birth)->format('d/m/Y'),
                'parent_name' => $student->parent_name,
            ],
            'classroom' => [
                'name' => $student->activeClassroom->name ?? '-',
                'grade' => $student->activeClassroom->grade ?? '-',
                'homeroom_teacher' => [
                    'full_name' => $student->activeClassroom->homeroomTeacher->full_name ?? '-',
                    'employee_number' => $student->activeClassroom->homeroomTeacher->employee_number ?? '-',
                ],
            ],
            'dormitory' => [
                'name' => $student->dormitory->name ?? '-',
                'building' => $student->dormitory->building ?? '-',
            ],
            'academic_year' => [
                'start_year' => $student->activeAcademicYear->start_year ?? '-',
                'end_year' => $student->activeAcademicYear->end_year ?? '-',
                'semester' => $student->activeAcademicYear->semester ?? '-',
            ],
            'report_card' => [
                'ranking' => $student->report_ranking ?? '-',
                'homeroom_notes' => $student->report_homeroom_notes ?? '',
                'headmaster_notes' => $student->report_headmaster_notes ?? '',
            ],
            'academic_scores' => $academicScores,
            'sorogan' => $sorogan,
            'memorization' => $memorization,
            'pesantren_scores' => $pesantrenScores,
            'average_score' => $average,
            'average_predicate' => $this->predicateFromScore($average),
            'summary' => [
                'average_score' => $average,
                'average_predicate' => $this->predicateFromScore($average),
                'print_date' => now()->format('d F Y'),
            ],
            'signatures' => $this->buildSignatures($template, $student),
            'settings' => [
                'pesantren_name' => config('app.name', 'Pondok Pesantren'),
                'headmaster_name' => '-',
            ],
            'print_date' => now()->format('d F Y'),
        ];
    }

    protected function buildSignatures(ReportTemplate $template, Student $student): array
    {
        $out = [];
        foreach ($template->signatories as $s) {
            $name = '';
            $employeeNumber = '';

            if ($s->data_source === 'automatic') {
                if ($s->role === 'homeroom_teacher') {
                    $name = $student->activeClassroom->homeroomTeacher->full_name ?? '';
                    $employeeNumber = $student->activeClassroom->homeroomTeacher->employee_number ?? '';
                } elseif ($s->teacher) {
                    $name = $s->teacher->full_name;
                    $employeeNumber = $s->teacher->employee_number;
                }
            } elseif ($s->data_source === 'manual') {
                $name = $s->manual_name ?? '';
                $employeeNumber = $s->manual_employee_number ?? '';
            }

            $out[$s->role] = [
                'name' => $name,
                'employee_number' => $employeeNumber,
                'image' => $s->signatureImage?->url ?? '',
                'label' => $s->label,
            ];
        }
        return $out;
    }

    protected function predicateFromScore(?float $score): string
    {
        if ($score === null)
            return '-';
        return match (true) {
            $score >= 90 => 'Mumtaz',
            $score >= 80 => 'Jayyid Jiddan',
            $score >= 70 => 'Jayyid',
            $score >= 60 => 'Maqbul',
            default => 'Rasib',
        };
    }

    public function finalize(ReportContext $ctx): ReportCard
    {
        $template = $ctx->template;

        if ($template->status !== 'published' && $template->status !== 'locked') {
            throw new \DomainException('Template belum dipublikasikan, rapor tidak bisa difinalkan.');
        }

        return DB::transaction(function () use ($ctx, $template) {
            $exists = ReportCard::where('student_id', $ctx->student->id)
                ->where('academic_year_id', $ctx->academicYearId)
                ->where('semester', $ctx->semester)
                ->where('status', 'final')
                ->exists();

            if ($exists) {
                throw new \DomainException('Rapor santri ini sudah difinalkan.');
            }

            $data = $this->registry->build($ctx); // hitung sekali

            $card = ReportCard::create([
                'student_id' => $ctx->student->id,
                'academic_year_id' => $ctx->academicYearId,
                'semester' => $ctx->semester,
                'template_id' => $template->id,
                'template_version' => $template->version,
                'template_config' => $template->configuration, // salinan beku
                'grading_rule_id' => $ctx->gradingRule?->id,
                'data' => $data,                    // snapshot beku
                'status' => 'final',
                'issued_at' => now(),
            ]);

            // Template otomatis dikunci setelah dipakai menerbitkan rapor
            if ($template->status !== 'locked') {
                $template->update(['status' => 'locked']);
            }

            return $card;
        });
    }

    public function renderFromSnapshot(ReportCard $card): string
    {
        $compiled = $this->compiler->compile($card->template_config);

        return $this->bindData($compiled['full'], $card->data);
    }
}