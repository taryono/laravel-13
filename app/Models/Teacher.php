<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends BasedModel
{
    protected $fillable = [
        'employee_id',
        'user_id',
        'department_id',
        'position_id',
        'supervisor_id',
        'name',
        'email',
        'phone',
        'address',
        'gender',
        'is_active',
        'last_login',
        'avatar_url',
        'employee_type',
        'nip',
        'nidn',
        'education_level',
        'degree_name',
        'joining_date',
        'status',
        'base_salary',
        'office_extension',
        'teach_subject_ids',
    ];
    // Relasi
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function homeroomAssignments()
    {
        return $this->hasMany(HomeroomTeacher::class);
    }
    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
