<?php

namespace Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\LogsActivity;

class AcademicSemester extends SoftDeletableModel
{
    use LogsActivity;
    protected $fillable = [
        'organization_id',
        'school_id',
        'academic_year_id',
        'code',
        'name',
        'type',
        'start_date',
        'end_date',
        'krs_start_date',
        'krs_end_date',
        'is_active',
        'is_current',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'krs_start_date' => 'date',
            'krs_end_date' => 'date',
            'is_active' => 'boolean',
            'is_current' => 'boolean',
        ];
    }
    // Relasi
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function parentSemester()
    {
        return $this->belongsTo(AcademicSemester::class, 'parent_semester_id');
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    protected function startDateFormatted(): Attribute
    {
        return Attribute::get(fn() => $this->start_date?->translatedFormat('d F Y'));
    }

    protected function endDateFormatted(): Attribute
    {
        return Attribute::get(fn() => $this->end_date?->translatedFormat('d F Y'));
    }

    protected function krsStartDateFormatted(): Attribute
    {
        return Attribute::get(fn() => $this->krs_start_date?->translatedFormat('d F Y'));
    }

    protected function krsEndDateFormatted(): Attribute
    {
        return Attribute::get(fn() => $this->krs_end_date?->translatedFormat('d F Y'));
    }
}
