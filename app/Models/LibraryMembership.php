<?php
namespace Models; 

class LibraryMembership extends BasedModel
{
    // Relasi
        public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}