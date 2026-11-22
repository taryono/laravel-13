<?php
namespace Models;

class CmsActivityLog extends BasedModel
{
    // Relasi
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}