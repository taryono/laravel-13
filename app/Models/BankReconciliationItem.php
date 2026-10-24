<?php
namespace Models; 

class BankReconciliationItem extends BasedModel
{
    // Relasi
        public function bankReconciliation()
    {
        return $this->belongsTo(BankReconciliation::class, 'bank_reconciliation_id');
    }

    public function bankMutation()
    {
        return $this->belongsTo(BankMutation::class, 'bank_mutation_id');
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