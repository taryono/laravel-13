<?php
namespace Models;

class StockAdjustment extends SoftDeletableModel
{
    // Relasi
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
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