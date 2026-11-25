<?php
namespace Models;

class BosReport extends SoftDeletableModel
{
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'total_allocation' => 'decimal:2',
            'total_received' => 'decimal:2',
            'total_used' => 'decimal:2',
            'total_remaining' => 'decimal:2',
            'percentage' => 'decimal:2',
            'report_data' => 'array',
            'submitted_at' => 'datetime',
            'verified_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
    // Relasi
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function bosPeriod()
    {
        return $this->belongsTo(BosPeriod::class, 'bos_period_id');
    }

    public function fiscalPeriod()
    {
        return $this->belongsTo(FiscalPeriod::class, 'fiscal_period_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}