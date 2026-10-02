<?php

namespace App\Services\Report;

use App\Models\GradingRule;
use App\Models\ReportTemplate;
use App\Models\Student;

final class ReportContext
{
    public function __construct(
        public Student $student,
        public int $academicYearId,
        public int $semester,
        public ReportTemplate $template,
        public ?GradingRule $gradingRule = null,
        public array $options = [],
    ) {
    }

    public static function forTemplate(
        ReportTemplate $template,
        Student $student,
        int $academicYearId,
        int $semester,
        array $options = [],
    ): self {
        return new self(
            student: $student,
            academicYearId: $academicYearId,
            semester: $semester,
            template: $template,
            gradingRule: $template->gradingRule,
            options: $options,
        );
    }

    public function withStudent(Student $student): self
    {
        $clone = clone $this;
        $clone->student = $student;

        return $clone;
    }

    public function option(string $key, mixed $default = null): mixed
    {
        return $this->options[$key] ?? $default;
    }
}