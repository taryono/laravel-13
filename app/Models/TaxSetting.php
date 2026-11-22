<?php

namespace Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxSetting extends SoftDeletableModel
{

    protected $table = 'tax_settings';

    protected $fillable = [
        'organization_id',
        'tax_type_id',
        'tax_code',
        'tax_name',
        'tax_rate',
        'min_amount',
        'max_amount',
        'effective_date',
        'expiry_date',
        'is_active',
        'description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tax_rate' => 'decimal:4',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relasi ke organisasi
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    // Relasi ke jenis pajak
    public function tax_type(): BelongsTo
    {
        return $this->belongsTo(TaxType::class);
    }

    // Pencarian aktif berdasarkan tanggal
    public function scopeActiveOnDate($query, $date = null)
    {
        $date = $date ?? now();

        return $query
            ->where('is_active', true)
            ->where('effective_date', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', $date);
            });
    }
}