<?php

namespace Models; 

class SalaryStructure extends BasedModel
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
     * Relasi ke SalaryStructureComponent (detail struktur)
     * Satu struktur memiliki banyak komponen
     */
    public function structureComponents()
    {
        return $this->hasMany(SalaryStructureComponent::class, 'salary_structure_id');
    }

    /**
     * Relasi ke SalaryComponent melalui pivot
     * Mendapatkan semua komponen yang digunakan dalam struktur ini
     */
    public function components()
    {
        return $this->belongsToMany(SalaryComponent::class, 'salary_structure_components', 'salary_structure_id', 'salary_component_id')
            ->withPivot(['calculation_type', 'value', 'calculation_base', 'calculation_order', 'is_active'])
            ->withTimestamps();
    }

    /**
     * Relasi ke Employee (karyawan yang menggunakan struktur ini)
     * Satu struktur bisa digunakan oleh banyak karyawan
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'salary_structure_id');
    }

    // ================================================================
    // SCOPES
    // ================================================================

    /**
     * Scope untuk struktur aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Scope untuk mencari berdasarkan kode
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    // ================================================================
    // METHODS
    // ================================================================

    /**
     * Mendapatkan semua komponen earning (penghasilan) dalam struktur
     * Diurutkan berdasarkan sort_order
     */
    public function getEarningComponents()
    {
        return $this->structureComponents()
            ->whereHas('component', function ($query) {
                $query->where('type', 'earning')->where('is_active', 1);
            })
            ->orderBy('calculation_order')
            ->get();
    }

    /**
     * Mendapatkan semua komponen deduction (potongan) dalam struktur
     */
    public function getDeductionComponents()
    {
        return $this->structureComponents()
            ->whereHas('component', function ($query) {
                $query->whereIn('type', ['deduction', 'tax'])->where('is_active', 1);
            })
            ->orderBy('calculation_order')
            ->get();
    }

    /**
     * Mendapatkan semua komponen company contribution dalam struktur
     */
    public function getCompanyContributionComponents()
    {
        return $this->structureComponents()
            ->whereHas('component', function ($query) {
                $query->where('type', 'company_contribution')->where('is_active', 1);
            })
            ->orderBy('calculation_order')
            ->get();
    }

    /**
     * Hitung total earning dari struktur
     * @param float $baseSalary - Gaji pokok untuk perhitungan percentage
     */
    public function calculateTotalEarning($baseSalary = 0)
    {
        $total = 0;
        $components = $this->getEarningComponents();

        foreach ($components as $structComp) {
            $total += $this->calculateComponentValue($structComp, $baseSalary);
        }

        return $total;
    }

    /**
     * Hitung total deduction dari struktur
     */
    public function calculateTotalDeduction($baseSalary = 0, $totalEarning = 0)
    {
        $total = 0;
        $components = $this->getDeductionComponents();

        foreach ($components as $structComp) {
            $total += $this->calculateComponentValue($structComp, $baseSalary, $totalEarning);
        }

        return $total;
    }

    /**
     * Hitung nilai komponen berdasarkan tipe perhitungan
     */
    private function calculateComponentValue($structComp, $baseSalary = 0, $totalEarning = 0)
    {
        $component = $structComp->component;
        $calcType = $structComp->calculation_type ?? $component->calculation_type;
        $value = $structComp->value ?? $component->default_value;

        switch ($calcType) {
            case 'fixed':
                return (float) $value;

            case 'percentage':
                $base = $structComp->calculation_base ?? $component->calculation_base;
                if ($base === 'SALARY_BASE') {
                    return ($value / 100) * $baseSalary;
                }
                if ($base === 'TOTAL_EARNING') {
                    return ($value / 100) * $totalEarning;
                }
                return ($value / 100) * $baseSalary;

            case 'inherit':
                // Gunakan nilai default dari komponen
                return (float) $component->default_value;

            default:
                return (float) $value;
        }
    }

    /**
     * Hitung total gaji bersih
     */
    public function calculateNetSalary($baseSalary = 0)
    {
        $totalEarning = $this->calculateTotalEarning($baseSalary);
        $totalDeduction = $this->calculateTotalDeduction($baseSalary, $totalEarning);
        $netSalary = $totalEarning - $totalDeduction;

        return [
            'base_salary' => $baseSalary,
            'total_earning' => $totalEarning,
            'total_deduction' => $totalDeduction,
            'net_salary' => $netSalary,
            'components' => [
                'earnings' => $this->getEarningComponents()->map(function ($item) use ($baseSalary) {
                    return [
                        'name' => $item->component->name,
                        'value' => $this->calculateComponentValue($item, $baseSalary),
                    ];
                }),
                'deductions' => $this->getDeductionComponents()->map(function ($item) use ($baseSalary, $totalEarning) {
                    return [
                        'name' => $item->component->name,
                        'value' => $this->calculateComponentValue($item, $baseSalary, $totalEarning),
                    ];
                }),
            ],
        ];
    }

    // ================================================================
    // ACCESSORS
    // ================================================================

    /**
     * Mendapatkan total komponen dalam struktur
     */
    public function getComponentsCountAttribute()
    {
        return $this->structureComponents()->where('is_active', 1)->count();
    }

    /**
     * Mendapatkan daftar nama komponen dalam struktur (string)
     */
    public function getComponentNamesAttribute()
    {
        return $this->structureComponents()
            ->where('is_active', 1)
            ->with('component')
            ->get()
            ->pluck('component.name')
            ->join(', ');
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}