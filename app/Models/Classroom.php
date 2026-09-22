<?php

namespace Models;

class Classroom extends BasedModel
{
    protected $fillable = [
        'organization_id',
        'class_level_id',
        'academic_year_id',
        'name',
        'code',
        'capacity',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function class_level()
    {
        return $this->belongsTo(ClassLevel::class);
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function homeroomAssignments()
    {
        return $this->hasMany(HomeroomTeacher::class);
    }

    public function currentHomeroomTeacher()
    {
        return $this->hasOne(HomeroomTeacher::class)
            ->where('is_active', true)
            ->latest();
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
