<?php
namespace Models; 

class CmsCategory extends BasedModel
{
    protected $table = 'cms_categories';
    protected $guarded = ['id'];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relasi
    public function parent()
    {
        return $this->belongsTo(CmsCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(CmsCategory::class, 'parent_id');
    }

    public function posts()
    {
        return $this->hasMany(CmsPost::class, 'cms_category_id');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}