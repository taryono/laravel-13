<?php

namespace Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Models\BasedModel;

class CmsComment extends BasedModel
{
    use SoftDeletes;

    protected $table = 'cms_comments';

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'name',
        'email',
        'body',
        'status',
        'ip_address',
        'user_agent',
    ];

    protected $hidden = [
        'email',
        'ip_address',
        'user_agent',
    ];

    // =========================================================================
    // ACCESSOR ALIAS (Kompatibilitas dengan View Blade)
    // =========================================================================

    public function getAuthorNameAttribute(): ?string
    {
        return $this->name;
    }

    public function getContentAttribute(): ?string
    {
        return $this->body;
    }

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    public function post(): BelongsTo
    {
        return $this->belongsTo(CmsPost::class, 'post_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->where('status', 'approved')
            ->orderBy('created_at');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // =========================================================================
    // QUERY SCOPES
    // =========================================================================

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopeRootLevel(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }
}