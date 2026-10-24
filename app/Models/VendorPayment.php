<?php
namespace Models; 

class VendorPayment extends BasedModel
{
    // Relasi
        public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function vendorInvoice()
    {
        return $this->belongsTo(VendorInvoice::class, 'vendor_invoice_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    public function fiscalPeriod()
    {
        return $this->belongsTo(FiscalPeriod::class, 'fiscal_period_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function postedJournalEntry()
    {
        return $this->belongsTo(PostedJournalEntry::class, 'posted_journal_entry_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}