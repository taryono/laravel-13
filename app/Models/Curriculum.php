<?php

namespace Models;

class Curriculum extends SoftDeletableModel
{
    protected $table = 'curriculums';
    protected $fillable = [
        'organization_id',
        'name',
        'code',
        'type',
        'description',
        'start_year',
        'end_year',
    ];

    protected $casts = [
        'start_year' => 'integer',
        'end_year' => 'integer',
    ];
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function report_card()
    {
        return $this->hasMany(ReportCard::class);
    }

    public function curriculum_assignments()
    {
        return $this->hasMany(CurriculumAssignment::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
