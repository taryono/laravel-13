<?php
namespace Models;

class RkasProgram extends SoftDeletableModel
{
    // Relasi
    public function rkasDocument()
    {
        return $this->belongsTo(RkasDocument::class, 'rkas_document_id');
    }

    public function standard()
    {
        return $this->belongsTo(Standard::class, 'standard_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}