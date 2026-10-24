<?php
namespace Models; 

class InventoryItem extends BasedModel
{
    // Relasi
        public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }

    public function inventoryAccount()
    {
        return $this->belongsTo(InventoryAccount::class, 'inventory_account_id');
    }

    public function cogsAccount()
    {
        return $this->belongsTo(CogsAccount::class, 'cogs_account_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}