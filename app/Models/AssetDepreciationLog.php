<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetDepreciationLog extends BasedModel
{
    protected $fillable = [
        'asset_id',
        'period_year',
        'period_month',
        'period_date',
        'opening_book_value',
        'depreciation_amount',
        'accumulated_depreciation',
        'closing_book_value',
        'depreciation_method',
        'purchase_price',
        'salvage_value',
        'useful_life_months',
        'is_posted',
        'posted_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'period_date'              => 'date',
        'posted_at'                => 'datetime',
        'is_posted'                => 'boolean',
        'opening_book_value'       => 'decimal:2',
        'depreciation_amount'      => 'decimal:2',
        'accumulated_depreciation' => 'decimal:2',
        'closing_book_value'       => 'decimal:2',
        'purchase_price'           => 'decimal:2',
        'salvage_value'            => 'decimal:2',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}
