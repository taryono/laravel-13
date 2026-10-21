<?php
namespace Models; 

class CmsTag extends BasedModel
{
    // Relasi
    


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}