<?php

namespace Models;

class StudentParent extends SoftDeletableModel
{
    protected $fillable = [
        'student_id',
        'parent_id',
        'relationship',
        'is_primary',
        'created_by',
        'updated_by',
    ];

    protected $casts = ['is_primary' => 'boolean'];
    // Relasi
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_parents')
            ->withPivot('relationship', 'is_primary')
            ->withTimestamps();
    }

    public function parent()
    {
        // Ganti ParentModel dengan nama model tabel `parents` Anda
        // (nama class "Parent" tidak bisa dipakai karena reserved word PHP)
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }

    public function scopeIsPrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
