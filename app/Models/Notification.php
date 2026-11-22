<?php
namespace Models;

class Notification extends SoftDeletableModel
{
    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reference()
    {
        return $this->belongsTo(Reference::class, 'reference_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}