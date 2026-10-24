<?php
namespace Models; 

class AcademicRegistrationFee extends BasedModel
{
    // Relasi
    


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}