<?php
namespace Models; 

class ActivityLog extends BasedModel
{
    // Relasi
        public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function record()
    {
        return $this->belongsTo(Record::class, 'record_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}