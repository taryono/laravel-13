<?php
namespace Models;

class CmsMedia extends SoftDeletableModel
{
     protected $fillable = [
        'user_id',
        'name',
        'file_name',
        'disk',
        'path',
        'mime_type',
        'size',
        'width',
        'height',
        'alt_text',
        'caption',
    ];

    protected $casts = [
        'size'   => 'integer',
        'width'  => 'integer',
        'height' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cms_gallery_items(): HasMany
    {
        return $this->hasMany(CmsGalleryItem::class, 'cms_media_id');
    }

    /**
     * URL publik file, mengikuti disk yang tersimpan di kolom `disk`.
     * Pemakaian: $media->url
     */
    protected function url(): Attribute
    {
        return Attribute::get(fn () => Storage::disk($this->disk ?: 'public')->url($this->path));
    }

    public function isImage(): bool
    {
        return str_starts_with((string) $this->mime_type, 'image/');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}