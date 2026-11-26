<?php
namespace Models;

class CmsGalleryItem extends SoftDeletableModel
{
    protected $fillable = [
        'cms_gallery_id',
        'cms_media_id',
        'image',
        'caption',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    // Relasi
    public function cms_gallery(): BelongsTo
    {
        return $this->belongsTo(CmsGallery::class, 'cms_gallery_id');
    }

    public function cms_media(): BelongsTo
    {
        return $this->belongsTo(CmsMedia::class, 'cms_media_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * URL gambar: prioritas dari media library, jika kosong pakai kolom `image`.
     * Pemakaian: $item->image_url
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(function () {
            if ($this->media) {
                return $this->media->url;
            }

            return $this->image ? Storage::disk('public')->url($this->image) : null;
        });
    }
}