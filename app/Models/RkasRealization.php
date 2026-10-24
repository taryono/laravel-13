<?php
namespace Models; 

class RkasRealization extends BasedModel
{
    // Relasi
        public function rkasItem()
    {
        return $this->belongsTo(RkasItem::class, 'rkas_item_id');
    }

    public function rkasSource()
    {
        return $this->belongsTo(RkasSource::class, 'rkas_source_id');
    }

    public function fiscalPeriod()
    {
        return $this->belongsTo(FiscalPeriod::class, 'fiscal_period_id');
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