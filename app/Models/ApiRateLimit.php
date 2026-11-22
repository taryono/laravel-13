<?php

namespace Models;

class ApiRateLimit extends SoftDeletableModel
{
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