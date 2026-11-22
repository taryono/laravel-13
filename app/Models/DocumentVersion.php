<?php
namespace Models;

class DocumentVersion extends SoftDeletableModel
{
    // Relasi
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}