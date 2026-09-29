<?php

namespace Models;

class School extends BasedModel
{
    protected $fillable = [
        'organization_id',
        'name',
        'npsn',
        'total_student',
        'address',
        'status',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'status' => 'string',
        'total_student' => 'integer',
    ];
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'school_modules');
    }
    public function education_level()
    {
        return $this->belongsToMany(EducationLevel::class, 'school_education_level');
    }
    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class, 'school_id', 'id');
    }
}
