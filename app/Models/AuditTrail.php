<?php
namespace Models; 

class AuditTrail extends BasedModel
{
    // Relasi
        public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

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