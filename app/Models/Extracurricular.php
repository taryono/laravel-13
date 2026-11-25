<?php

namespace Models;

class Extracurricular extends SoftDeletableModel
{

    // Relasi
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function studentExtracurriculars()
    {
        return $this->hasMany(StudentExtracurricular::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function days()
    {
        return $this->hasMany(ExtracurricularDay::class)->orderByRaw("
            CASE day_of_week
                WHEN 'Senin' THEN 1
                WHEN 'Selasa' THEN 2
                WHEN 'Rabu' THEN 3
                WHEN 'Kamis' THEN 4
                WHEN 'Jumat' THEN 5
                WHEN 'Sabtu' THEN 6
                WHEN 'Minggu' THEN 7
                ELSE 99
            END
        ");
    }
}
