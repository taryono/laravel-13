<?php

namespace Models; 

class Teacher extends BasedModel
{ 
    // Relasi
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    public function homeroomAssignments()
    {
        return $this->hasMany(HomeroomTeacher::class);
    }
    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}