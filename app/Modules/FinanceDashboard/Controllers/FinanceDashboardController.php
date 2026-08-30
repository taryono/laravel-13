<?php

namespace App\Modules\FinanceDashboard\Controllers;

use App\DataTables\FinanceDashboardDataTable;
use App\Http\Controllers\MainController;
use Models\FinanceDashboard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Models\PayrollRun;
use Models\Employee;
use Models\Attendance;
use Models\JournalEntry;
use Models\Invoice; 
use Throwable; 

class FinanceDashboardController extends Controller
{
    public function index(): View
    {
        // Get statistics
        $stats = $this->getStats();
        $payrollStats = $this->getPayrollStats();
        $financeStats = $this->getFinanceStats();
        $recentActivities = $this->getRecentActivities();

        return view('System::finance_dashboard.index', compact('stats', 'payrollStats', 'financeStats', 'recentActivities'));
    }

    private function getStats(): array
    {
        return [
            'total_employees' => Employee::where('is_active', 1)->count(),
            'total_departments' => DB::table('departments')->where('is_active', 1)->count(),
            'total_positions' => DB::table('positions')->where('is_active', 1)->count(),
            'active_payroll' => PayrollRun::where('status', 'processing')->count(),
            'pending_invoices' => Invoice::where('status', 'sent')->count(),
            'overdue_invoices' => Invoice::where('status', 'sent')
                ->where('due_date', '<', now())
                ->count(),
        ];
    }

    private function getPayrollStats(): array
    {
        $currentMonth = now()->format('Y-m');
        
        $currentPayroll = PayrollRun::whereHas('payrollPeriod', function($query) use ($currentMonth) {
            $query->where('period', $currentMonth);
        })->first();

        return [
            'total_gross' => $currentPayroll ? $currentPayroll->total_gross_salary : 0,
            'total_net' => $currentPayroll ? $currentPayroll->total_net_salary : 0,
            'total_employees' => $currentPayroll ? $currentPayroll->total_employees : 0,
            'total_overtime' => $currentPayroll ? $currentPayroll->payrollDetails()->sum('total_overtime') : 0,
            'total_deductions' => $currentPayroll ? $currentPayroll->total_deductions : 0,
            'status' => $currentPayroll ? $currentPayroll->status : 'No data',
        ];
    }

    private function getFinanceStats(): array
    {
        $currentMonth = now()->format('Y-m');
        
        $totalRevenue = JournalEntry::where('period', $currentMonth)
            ->whereHas('details', function($query) {
                $query->whereHas('account.category', function($q) {
                    $q->where('type', 'pendapatan');
                });
            })
            ->sum('total_credit');

        $totalExpenses = JournalEntry::where('period', $currentMonth)
            ->whereHas('details', function($query) {
                $query->whereHas('account.category', function($q) {
                    $q->where('type', 'beban');
                });
            })
            ->sum('total_debit');

        $accountsReceivable = Invoice::where('status', 'sent')
            ->where('due_date', '>=', now())
            ->sum('total_amount');

        $accountsPayable = DB::table('expenses')
            ->where('payment_status', 'unpaid')
            ->sum('total_amount');

        return [
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'net_income' => $totalRevenue - $totalExpenses,
            'accounts_receivable' => $accountsReceivable,
            'accounts_payable' => $accountsPayable,
        ];
    }

    private function getRecentActivities(): array
    {
        // Get recent payroll runs
        $recentPayrolls = PayrollRun::with('payrollPeriod')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'payroll',
                    'description' => 'Payroll ' . $item->payrollPeriod->period_month . '/' . $item->payrollPeriod->period_year,
                    'status' => $item->status,
                    'created_at' => $item->created_at,
                ];
            });

        // Get recent invoices
        $recentInvoices = Invoice::orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'invoice',
                    'description' => 'Invoice ' . $item->invoice_number . ' - ' . $item->customer_name,
                    'status' => $item->status,
                    'created_at' => $item->created_at,
                ];
            });

        // Get recent journal entries
        $recentJournals = JournalEntry::orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'journal',
                    'description' => 'Jurnal ' . $item->journal_number,
                    'status' => $item->status,
                    'created_at' => $item->created_at,
                ];
            });

        // Combine and sort by created_at
        $activities = $recentPayrolls->concat($recentInvoices)->concat($recentJournals)
            ->sortByDesc('created_at')
            ->take(10)
            ->values()
            ->toArray();

        return $activities;
    }

    public function getChartData(Request $request): \Illuminate\Http\JsonResponse
    {
        $period = $request->get('period', 'year');
        $data = [];

        switch ($period) {
            case 'year':
                // Monthly data for current year
                for ($i = 1; $i <= 12; $i++) {
                    $month = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $periodStr = date('Y') . '-' . $month;
                    
                    $revenue = JournalEntry::where('period', $periodStr)
                        ->whereHas('details', function($query) {
                            $query->whereHas('account.category', function($q) {
                                $q->where('type', 'pendapatan');
                            });
                        })
                        ->sum('total_credit');

                    $expenses = JournalEntry::where('period', $periodStr)
                        ->whereHas('details', function($query) {
                            $query->whereHas('account.category', function($q) {
                                $q->where('type', 'beban');
                            });
                        })
                        ->sum('total_debit');

                    $data[] = [
                        'month' => $month,
                        'revenue' => $revenue,
                        'expenses' => $expenses,
                        'profit' => $revenue - $expenses,
                    ];
                }
                break;

            case 'month':
                // Daily data for current month
                $daysInMonth = now()->daysInMonth;
                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $date = now()->startOfMonth()->addDays($i - 1)->format('Y-m-d');
                    // Get daily data
                    $data[] = [
                        'date' => $date,
                        'value' => rand(1000, 10000), // Sample data
                    ];
                }
                break;
        }

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function getNotificationBadge(): \Illuminate\Http\JsonResponse
    {
        $count = 0;

        // Overdue invoices
        $count += Invoice::where('status', 'sent')
            ->where('due_date', '<', now())
            ->count();

        // Pending payroll
        $count += PayrollRun::where('status', 'draft')->count();

        // Pending approvals
        $count += DB::table('payment_requests')
            ->where('status', 'pending_approval')
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count,
            'items' => [
                'overdue_invoices' => Invoice::where('status', 'sent')
                    ->where('due_date', '<', now())
                    ->count(),
                'pending_payroll' => PayrollRun::where('status', 'draft')->count(),
                'pending_approvals' => DB::table('payment_requests')
                    ->where('status', 'pending_approval')
                    ->count(),
            ]
        ]);
    }
}
