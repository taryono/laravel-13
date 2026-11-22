<?php
namespace Models;

class CmsPage extends SoftDeletableModel
{
    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent()
    {
        return $this->belongsTo(parent::class, 'parent_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}