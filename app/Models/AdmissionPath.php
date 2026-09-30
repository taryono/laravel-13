<?php

namespace Models;

class AdmissionPath extends BasedModel
{
    protected $table = 'admission_paths';

    public function admission()
    {
        return $this->belongsTo(Admission::class, 'admission_id');
    }

    public function quota_settings()
    {
        return $this->hasMany(AdmissionQuotaSetting::class, 'admission_path_id');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
