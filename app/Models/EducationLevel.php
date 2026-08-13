<?php

namespace Models; 

class EducationLevel extends BasedModel
{  
    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}