<?php
namespace Models; 

class Session extends BasedModel
{
    // Relasi
        public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}