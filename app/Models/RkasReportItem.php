<?php
namespace Models;

class RkasReportItem extends SoftDeletableModel
{
    // Relasi
    public function rkasReport()
    {
        return $this->belongsTo(RkasReport::class, 'rkas_report_id');
    }

    public function rkasComponent()
    {
        return $this->belongsTo(RkasComponent::class, 'rkas_component_id');
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