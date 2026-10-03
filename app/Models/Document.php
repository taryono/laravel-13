<?php

namespace Models;

class Document extends BasedModel
{
    protected $fillable = [
        'organization_id',
        'document_type_id',
        'owner_type',
        'owner_id',
        'file_name',
        'file_path',
        'file_disk',
        'file_extension',
        'file_size_kb',
        'mime_type',
        'file_hash',
        'status',
        'verification_note',
        'verified_by',
        'verified_at',
        'rejected_at',
        'issued_at',
        'valid_until',
        'signed_by',
        'signed_at',
        'signature_data',
        'notes',
        'custom_data',
        'is_archived',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'signature_data' => 'json',
        'custom_data' => 'json',
        'verified_at' => 'datetime',
        'rejected_at' => 'datetime',
        'issued_at' => 'datetime',
        'valid_until' => 'datetime',
        'signed_at' => 'datetime',
        'is_archived' => 'boolean'
    ];

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function owner()
    {
        return $this->morphTo();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
