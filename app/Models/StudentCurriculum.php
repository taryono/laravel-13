<?php

namespace Models;

class StudentCurriculum extends SoftDeletableModel
{
    protected $table = 'student_curriculums';

    protected $fillable = [
        'student_id',
        'curriculum_id',
    ];

    // Relasi
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
