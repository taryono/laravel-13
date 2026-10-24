<?php
namespace Models; 

class RkasRevision extends BasedModel
{
    // Relasi
        public function rkasDocument()
    {
        return $this->belongsTo(RkasDocument::class, 'rkas_document_id');
    }

    public function parentRevision()
    {
        return $this->belongsTo(ParentRevision::class, 'parent_revision_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}