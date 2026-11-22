<?php

namespace Models;

class StudentExtracurricular extends SoftDeletableModel
{
    // Relasi
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class);
    }

    public function studentAttendance()
    {
        return $this->hasMany(StudentAttendance::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
