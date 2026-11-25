<?php
namespace Models; 

class EventType extends BasedModel
{
    // Relasi
    


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}