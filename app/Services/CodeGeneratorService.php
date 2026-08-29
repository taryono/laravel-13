<?php
// app/Services/CodeGeneratorService.php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CodeGeneratorService
{
    /**
     * Generate payroll run code.
     */
    public function generatePayrollRunCode($periodId): string
    {
        $period = \App\Modules\Payroll\Models\PayrollPeriod::find($periodId);
        $year = $period ? $period->period_year : date('Y');
        $month = $period ? str_pad($period->period_month, 2, '0', STR_PAD_LEFT) : date('m');
        
        $last = DB::table('payroll_runs')
            ->where('code', 'LIKE', 'RUN-' . $year . '-' . $month . '-%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->code, -3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'RUN-' . $year . '-' . $month . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate payslip number.
     */
    public function generatePayslipNumber($employeeId, $periodId): string
    {
        $period = \App\Modules\Payroll\Models\PayrollPeriod::find($periodId);
        $year = $period ? $period->period_year : date('Y');
        $month = $period ? str_pad($period->period_month, 2, '0', STR_PAD_LEFT) : date('m');
        
        $employee = \App\Modules\Payroll\Models\Employee::find($employeeId);
        $employeeCode = $employee ? $employee->employee_id : 'EMP';
        
        return 'SLIP-' . $year . '-' . $month . '-' . $employeeCode;
    }

    /**
     * Generate loan number.
     */
    public function generateLoanNumber(): string
    {
        $year = date('Y');
        
        $last = DB::table('loans')
            ->where('loan_number', 'LIKE', 'LOAN-' . $year . '-%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->loan_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'LOAN-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate integration code.
     */
    public function generateIntegrationCode($payrollRunId): string
    {
        $payrollRun = \App\Modules\Payroll\Models\PayrollRun::find($payrollRunId);
        $periodId = $payrollRun ? $payrollRun->payroll_period_id : null;
        
        $period = $periodId ? \App\Modules\Payroll\Models\PayrollPeriod::find($periodId) : null;
        $year = $period ? $period->period_year : date('Y');
        $month = $period ? str_pad($period->period_month, 2, '0', STR_PAD_LEFT) : date('m');
        
        return 'INT-PAY-' . $year . '-' . $month . '-' . str_pad($payrollRunId, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate expense number.
     */
    public function generateExpenseNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        
        $last = DB::table('expenses')
            ->where('expense_number', 'LIKE', 'EXP-' . $year . '-' . $month . '-%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->expense_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'EXP-' . $year . '-' . $month . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate invoice number.
     */
    public function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $month = date('m');
        
        $last = DB::table('invoices')
            ->where('invoice_number', 'LIKE', 'INV-' . $year . '-' . $month . '-%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->invoice_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'INV-' . $year . '-' . $month . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate random unique code.
     */
    public function generateRandomCode(string $prefix, int $length = 8): string
    {
        $random = Str::random($length);
        $code = $prefix . '-' . strtoupper($random);
        
        return $code;
    }
}