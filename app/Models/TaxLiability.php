<?php
namespace Models; 

class TaxLiability extends BasedModel
{
    // Relasi
        public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id');
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}