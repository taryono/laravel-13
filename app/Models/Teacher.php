<?php

namespace Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends SoftDeletableModel
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

    protected $casts = [
        'join_date' => 'date',
        'is_active' => 'boolean',
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

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
