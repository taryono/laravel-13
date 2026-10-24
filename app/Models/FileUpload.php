<?php
namespace Models; 

class FileUpload extends BasedModel
{
    // Relasi
        public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function uploadable()
    {
        return $this->belongsTo(Uploadable::class, 'uploadable_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}