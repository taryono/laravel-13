<?php
namespace Models;

class CmsGalleryItem extends SoftDeletableModel
{
    // Relasi
    public function gallery()
    {
        return $this->belongsTo(Gallery::class, 'gallery_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}