<?php
namespace Models; 

class FixedAsset extends BasedModel
{
    // Relasi
        public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function assetCategory()
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }

    public function assetAccount()
    {
        return $this->belongsTo(AssetAccount::class, 'asset_account_id');
    }

    public function accumulatedDepreciationAccount()
    {
        return $this->belongsTo(AccumulatedDepreciationAccount::class, 'accumulated_depreciation_account_id');
    }

    public function depreciationExpenseAccount()
    {
        return $this->belongsTo(DepreciationExpenseAccount::class, 'depreciation_expense_account_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}