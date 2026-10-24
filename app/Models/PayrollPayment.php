<?php
namespace Models; 

class PayrollPayment extends BasedModel
{
    // Relasi
        public function payrollPaymentBatch()
    {
        return $this->belongsTo(PayrollPaymentBatch::class, 'payroll_payment_batch_id');
    }

    public function payrollDetail()
    {
        return $this->belongsTo(PayrollDetail::class, 'payroll_detail_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }


    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}