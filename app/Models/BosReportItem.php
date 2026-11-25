<?php
namespace Models;

class BosReportItem extends SoftDeletableModel
{
    protected function casts(): array
    {
        return [
            'allocation_amount' => 'decimal:2',
            'received_amount' => 'decimal:2',
            'realization_amount' => 'decimal:2',
            'remaining_amount' => 'decimal:2',
            'percentage' => 'decimal:2',
            'item_data' => 'array',
            'is_active' => 'boolean',
        ];
    }
    // Relasi
    public function bosReport()
    {
        return $this->belongsTo(BosReport::class, 'bos_report_id');
    }

    public function bosComponent()
    {
        return $this->belongsTo(BosComponent::class, 'bos_component_id');
    }

    public function rkasSource()
    {
        return $this->belongsTo(RkasSource::class, 'rkas_source_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}