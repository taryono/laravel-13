<?php

namespace Models;

class SalaryComponent extends SoftDeletableModel
{
    protected $fillable = [
        'organization_id',
        'code',
        'name',
        'type',
        'calculation_type',
        'calculation_base',
        'default_value',
        'is_taxable',
        'is_bpjs_base',
        'affects_net_salary',
        'is_recurring',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'default_value' => 'decimal:4',
        'is_taxable' => 'boolean',
        'is_bpjs_base' => 'boolean',
        'affects_net_salary' => 'boolean',
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // ================================================================
    // RELATIONS
    // ================================================================

    /**
     * Relasi ke Organization
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Relasi ke SalaryStructureComponent (pivot)
     * Komponen ini digunakan di banyak struktur
     */
    public function structureComponents()
    {
        return $this->hasMany(SalaryStructureComponent::class, 'salary_component_id');
    }

    /**
     * Relasi ke SalaryStructure melalui pivot
     * Mendapatkan semua struktur yang menggunakan komponen ini
     */
    public function structures()
    {
        return $this->belongsToMany(SalaryStructure::class, 'salary_structure_components', 'salary_component_id', 'salary_structure_id')
            ->withPivot(['calculation_type', 'value', 'calculation_base', 'calculation_order', 'is_active'])
            ->withTimestamps();
    }

    // ================================================================
    // SCOPES
    // ================================================================

    /**
     * Scope untuk komponen aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Scope berdasarkan tipe
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope untuk komponen yang mempengaruhi gaji bersih
     */
    public function scopeAffectsNetSalary($query)
    {
        return $query->where('affects_net_salary', 1);
    }

    /**
     * Scope untuk komponen yang berulang (setiap bulan)
     */
    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', 1);
    }

    // ================================================================
    // ACCESSORS & MUTATORS
    // ================================================================

    /**
     * Get label type dalam Bahasa Indonesia
     */
    public function getTypeLabelAttribute()
    {
        $labels = [
            'earning' => 'Penghasilan',
            'deduction' => 'Potongan',
            'company_contribution' => 'Kontribusi Perusahaan',
            'tax' => 'Pajak',
        ];
        return $labels[$this->type] ?? $this->type;
    }

    /**
     * Get label calculation type dalam Bahasa Indonesia
     */
    public function getCalculationTypeLabelAttribute()
    {
        $labels = [
            'fixed' => 'Tetap',
            'percentage' => 'Persentase',
            'formula' => 'Rumus',
            'attendance' => 'Berdasarkan Kehadiran',
            'loan' => 'Cicilan',
            'tax' => 'Pajak',
            'manual' => 'Manual',
        ];
        return $labels[$this->calculation_type] ?? $this->calculation_type;
    }

    /**
     * Format default value sebagai Rupiah
     */
    public function getFormattedDefaultValueAttribute()
    {
        if ($this->calculation_type === 'percentage') {
            return $this->default_value . '%';
        }
        return 'Rp ' . number_format($this->default_value, 0, ',', '.');
    }
}