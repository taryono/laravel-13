<?php

namespace Models;

class Period extends SoftDeletableModel
{

    // ─── Assessment Type Constants ───
    public const SEMESTER = 'Semester';
    public const QUARTER = 'Quarter';
    public const TERM = 'Term';

    public const SEMESTER_TYPES = [
        self::SEMESTER,
        self::QUARTER,
        self::TERM,
    ];

    // ─── Assessment Type Constants ───
    public const SUMATIF = 'Sumatif';
    public const FORMATIF = 'Formatif';

    public const ASSESSMENT_TYPES = [
        self::SUMATIF,
        self::FORMATIF,
    ];

    // ─── Assessment Type Constants ───
    public const SEMESTER_1 = '1';
    public const SEMESTER_2 = '2';
    public const SEMESTER_3 = '3';
    public const SEMESTER_4 = '4';

    public const SEMESTER_NUMBERS = [
        self::SEMESTER_1,
        self::SEMESTER_2,
        self::SEMESTER_3,
        self::SEMESTER_4,
    ];

    // Relasi
    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}