<?php
namespace Models;

class CbtScheduleProctor extends SoftDeletableModel
{
    // Relasi
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function proctor()
    {
        return $this->belongsTo(Proctor::class, 'proctor_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}