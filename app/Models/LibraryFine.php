<?php
namespace Models;

class LibraryFine extends SoftDeletableModel
{
    // Relasi
    public function libraryLoan()
    {
        return $this->belongsTo(LibraryLoan::class, 'library_loan_id');
    }

    public function libraryMembership()
    {
        return $this->belongsTo(LibraryMembership::class, 'library_membership_id');
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