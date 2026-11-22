<?php
namespace Models;

use Illuminate\Database\Eloquent\Builder;

class MenuCategory extends SoftDeletableModel
{
    protected $fillable = [
        'organization_id',
        'parent_id',
        'code',
        'name',
        'description',
        'icon',
        'color',
        'sort_order',
        'is_system',
        'is_active',
        'show_to_trial',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
        'show_to_trial' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForOrganization(Builder $query, int $organizationId): Builder
    {
        return $query->where('organization_id', $organizationId);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function parent()
    {
        return $this->belongsTo(parent::class, 'parent_id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'menu_category_id');
    }
}