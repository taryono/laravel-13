<?php

namespace Models;

class AcademicCalendarEvent extends SoftDeletableModel
{

    protected $fillable = [
        'organization_id',
        'school_id',
        'academic_year_id',
        'academic_semester_id',
        'title',
        'description',
        'event_type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'is_holiday',
        'is_all_day',
        'color',
        'attachment_url',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'is_holiday' => 'boolean',
        'is_all_day' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relasi
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
