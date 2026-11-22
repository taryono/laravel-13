<?php

namespace Models;

class ApplicationDocument extends SoftDeletableModel
{
    protected $table = 'application_documents';
    protected $guarded = ['id'];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'is_active' => 'boolean',
        'verified' => 'boolean',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}