<?php

namespace Models; 

class ReconciliationDetail extends BasedModel
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