<?php
namespace Models; 

class DigitalLibraryItem extends BasedModel
{
    // Relasi
        public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function libraryBook()
    {
        return $this->belongsTo(LibraryBook::class, 'library_book_id');
    }

    public function bookCategory()
    {
        return $this->belongsTo(BookCategory::class, 'book_category_id');
    }

    public function bookPublisher()
    {
        return $this->belongsTo(BookPublisher::class, 'book_publisher_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}