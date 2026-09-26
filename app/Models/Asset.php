<?php

namespace Models;

class Asset extends BasedModel
{
    // Tambahkan baris ini agar 'book_value' & 'monthly_depreciation' otomatis masuk di response JSON/Array
    protected $appends = [
        'book_value',
        'monthly_depreciation',
    ];

    protected $fillable = [
        // Kepemilikan
        'organization_id',
        'school_id',
        'is_active',

        // Identitas
        'asset_category_id',
        'supplier_id',
        'location_id',
        'code',
        'name',
        'brand',
        'model',
        'specification',
        'serial_number',

        // Keuangan & Penyusutan
        'purchase_date',
        'purchase_price',
        'salvage_value',
        'useful_life_months',
        'depreciation_method',
        'accumulated_depreciation',
        'book_value',
        'asset_chart_of_account_id',
        'accumulated_depreciation_chart_of_account_id',
        'depreciation_expense_chart_of_account_id',

        // Status & Penggunaan
        'status',
        'condition',
        'assigned_to',
        'warranty_end',

        // Penghapusan
        'disposed_at',
        'disposal_value',
        'disposal_reason',

        // Lampiran & Catatan
        'photo_url',
        'notes',

        // Audit
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'purchase_date' => 'date',
        'warranty_end' => 'date',
        'disposed_at' => 'datetime',
        'purchase_price' => 'decimal:2',
        'salvage_value' => 'decimal:2',
        'accumulated_depreciation' => 'decimal:2',
        'book_value' => 'decimal:2',
        'disposal_value' => 'decimal:2',
    ];


    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function category()
    {
        return $this->belongsTo(AssetCategory::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    // Ganti nama agar lebih jelas & konsisten
    public function assetChartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'asset_chart_of_account_id');
    }

    public function accumulatedDepreciationChartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'accumulated_depreciation_chart_of_account_id');
    }

    public function depreciationExpenseChartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'depreciation_expense_chart_of_account_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }

    public function getMonthlyDepreciationAttribute(): float
    {
        if (!$this->useful_life_months || $this->useful_life_months <= 0) {
            return 0;
        }

        $depreciableAmount = (float) $this->purchase_price - (float) $this->salvage_value;

        // Untuk perhitungan akurat semua metode, pakai AssetDepreciationService.
        return match ($this->depreciation_method) {
            'straight_line' => $depreciableAmount / $this->useful_life_months,
            default         => $depreciableAmount / $this->useful_life_months, // fallback kasar
        };
    }


    public function getBookValueAttribute()
    {
        return $this->purchase_price - $this->accumulated_depreciation;
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNotDisposed($query)
    {
        return $query->whereNull('disposed_at');
    }

    public function isDisposed()
    {
        return !is_null($this->disposed_at);
    }

    public function depreciationLogs()
    {
        return $this->hasMany(AssetDepreciationLog::class)->orderBy('period_year')->orderBy('period_month');
    }
}
