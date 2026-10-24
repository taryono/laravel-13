<?php
namespace Models; 

class RkasApproval extends BasedModel
{
    // Relasi
        public function rkasDocument()
    {
        return $this->belongsTo(RkasDocument::class, 'rkas_document_id');
    }

    public function approver()
    {
        return $this->belongsTo(Approver::class, 'approver_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}