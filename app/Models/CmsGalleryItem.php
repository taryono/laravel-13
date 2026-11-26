<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class CmsGalleryItem extends Model
{
    protected $table = 'cms_gallery_items';

    protected $fillable = [
        'gallery_id',
        'media_id',
        'image',
        'caption',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    // Relasi
    public function gallery(): BelongsTo
    {
        return $this->belongsTo(CmsGallery::class, 'gallery_id');
    }

    public function cms_gallery(): BelongsTo
    {
        return $this->belongsTo(CmsGallery::class, 'gallery_id');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(CmsMedia::class, 'media_id');
    }

    public function cms_media(): BelongsTo
    {
        return $this->belongsTo(CmsMedia::class, 'media_id');
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