<?php

namespace Models;

class HomeroomTeacher extends SoftDeletableModel
{
    // Relasi
    protected $fillable = [
        'organization_id',
        'teacher_id',
        'classroom_id',
        'academic_year_id',
        'is_active',
        'assignment_date',
        'end_date',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'assignment_date' => 'date',
        'end_date' => 'date',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
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
}
