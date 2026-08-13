<?php

namespace Models; 

class EduationLevel extends BaseModel
{  
    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}