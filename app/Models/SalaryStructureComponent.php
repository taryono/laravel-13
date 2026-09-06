<?php

namespace Models; 

class SalaryStructureComponent extends BasedModel
{ 
    protected $fillable = [
        'salary_structure_id',
        'salary_component_id',
        'calculation_type',
        'value',
        'calculation_base',
        'calculation_order',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:4',
        'calculation_order' => 'integer',
        'is_active' => 'boolean',
    ];

    // ================================================================
    // RELATIONS
    // ================================================================

    /**
     * Relasi ke SalaryStructure (induk)
     */
    public function salaryStructure()
    {
        return $this->belongsTo(SalaryStructure::class, 'salary_structure_id');
    }

    /**
     * Relasi ke SalaryComponent (komponen)
     */
    public function component()
    {
        return $this->belongsTo(SalaryComponent::class, 'salary_component_id');
    }

    // ================================================================
    // SCOPES
    // ================================================================

    /**
     * Scope untuk data aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Scope untuk mengurutkan berdasarkan calculation_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('calculation_order');
    }

    // ================================================================
    // ACCESSORS
    // ================================================================

    /**
     * Get calculation type label
     */
    public function getCalculationTypeLabelAttribute()
    {
        $labels = [
            'fixed' => 'Tetap',
            'percentage' => 'Persentase',
            'formula' => 'Rumus',
            'inherit' => 'Warisan',
        ];
        return $labels[$this->calculation_type] ?? $this->calculation_type;
    }

    /**
     * Get formatted value
     */
    public function getFormattedValueAttribute()
    {
        if ($this->calculation_type === 'percentage') {
            return $this->value . '%';
        }
        if ($this->calculation_type === 'inherit') {
            return 'Mengikuti Default';
        }
        return 'Rp ' . number_format($this->value, 0, ',', '.');
    }

    /**
     * Get komponen dengan nilai akhir (efektif)
     * Menggabungkan data dari komponen dan struktur
     */
    public function getEffectiveComponentAttribute()
    {
        $component = $this->component;
        return [
            'id' => $this->id,
            'component_id' => $component->id,
            'code' => $component->code,
            'name' => $component->name,
            'type' => $component->type,
            'type_label' => $component->type_label,
            'calculation_type' => $this->calculation_type ?? $component->calculation_type,
            'value' => $this->value ?? $component->default_value,
            'calculation_base' => $this->calculation_base ?? $component->calculation_base,
            'is_taxable' => $component->is_taxable,
            'is_bpjs_base' => $component->is_bpjs_base,
            'affects_net_salary' => $component->affects_net_salary,
            'is_recurring' => $component->is_recurring,
            'calculation_order' => $this->calculation_order,
        ];
    }
}