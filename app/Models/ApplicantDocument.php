<?php

namespace Models;

class ApplicantDocument extends BasedModel
{
    protected $fillable = [
        'id',
        'applicant_id',
        'document_type',
        'document_url',
        'file_name',
        'file_size',
        'uploaded_at',
        'verified',
        'verified_by',
        'verification_notes',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];
    // Relasi
    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }
}
