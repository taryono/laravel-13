<?php

namespace Models;

class Student extends SoftDeletableModel
{
    protected $fillable = [
        'organization_id',
        'user_id',
        'student_code',
        'nisn',
        'nis',
        'class',
        'level',
        'date_of_entry',
        'batch',
        'graduation_year',
        'organization_name',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'religion',
        'address',
        'postal_code',
        'phone',
        'email',
        'photo_url',
        'status',
        'is_active'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'join_date' => 'date',
        'enrollment_year' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }


    // Relasi
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function parents()
    {
        return $this->belongsToMany(parent::class, 'student_parents')
            ->withPivot('relationship', 'is_primary', 'deleted_at')
            ->withTimestamps();
    }

    public function primaryParent()
    {
        return $this->parents()->wherePivot('is_primary', 1)->first();
    }

    // Scope
    public function scopeIsPrimary($query)
    {
        return $query->where('is_primary', true);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

if (!class_exists('App\Models\Student', false)) {
    class_alias(Student::class, 'App\Models\Student');
}

