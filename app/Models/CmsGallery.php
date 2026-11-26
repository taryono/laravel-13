<?php
namespace Models;

class CmsGallery extends SoftDeletableModel
{

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Route model binding memakai slug: route('galeri.show', $gallery)
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function cms_gallery_items(): HasMany
    {
        return $this->hasMany(CmsGalleryItem::class, 'cms_gallery_id')
            ->orderBy('sort_order');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /**
     * URL cover galeri (null jika belum ada cover).
     * Pemakaian: $gallery->cover_url
     */
    protected function coverUrl(): Attribute
    {
        return Attribute::get(
            fn () => $this->cover_image ? Storage::disk('public')->url($this->cover_image) : null
        );
    }  
     
}