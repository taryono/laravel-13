<?php

namespace Models;

class AssetDepreciation extends SoftDeletableModel
{
    protected $table = 'asset_depreciations';

    protected $fillable = [
        'fixed_asset_id',
        'accounting_period_id',
        'depreciation_date',
        'amount',
        'journal_entry_id',
    ];

    protected $casts = [
        'depreciation_date' => 'date:Y-m-d',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ─── Relationships ───
    public function fixedAsset()
    {
        return $this->belongsTo(FixedAsset::class);
    }

    public function accountingPeriod()
    {
        return $this->belongsTo(AccountingPeriod::class);
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}