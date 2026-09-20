<?php

namespace Models;

class ParentModel extends BasedModel
{
    protected $table = 'parents'; // Tetap pakai nama tabel lama
    protected $guarded = ['id'];

    protected $casts = [
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
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
