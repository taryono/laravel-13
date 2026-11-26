<?php
namespace Models;

use App\Enums\PostStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class CmsPost extends SoftDeletableModel
{
    protected $fillable = [
        'user_id',
        'organization_id',
        'cms_category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at',
        'is_featured',
        'allow_comments',
        'view_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $hidden = [
        'is_not_deleted',
    ];

    protected function casts(): array
    {
        return [
            'status' => PostStatus::class,
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'view_count' => 'integer',
            'is_not_deleted' => 'boolean',
        ];
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title) ?: 'post';
        $slug = $baseSlug;
        $counter = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    // =========================================================================
    // RELATIONSHIPS & ACCESSORS
    // =========================================================================

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

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


    // Scope
    public function scopeActive($query)
    {
        return $query->where('status', 'published');
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image
            ? Storage::disk('public')->url($this->featured_image)
            : null;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PostStatus::PUBLISHED)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    protected static function booted()
    {
        static::saving(function (self $post): void {
            if ($post->isDirty('content') && !empty($post->content)) {
                $post->content = Purifier::clean($post->content, 'cms_content');
            }
        });
    }

    // Tambahkan di App\Models\CmsPost.php
    public function comments(): HasMany
    {
        return $this->hasMany(CmsComment::class, 'post_id');
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(CmsComment::class, 'post_id')
            ->approved()
            ->rootLevel()
            ->with(['user:id,name', 'replies.user:id,name'])
            ->latest();
    }
}