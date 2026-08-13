<?php

namespace Models; 

class Position extends BasedModel
{    
    protected $fillable = ['code', 'name', 'description', 'is_active'];
    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}