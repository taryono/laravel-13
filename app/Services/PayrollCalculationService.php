<?php

namespace App\Services;

use Models\PayrollRun;
use Models\PayrollDetail;
use Models\Employee;
use Models\Attendance;
use Models\Overtime;
use Models\Loan;
use Models\Deduction;
use Models\EmployeeSalaryComponent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PayrollCalculationService
{
    public function calculatePayroll(PayrollRun $payrollRun): void
    {
        try {
            // Update status
            $payrollRun->update(['status' => 'processing']);

            $payrollPeriod = $payrollRun->payrollPeriod;
            $employees = Employee::where('is_active', 1)->get();

            $totalGross = 0;
            $totalNet = 0;
            $totalDeductions = 0;

            foreach ($employees as $employee) {
                $payrollDetail = $this->calculateEmployeePayroll($employee, $payrollPeriod, $payrollRun);

                $totalGross += $payrollDetail->gross_salary;
                $totalNet += $payrollDetail->net_salary;
                $totalDeductions += $payrollDetail->total_deductions;
            }

            // Update payroll run totals
            $payrollRun->update([
                'total_gross_salary' => $totalGross,
                'total_net_salary' => $totalNet,
                'total_deductions' => $totalDeductions,
                'total_employees' => $employees->count(),
                'status' => 'completed',
                'processed_at' => now(),
            ]);

        } catch (Throwable $e) {
            $payrollRun->update(['status' => 'failed']);
            throw $e;
        }
    }

    private function calculateEmployeePayroll($employee, $payrollPeriod, $payrollRun): PayrollDetail
    {
        // Get employee salary components
        $salaryComponents = EmployeeSalaryComponent::with('component')
            ->where('employee_id', $employee->id)
            ->where('is_active', 1)
            ->where('effective_date', '<=', $payrollPeriod->period_end_date)
            ->where(function ($query) use ($payrollPeriod) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', $payrollPeriod->period_start_date);
            })
            ->get();

        // Calculate base salary and allowances
        $baseSalary = 0;
        $allowances = 0;
        $totalIncome = 0;

        foreach ($salaryComponents as $component) {
            if ($component->component->type === 'penghasilan') {
                if ($component->component->nature === 'tetap') {
                    $baseSalary += $component->value;
                } else {
                    $allowances += $component->value;
                }
            }
        }

        // Calculate attendance
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereBetween('attendance_date', [
                $payrollPeriod->period_start_date,
                $payrollPeriod->period_end_date
            ])
            ->get();

        $workDays = $attendance->where('status', 'hadir')->count();
        $absentDays = $attendance->where('status', 'alpha')->count();
        $sickDays = $attendance->where('status', 'sakit')->count();
        $leaveDays = $attendance->where('status', 'izin')->count();

        // Calculate overtime
        $overtimes = Overtime::where('employee_id', $employee->id)
            ->whereBetween('overtime_date', [
                $payrollPeriod->period_start_date,
                $payrollPeriod->period_end_date
            ])
            ->where('is_paid', 0)
            ->get();

        $totalOvertimeAmount = $overtimes->sum('total_amount');

        // Calculate loans
        $loans = Loan::where('employee_id', $employee->id)
            ->where('is_active', 1)
            ->where('start_date', '<=', $payrollPeriod->period_end_date)
            ->where('end_date', '>=', $payrollPeriod->period_start_date)
            ->get();

        $totalLoanPayment = $loans->sum('installment_amount');

        // Calculate other deductions
        $deductions = Deduction::where('employee_id', $employee->id)
            ->where(function ($query) use ($payrollPeriod) {
                $query->whereNull('payroll_period_id')
                    ->orWhere('payroll_period_id', $payrollPeriod->id);
            })
            ->where('is_paid', 0)
            ->get();

        $totalOtherDeductions = $deductions->sum('amount');

        // Calculate gross salary
        $grossSalary = $baseSalary + $allowances + $totalOvertimeAmount;

        // Calculate total deductions
        $totalDeductions = $totalLoanPayment + $totalOtherDeductions;

        // Calculate net salary
        $netSalary = $grossSalary - $totalDeductions;

        // Create payroll detail
        return PayrollDetail::create([
            'payroll_run_id' => $payrollRun->id,
            'employee_id' => $employee->id,
            'employee_employee_id' => $employee->employee_id,
            'employee_name' => $employee->full_name,
            'department_name' => $employee->department->name ?? null,
            'position_name' => $employee->position->name ?? null,
            'base_salary' => $baseSalary,
            'total_allowances' => $allowances,
            'total_deductions' => $totalDeductions,
            'total_overtime' => $totalOvertimeAmount,
            'total_loan_payment' => $totalLoanPayment,
            'total_other_deductions' => $totalOtherDeductions,
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'bank_name' => $employee->bank_name,
            'bank_account_number' => $employee->bank_account_number,
            'bank_account_name' => $employee->bank_account_name,
            'component_details' => json_encode([
                'base_salary' => $baseSalary,
                'allowances' => $allowances,
                'overtime' => $totalOvertimeAmount,
                'loan_payment' => $totalLoanPayment,
                'other_deductions' => $totalOtherDeductions,
                'work_days' => $workDays,
                'absent_days' => $absentDays,
                'sick_days' => $sickDays,
                'leave_days' => $leaveDays,
            ]),
        ]);
    }
}