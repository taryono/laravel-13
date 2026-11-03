<?php
namespace Models; 

class MenuCategory extends BasedModel
{
    // Relasi
        public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function parent()
    {
        return $this->belongsTo(Parent::class, 'parent_id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'menu_category_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}