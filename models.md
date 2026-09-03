<?php

namespace Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

// ==================== ACCOUNTING MODELS ====================

class Organization extends BasedModel
{
    protected $table = 'organizations';
    
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'parent_id');
    }
    
    public function children(): HasMany
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }
    
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_users')
                    ->withPivot('is_default', 'is_active');
    }
}

class OrganizationUser extends BasedModel
{
    protected $table = 'organization_users';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

class AccountCategory extends BasedModel
{
    protected $table = 'account_categories';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}

class Account extends BasedModel
{
    protected $table = 'accounts';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(AccountCategory::class, 'account_category_id');
    }
    
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }
    
    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id');
    }
    
    public function debitMappings(): HasMany
    {
        return $this->hasMany(AccountMapping::class, 'debit_account_id');
    }
    
    public function creditMappings(): HasMany
    {
        return $this->hasMany(AccountMapping::class, 'credit_account_id');
    }
}

class AccountMapping extends BasedModel
{
    protected $table = 'account_mappings';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function debitAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'debit_account_id');
    }
    
    public function creditAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'credit_account_id');
    }
}

class FiscalYear extends BasedModel
{
    protected $table = 'fiscal_years';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function accountingPeriods(): HasMany
    {
        return $this->hasMany(AccountingPeriod::class);
    }
    
    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }
    
    public function openingBalances(): HasMany
    {
        return $this->hasMany(OpeningBalance::class);
    }
}

class AccountingPeriod extends BasedModel
{
    protected $table = 'accounting_periods';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class);
    }
    
    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }
    
    public function budgetDetails(): HasMany
    {
        return $this->hasMany(BudgetDetail::class);
    }
    
    public function assetDepreciations(): HasMany
    {
        return $this->hasMany(AssetDepreciation::class);
    }
}

class CostCenter extends BasedModel
{
    protected $table = 'cost_centers';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function parent(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class, 'parent_id');
    }
    
    public function children(): HasMany
    {
        return $this->hasMany(CostCenter::class, 'parent_id');
    }
    
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}

class JournalEntry extends BasedModel
{
    protected $table = 'journal_entries';

    const SOURCE_MANUAL = 'manual';
    const SOURCE_PAYROLL = 'payroll';
    const SOURCE_SALES = 'sales';
    const SOURCE_PURCHASE = 'purchase';
    const SOURCE_EXPENSE = 'expense';
    const SOURCE_TRANSFER = 'transfer';
    const SOURCE_TAX = 'tax';
    const SOURCE_OPENING = 'opening';
    const SOURCE_ADJUSTMENT = 'adjustment';
    const SOURCE_CLOSING = 'closing';
    
    const SOURCES = [
        self::SOURCE_MANUAL => 'Manual',
        self::SOURCE_PAYROLL => 'Payroll',
        self::SOURCE_SALES => 'Sales',
        self::SOURCE_PURCHASE => 'Purchase',
        self::SOURCE_EXPENSE => 'Expense',
        self::SOURCE_TRANSFER => 'Transfer',
        self::SOURCE_TAX => 'Tax',
        self::SOURCE_OPENING => 'Opening Balance',
        self::SOURCE_ADJUSTMENT => 'Adjustment',
        self::SOURCE_CLOSING => 'Closing',
    ];
    
    // Status Enum
    const STATUS_DRAFT = 'draft';
    const STATUS_APPROVED = 'approved';
    const STATUS_POSTED = 'posted';
    const STATUS_VOID = 'void';
    
    const STATUSES = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_POSTED => 'Posted',
        self::STATUS_VOID => 'Void',
    ];
    
    public function getSourceLabelAttribute(): string
    {
        return self::SOURCES[$this->source_type] ?? $this->source_type;
    }
    
    public function isPosted(): bool
    {
        return $this->status === self::STATUS_POSTED;
    }
    
    public function canBeVoided(): bool
    {
        return in_array($this->status, [self::STATUS_APPROVED, self::STATUS_POSTED]);
    }
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function accountingPeriod(): BelongsTo
    {
        return $this->belongsTo(AccountingPeriod::class);
    }
    
    public function details(): HasMany
    {
        return $this->hasMany(JournalDetail::class);
    }
    
    public function reversalOf(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class, 'reversal_of_id');
    }
    
    public function reversal(): HasOne
    {
        return $this->hasOne(JournalEntry::class, 'reversal_of_id');
    }
}

class JournalDetail extends BasedModel
{
    protected $table = 'journal_details';
    
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }
    
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
    
    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }
}

class OpeningBalance extends BasedModel
{
    protected $table = 'opening_balances';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class);
    }
    
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
    
    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }
    
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }
}

class Budget extends BasedModel
{
    protected $table = 'budgets';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class);
    }
    
    public function details(): HasMany
    {
        return $this->hasMany(BudgetDetail::class);
    }
}

class BudgetDetail extends BasedModel
{
    protected $table = 'budget_details';
    
    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }
    
    public function accountingPeriod(): BelongsTo
    {
        return $this->belongsTo(AccountingPeriod::class);
    }
    
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
    
    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }
}

// ==================== MASTER DATA MODELS ====================

class Department extends BasedModel
{
    protected $table = 'departments';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }
    
    public function children(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_id');
    }
    
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
    
    public function costCenters(): HasMany
    {
        return $this->hasMany(CostCenter::class);
    }
}

class Position extends BasedModel
{
    protected $table = 'positions';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}

class Employee extends BasedModel
{
    protected $table = 'employees';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
    
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }
    
    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'supervisor_id');
    }
    
    public function salaryStructure(): BelongsTo
    {
        return $this->belongsTo(SalaryStructure::class);
    }
    
    public function contracts(): HasMany
    {
        return $this->hasMany(EmployeeContract::class);
    }
    
    public function salaryComponents(): HasMany
    {
        return $this->hasMany(EmployeeSalaryComponent::class);
    }
    
    public function bonuses(): HasMany
    {
        return $this->hasMany(EmployeeBonus::class);
    }
    
    public function deductions(): HasMany
    {
        return $this->hasMany(EmployeeDeduction::class);
    }
    
    public function leaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class);
    }
    
    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }
    
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
    
    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }
    
    public function attendanceDailies(): HasMany
    {
        return $this->hasMany(AttendanceDaily::class);
    }
    
    public function overtimes(): HasMany
    {
        return $this->hasMany(Overtime::class);
    }
    
    public function payrollDetails(): HasMany
    {
        return $this->hasMany(PayrollDetail::class);
    }
}

// ==================== CUSTOMER & VENDOR MODELS ====================

class Customer extends BasedModel
{
    protected $table = 'customers';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function receivableAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'receivable_account_id');
    }
    
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}

class Vendor extends BasedModel
{
    protected $table = 'vendors';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function payableAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'payable_account_id');
    }
    
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}

// ==================== INVOICE MODELS ====================

class Invoice extends BasedModel
{
    protected $table = 'invoices';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
    
    public function payments(): HasMany
    {
        return $this->hasMany(InvoicePayment::class);
    }
    
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }
}

class InvoiceItem extends BasedModel
{
    protected $table = 'invoice_items';
    
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
    
    public function revenueAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'revenue_account_id');
    }
}

class InvoicePayment extends BasedModel
{
    protected $table = 'invoice_payments';
    
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }
    
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }
}

// ==================== EXPENSE MODELS ====================

class Expense extends BasedModel
{
    protected $table = 'expenses';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
    
    public function items(): HasMany
    {
        return $this->hasMany(ExpenseItem::class);
    }
    
    public function payments(): HasMany
    {
        return $this->hasMany(ExpensePayment::class);
    }
    
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }
}

class ExpenseItem extends BasedModel
{
    protected $table = 'expense_items';
    
    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }
    
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
    
    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }
}

class ExpensePayment extends BasedModel
{
    protected $table = 'expense_payments';
    
    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }
    
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }
}

// ==================== BANK MODELS ====================

class BankAccount extends BasedModel
{
    protected $table = 'bank_accounts';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
    
    public function transactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class);
    }
    
    public function reconciliations(): HasMany
    {
        return $this->hasMany(Reconciliation::class);
    }
}

class BankTransaction extends BasedModel
{
    protected $table = 'bank_transactions';
    
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }
    
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }
    
    public function reconciliationDetails(): HasMany
    {
        return $this->hasMany(ReconciliationDetail::class);
    }
}

class Reconciliation extends BasedModel
{
    protected $table = 'reconciliations';
    
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }
    
    public function details(): HasMany
    {
        return $this->hasMany(ReconciliationDetail::class);
    }
}

class ReconciliationDetail extends BasedModel
{
    protected $table = 'reconciliation_details';
    
    public function reconciliation(): BelongsTo
    {
        return $this->belongsTo(Reconciliation::class);
    }
    
    public function bankTransaction(): BelongsTo
    {
        return $this->belongsTo(BankTransaction::class);
    }
    
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }
}

// ==================== PAYROLL MODELS ====================

class SalaryComponent extends BasedModel
{
    protected $table = 'salary_components';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function structures(): HasMany
    {
        return $this->hasMany(SalaryStructureComponent::class);
    }
    
    public function employeeComponents(): HasMany
    {
        return $this->hasMany(EmployeeSalaryComponent::class);
    }
}

class SalaryStructure extends BasedModel
{
    protected $table = 'salary_structures';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function components(): HasMany
    {
        return $this->hasMany(SalaryStructureComponent::class);
    }
    
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
    
    public function contracts(): HasMany
    {
        return $this->hasMany(EmployeeContract::class);
    }
}

class SalaryStructureComponent extends BasedModel
{
    protected $table = 'salary_structure_components';
    
    public function salaryStructure(): BelongsTo
    {
        return $this->belongsTo(SalaryStructure::class);
    }
    
    public function salaryComponent(): BelongsTo
    {
        return $this->belongsTo(SalaryComponent::class);
    }
}

class EmployeeSalaryComponent extends BasedModel
{
    protected $table = 'employee_salary_components';
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function salaryComponent(): BelongsTo
    {
        return $this->belongsTo(SalaryComponent::class);
    }
}

class EmployeeContract extends BasedModel
{
    protected $table = 'employee_contracts';
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function salaryStructure(): BelongsTo
    {
        return $this->belongsTo(SalaryStructure::class);
    }
}

class EmployeeBonus extends BasedModel
{
    protected $table = 'employee_bonuses';
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function payrollDetail(): BelongsTo
    {
        return $this->belongsTo(PayrollDetail::class);
    }
}

class EmployeeDeduction extends BasedModel
{
    protected $table = 'employee_deductions';
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function payrollDetail(): BelongsTo
    {
        return $this->belongsTo(PayrollDetail::class);
    }
}

class PayrollPeriod extends BasedModel
{
    protected $table = 'payroll_periods';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function runs(): HasMany
    {
        return $this->hasMany(PayrollRun::class);
    }
    
    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }
    
    public function integrations(): HasMany
    {
        return $this->hasMany(PayrollIntegration::class);
    }
}

class PayrollRun extends BasedModel
{
    protected $table = 'payroll_runs';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function payrollPeriod(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class);
    }
    
    public function details(): HasMany
    {
        return $this->hasMany(PayrollDetail::class);
    }
    
    public function paymentBatches(): HasMany
    {
        return $this->hasMany(PayrollPaymentBatch::class);
    }
    
    public function integration(): HasOne
    {
        return $this->hasOne(PayrollIntegration::class);
    }
}

class PayrollDetail extends BasedModel
{
    protected $table = 'payroll_details';
    
    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class);
    }
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function components(): HasMany
    {
        return $this->hasMany(PayrollDetailComponent::class);
    }
    
    public function payslip(): HasOne
    {
        return $this->hasOne(Payslip::class);
    }
}

class PayrollDetailComponent extends BasedModel
{
    protected $table = 'payroll_detail_components';
    
    public function payrollDetail(): BelongsTo
    {
        return $this->belongsTo(PayrollDetail::class);
    }
    
    public function salaryComponent(): BelongsTo
    {
        return $this->belongsTo(SalaryComponent::class);
    }
}

class PayrollPaymentBatch extends BasedModel
{
    protected $table = 'payroll_payment_batches';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class);
    }
    
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }
    
    public function payments(): HasMany
    {
        return $this->hasMany(PayrollPayment::class);
    }
}

class PayrollPayment extends BasedModel
{
    protected $table = 'payroll_payments';
    
    public function batch(): BelongsTo
    {
        return $this->belongsTo(PayrollPaymentBatch::class, 'payroll_payment_batch_id');
    }
    
    public function payrollDetail(): BelongsTo
    {
        return $this->belongsTo(PayrollDetail::class);
    }
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }
}

class Payslip extends BasedModel
{
    protected $table = 'payslips';
    
    public function payrollDetail(): BelongsTo
    {
        return $this->belongsTo(PayrollDetail::class);
    }
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function payrollPeriod(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class);
    }
}

class PayrollIntegration extends BasedModel
{
    protected $table = 'payroll_integrations';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class);
    }
    
    public function payrollPeriod(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class);
    }
    
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }
}

// ==================== LEAVE MODELS ====================

class LeaveType extends BasedModel
{
    protected $table = 'leave_types';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function leaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class);
    }
    
    public function balances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }
}

class EmployeeLeave extends BasedModel
{
    protected $table = 'employee_leaves';
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }
}

class LeaveBalance extends BasedModel
{
    protected $table = 'leave_balances';
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }
}

// ==================== ATTENDANCE MODELS ====================

class AttendanceLog extends BasedModel
{
    protected $table = 'attendance_logs';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function daily(): HasOne
    {
        return $this->hasOne(AttendanceDaily::class, 'source_log_id');
    }
}

class AttendanceDaily extends BasedModel
{
    protected $table = 'attendance_dailies';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function sourceLog(): BelongsTo
    {
        return $this->belongsTo(AttendanceLog::class, 'source_log_id');
    }
    
    public function overtimes(): HasMany
    {
        return $this->hasMany(Overtime::class);
    }
}

class Overtime extends BasedModel
{
    protected $table = 'overtimes';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function attendanceDaily(): BelongsTo
    {
        return $this->belongsTo(AttendanceDaily::class);
    }
}

// ==================== LOAN MODELS ====================

class Loan extends BasedModel
{
    protected $table = 'loans';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function installments(): HasMany
    {
        return $this->hasMany(LoanInstallment::class);
    }
}

class LoanInstallment extends BasedModel
{
    protected $table = 'loan_installments';
    
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }
    
    public function payrollDetail(): BelongsTo
    {
        return $this->belongsTo(PayrollDetail::class);
    }
}

// ==================== ASSET MODELS ====================

class FixedAsset extends BasedModel
{
    protected $table = 'fixed_assets';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function assetAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'asset_account_id');
    }
    
    public function accumulatedDepreciationAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'accumulated_depreciation_account_id');
    }
    
    public function depreciationExpenseAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'depreciation_expense_account_id');
    }
    
    public function depreciations(): HasMany
    {
        return $this->hasMany(AssetDepreciation::class);
    }
}

class AssetDepreciation extends BasedModel
{
    protected $table = 'asset_depreciations';
    
    public function fixedAsset(): BelongsTo
    {
        return $this->belongsTo(FixedAsset::class);
    }
    
    public function accountingPeriod(): BelongsTo
    {
        return $this->belongsTo(AccountingPeriod::class);
    }
    
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }
}

// ==================== TAX MODELS ====================

class TaxSetting extends BasedModel
{
    protected $table = 'tax_settings';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}

class TaxLiability extends BasedModel
{
    protected $table = 'tax_liabilities';
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }
    
    public function payments(): HasMany
    {
        return $this->hasMany(TaxPayment::class);
    }
}

class TaxPayment extends BasedModel
{
    protected $table = 'tax_payments';
    
    public function taxLiability(): BelongsTo
    {
        return $this->belongsTo(TaxLiability::class);
    }
    
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }
    
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }
}

// ==================== AUDIT MODELS ====================

class FinanceAuditLog extends BasedModel
{
    protected $table = 'finance_audit_logs';
    
    public $timestamps = false;
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

// ==================== USER MODEL ====================

class User extends BasedModel
{
    protected $table = 'users';
    
    use \Illuminate\Foundation\Auth\User as Authenticatable;
    use \Illuminate\Notifications\Notifiable;
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }
    
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_users')
                    ->withPivot('is_default', 'is_active');
    }
}