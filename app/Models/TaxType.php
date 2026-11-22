<?php

namespace Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxType extends SoftDeletableModel
{
    protected $fillable = [
        'organization_id',
        'code',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    // Relasi ke pengaturan pajak
    public function tax_settings(): HasMany
    {
        return $this->hasMany(TaxSetting::class);
    }

    // Bantu untuk mendapatkan kode dengan mudah
    public static function getByCode(string $code): ?self
    {
        return static::where('code', $code)->first();
    }
}