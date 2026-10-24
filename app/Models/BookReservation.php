<?php
namespace Models; 

class BookReservation extends BasedModel
{
    // Relasi
        public function libraryBook()
    {
        return $this->belongsTo(LibraryBook::class, 'library_book_id');
    }

    public function libraryMembership()
    {
        return $this->belongsTo(LibraryMembership::class, 'library_membership_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}