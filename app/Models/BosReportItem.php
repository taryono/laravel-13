<?php
namespace Models;

class BosReportItem extends SoftDeletableModel
{
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