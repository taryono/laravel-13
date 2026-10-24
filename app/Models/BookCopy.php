<?php
namespace Models; 

class BookCopy extends BasedModel
{
    // Relasi
        public function libraryBook()
    {
        return $this->belongsTo(LibraryBook::class, 'library_book_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}