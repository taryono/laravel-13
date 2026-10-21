<?php
namespace Models; 

class CmsCategory extends BasedModel
{
    // Relasi
        public function parent()
    {
        return $this->belongsTo(Parent::class, 'parent_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}