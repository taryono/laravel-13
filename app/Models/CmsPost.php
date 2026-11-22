<?php
namespace Models;

use App\Models\User;

class CmsPost extends SoftDeletableModel
{
    protected $table = 'cms_posts';
    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'view_count' => 'integer',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(CmsCategory::class, 'cms_category_id');
    }

    public function cms_category()
    {
        return $this->category();
    }

    public function cmsCategory()
    {
        return $this->category();
    }

    public function getCategoryIdAttribute()
    {
        return $this->attributes['cms_category_id'] ?? $this->attributes['category_id'] ?? null;
    }

    public function setCategoryIdAttribute($value)
    {
        $this->attributes['cms_category_id'] = $value;
    }

    public function tags()
    {
        return $this->belongsToMany(CmsTag::class, 'cms_post_tag', 'post_id', 'tag_id');
    }

    public function revisions()
    {
        return $this->hasMany(CmsPostRevision::class, 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(CmsComment::class, 'post_id');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}