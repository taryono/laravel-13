<?php
namespace Models; 

class UomConversion extends BasedModel
{
    // Relasi
        public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function fromUom()
    {
        return $this->belongsTo(FromUom::class, 'from_uom_id');
    }

    public function toUom()
    {
        return $this->belongsTo(ToUom::class, 'to_uom_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}