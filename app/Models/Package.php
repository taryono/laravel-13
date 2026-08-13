<?php

namespace Models; 

class Package extends BasedModel
{ 
    protected $fillable = [
        'code',
        'name',
        'price',
        'description',
        'features'
    ];

    protected $casts = [
        'price' => 'integer',
        'features' => 'array'
    ];

    // Accessor untuk menampilkan harga terformat
    public function getFormattedPriceAttribute()
    {
        if ($this->price == 0) {
            return 'Harga Khusus';
        }
        return 'Rp ' . number_format($this->price, 0, ',', '.') . ' / bln';
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}