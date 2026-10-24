<?php
namespace Models; 

class BosRealization extends BasedModel
{
    // Relasi
        public function bosPeriod()
    {
        return $this->belongsTo(BosPeriod::class, 'bos_period_id');
    }

    public function bosUsagePlan()
    {
        return $this->belongsTo(BosUsagePlan::class, 'bos_usage_plan_id');
    }

    public function rkasRealization()
    {
        return $this->belongsTo(RkasRealization::class, 'rkas_realization_id');
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function vendorInvoice()
    {
        return $this->belongsTo(VendorInvoice::class, 'vendor_invoice_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}