<?php

/**
 * CONTOH PEMAKAIAN DocumentNumberGenerator
 * File ini bukan file yang dieksekusi — hanya referensi cara pakai
 * di berbagai konteks yang sesuai dengan skema
 * erp_hris_payroll_accounting_v2.sql.
 */

use App\Services\DocumentNumberGenerator;

// -----------------------------------------------------------
// 1. Membuat kontrak karyawan baru
// -----------------------------------------------------------
function createEmployeeContract(array $data, DocumentNumberGenerator $generator)
{
    $contractNumber = $generator->generate($data['organization_id'], 'contract');

    return \Models\EmployeeContract::create([
        'employee_id' => $data['employee_id'],
        'organization_id' => $data['organization_id'],
        'contract_number' => $contractNumber, // contoh: "CTR/YYP/2026/0001"
        'contract_type' => $data['contract_type'],
        'start_date' => $data['start_date'],
        'salary' => $data['salary'],
        'status' => 'draft',
    ]);
}

// -----------------------------------------------------------
// 2. Membuat pinjaman karyawan (loan)
// -----------------------------------------------------------
function createLoan(array $data, DocumentNumberGenerator $generator)
{
    $loanNumber = $generator->generate($data['organization_id'], 'loan');

    return \Models\Loan::create([
        'organization_id' => $data['organization_id'],
        'employee_id' => $data['employee_id'],
        'loan_number' => $loanNumber, // contoh: "LN/YYP/2026/0001"
        'loan_date' => now()->toDateString(),
        'principal_amount' => $data['principal_amount'],
        'installment_amount' => $data['installment_amount'],
        'total_installments' => $data['total_installments'],
        'remaining_installments' => $data['total_installments'],
        'status' => 'draft',
    ]);
}

// -----------------------------------------------------------
// 3. Membuat payroll run baru untuk sebuah periode
//    Nomor mengikuti bulan berjalan periode payroll, bukan
//    tanggal saat tombol "buat run" ditekan — supaya off-cycle
//    run yang dibuat telat tetap konsisten dengan periodenya.
// -----------------------------------------------------------
function createPayrollRun(\Models\PayrollPeriod $period, DocumentNumberGenerator $generator)
{
    $runNumber = $generator->generate(
        $period->organization_id,
        'payroll_run',
        $period->start_date // acuan periode, bukan now()
    );

    return \Models\PayrollRun::create([
        'organization_id' => $period->organization_id,
        'payroll_period_id' => $period->id,
        'run_number' => $runNumber, // contoh: "PR/YYP/202609/001"
        'run_type' => 'regular',
        'status' => 'draft',
    ]);
}

// -----------------------------------------------------------
// 4. Generate payslip untuk SEMUA employee dalam satu payroll run
//    Contoh loop massal — generator sudah meng-cache kode organisasi
//    per request jadi aman dipanggil berkali-kali dalam loop.
// -----------------------------------------------------------
function generatePayslipsForRun(\Models\PayrollRun $run, DocumentNumberGenerator $generator)
{
    $details = \Models\PayrollDetail::where('payroll_run_id', $run->id)->get();

    foreach ($details as $detail) {
        $payslipNumber = $generator->generate($run->organization_id, 'payslip');

        \Models\Payslip::create([
            'payroll_detail_id' => $detail->id,
            'employee_id' => $detail->employee_id,
            'organization_id' => $run->organization_id,
            'payroll_period_id' => $run->payroll_period_id,
            'payslip_number' => $payslipNumber, // contoh: "PS/YYP/202609/00001"
            'generated_at' => now(),
        ]);
    }
}

// -----------------------------------------------------------
// 5. Nomor pembayaran invoice (invoice_payments)
// -----------------------------------------------------------
function recordInvoicePayment(\Models\Invoice $invoice, array $data, DocumentNumberGenerator $generator)
{
    $paymentNumber = $generator->generate($invoice->organization_id, 'invoice_payment');

    return \Models\InvoicePayment::create([
        'invoice_id' => $invoice->id,
        'organization_id' => $invoice->organization_id,
        'payment_number' => $paymentNumber, // contoh: "INVPAY/YYP/2026/00001"
        'payment_date' => $data['payment_date'],
        'amount' => $data['amount'],
        'payment_method' => $data['payment_method'],
    ]);
}

$generator = app(\App\Services\DocumentNumberGenerator::class);
$contractNumber = $generator->generate($organizationId, 'contract');
// -> "CTR/YYP/2026/0001"