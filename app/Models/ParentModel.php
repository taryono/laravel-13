<?php

namespace Models;

class ParentModel extends SoftDeletableModel
{
    protected $table = 'parents'; // Tetap pakai nama tabel lama
    protected $guarded = ['id'];

    protected $casts = [
        'is_biological'      => 'boolean',
        'is_primary_contact' => 'boolean',
        'lives_with_student' => 'boolean',
        'is_alive'           => 'boolean',
        'is_active'          => 'boolean',
        'birth_date'         => 'date',
        'death_date'         => 'date',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime',
        'deleted_at'         => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_parents')
            ->withPivot('relationship', 'is_primary', 'deleted_at')
            ->withTimestamps();
    }
}
