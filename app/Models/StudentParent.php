<?php

namespace Models;

class StudentParent extends SoftDeletableModel
{
    protected $fillable = [];
    // Relasi
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_parents')
            ->withPivot('relationship', 'is_primary')
            ->withTimestamps();
    }

    public function parent()
    {
        return $this->belongsTo(StudentParent::class, 'parent_id')->with('parent', 'student');
    }

    // Scope
    public function scopeIsPrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
