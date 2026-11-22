<?php
namespace Models;

class DapodikMapping extends SoftDeletableModel
{
    // Relasi
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function local()
    {
        return $this->belongsTo(Local::class, 'local_id');
    }

    public function dapodik()
    {
        return $this->belongsTo(Dapodik::class, 'dapodik_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}