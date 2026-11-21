<?php
namespace Models;

class CmsRedirect extends BasedModel
{
    protected $fillable = [
        'from_url',
        'to_url',
        'status_code',
        'hits',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Kode status yang tersedia
    public static $statusCodes = [
        301 => '301 - Pindah Permanen',
        302 => '302 - Pindah Sementara',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->hits = 0;
        });
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}