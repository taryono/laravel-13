<?php

namespace App\Modules\Integration\Services;

use Models\PayrollRun;
use Models\JournalEntry;
use Models\ChartOfAccount;
use App\Services\JournalService;
use Models\PayrollIntegration;
use Models\AccountMapping;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PayrollFinanceIntegrationService
{
    protected JournalService $journalService;

    public function __construct(JournalService $journalService)
    {
        $this->journalService = $journalService;
    }

    public function syncToFinance(PayrollRun $payrollRun): void
    {
        // Check if already synced
        $integration = PayrollIntegration::where('payroll_run_id', $payrollRun->id)->first();

        if ($integration && $integration->status === 'synced') {
            throw new \Exception('Payroll already synced to finance');
        }

        // Get account mappings
        $mappings = AccountMapping::where('source_type', 'payroll_component')
            ->where('is_active', 1)
            ->get()
            ->keyBy('source_code');

        // Prepare journal items
        $journalItems = $this->prepareJournalItems($payrollRun, $mappings);

        // Create journal entry
        $journal = $this->journalService->createJournal([
            'journal_date' => now()->format('Y-m-d'),
            'description' => 'Payroll Periode ' . $payrollRun->payrollPeriod->period_month . '/' . $payrollRun->payrollPeriod->period_year,
            'reference_number' => 'PAY-' . $payrollRun->code,
            'items' => $journalItems,
            'notes' => 'Generated from payroll run #' . $payrollRun->id,
        ], array_sum(array_column($journalItems, 'debit')), array_sum(array_column($journalItems, 'credit')));

        // Post journal immediately
        $this->journalService->postJournal($journal);

        // Create integration record
        PayrollIntegration::updateOrCreate(
            ['payroll_run_id' => $payrollRun->id],
            [
                'payroll_period_id' => $payrollRun->payroll_period_id,
                'journal_entry_id' => $journal->id,
                'integration_code' => 'INT-PAY-' . $payrollRun->id . '-' . date('Ymd'),
                'total_gross_salary' => $payrollRun->total_gross_salary,
                'total_net_salary' => $payrollRun->total_net_salary,
                'total_tax' => $this->calculateTotalTax($payrollRun),
                'total_bpjs_company' => $this->calculateBpjsCompany($payrollRun),
                'total_bpjs_employee' => $this->calculateBpjsEmployee($payrollRun),
                'total_loans' => $this->calculateTotalLoans($payrollRun),
                'total_other_deductions' => $payrollRun->total_deductions,
                'status' => 'synced',
                'synced_at' => now(),
                'mapping_config' => json_encode($mappings->toArray()),
            ]
        );
    }

    public function reverseSync(PayrollIntegration $integration): void
    {
        // Void the journal entry
        if ($integration->journal_entry_id) {
            $journal = JournalEntry::find($integration->journal_entry_id);
            if ($journal && $journal->status === 'posted') {
                $this->journalService->voidJournal($journal);
            }
        }

        // Update integration status
        $integration->update([
            'status' => 'failed',
        ]);
    }

    private function prepareJournalItems($payrollRun, $mappings): array
    {
        $items = [];

        // 1. Debit: Beban Gaji (Gross Salary)
        if (isset($mappings['salary_gross'])) {
            $items[] = [
                'account_id' => $mappings['salary_gross']->debit_account_id,
                'debit' => $payrollRun->total_gross_salary,
                'credit' => 0,
                'description' => 'Beban Gaji Kotor',
            ];
        }

        // 2. Credit: Kas/Bank (Net Salary)
        if (isset($mappings['salary_net'])) {
            $items[] = [
                'account_id' => $mappings['salary_net']->credit_account_id,
                'debit' => 0,
                'credit' => $payrollRun->total_net_salary,
                'description' => 'Pembayaran Gaji Bersih',
            ];
        }

        // 3. Credit: Hutang PPH 21
        $totalTax = $this->calculateTotalTax($payrollRun);
        if ($totalTax > 0 && isset($mappings['tax_pph21'])) {
            $items[] = [
                'account_id' => $mappings['tax_pph21']->credit_account_id,
                'debit' => 0,
                'credit' => $totalTax,
                'description' => 'Hutang PPH 21',
            ];
        }

        // 4. Credit: Hutang BPJS
        $totalBpjs = $this->calculateTotalBpjs($payrollRun);
        if ($totalBpjs > 0 && isset($mappings['bpjs_health'])) {
            $items[] = [
                'account_id' => $mappings['bpjs_health']->credit_account_id,
                'debit' => 0,
                'credit' => $totalBpjs,
                'description' => 'Hutang BPJS',
            ];
        }

        // 5. Debit/Credit: Pinjaman Karyawan
        $totalLoans = $this->calculateTotalLoans($payrollRun);
        if ($totalLoans > 0 && isset($mappings['loan_deduction'])) {
            $items[] = [
                'account_id' => $mappings['loan_deduction']->debit_account_id,
                'debit' => $totalLoans,
                'credit' => 0,
                'description' => 'Potongan Pinjaman Karyawan',
            ];
            $items[] = [
                'account_id' => $mappings['loan_deduction']->credit_account_id,
                'debit' => 0,
                'credit' => $totalLoans,
                'description' => 'Pembayaran Pinjaman Karyawan',
            ];
        }

        return $items;
    }

    private function calculateTotalTax($payrollRun): float
    {
        return $payrollRun->payrollDetails->sum('tax_amount') ?? 0;
    }

    private function calculateBpjsCompany($payrollRun): float
    {
        // Implementasi perhitungan BPJS dari perusahaan
        return $payrollRun->total_gross_salary * 0.04; // Example: 4%
    }

    private function calculateBpjsEmployee($payrollRun): float
    {
        // Implementasi perhitungan BPJS dari karyawan
        return $payrollRun->total_gross_salary * 0.02; // Example: 2%
    }

    private function calculateTotalBpjs($payrollRun): float
    {
        return $this->calculateBpjsCompany($payrollRun) + $this->calculateBpjsEmployee($payrollRun);
    }

    private function calculateTotalLoans($payrollRun): float
    {
        return $payrollRun->payrollDetails->sum('total_loan_payment') ?? 0;
    }
}
