<?php
namespace Models; 

class FacilityImage extends BasedModel
{
    protected $table = 'facility_images';

    protected $fillable = [
        'organization_id',
        'facility_id',
        'image',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relasi
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}