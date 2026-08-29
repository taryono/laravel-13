<?php

namespace App\Modules\LeaveBalance\Controllers;

use App\DataTables\LeaveBalanceDataTable;
use App\Http\Controllers\MainController;
use Models\LeaveBalance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable; 
use Models\Employee;

class LeaveBalanceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new LeaveBalance());
    }

    public function index(Request $request, LeaveBalanceDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Payroll::leave_balance.index');
    }

    public function create(): View
    {
        $balance = new LeaveBalance();
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');

        $result = $this->getForm($balance);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Payroll::leave_balance.edit', compact('balance', 'forms', 'groupedForms', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|in:annual,sick,maternity,paternity,unpaid,emergency,other',
            'year' => 'required|integer|min:2000|max:2100',
            'quota' => 'required|integer|min:0',
            'used' => 'nullable|integer|min:0',
            'remaining' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            $balance = DB::transaction(function () use ($validated) {
                $validated['remaining'] = $validated['quota'] - ($validated['used'] ?? 0);
                return LeaveBalance::create($validated);
            });

            return $this->successResponse($request, 'Data saldo cuti berhasil ditambahkan.', $balance);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan saldo cuti.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data saldo cuti gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $balance = $this->model->findOrFail($id);
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');

        $result = $this->getForm($balance);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Payroll::leave_balance.edit', compact('balance', 'forms', 'groupedForms', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $balance = $this->model->findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|in:annual,sick,maternity,paternity,unpaid,emergency,other',
            'year' => 'required|integer|min:2000|max:2100',
            'quota' => 'required|integer|min:0',
            'used' => 'nullable|integer|min:0',
            'remaining' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($balance, $validated) {
                $validated['remaining'] = $validated['quota'] - ($validated['used'] ?? 0);
                $balance->update($validated);
            });

            return $this->successResponse($request, 'Data saldo cuti berhasil diperbarui.', $balance);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui saldo cuti.', ['exception' => $e, 'balance_id' => $id]);
            return $this->errorResponse($request, 'Data saldo cuti gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $balance = $this->model->findOrFail($id);
            DB::transaction(function () use ($balance) {
                $balance->delete();
            });

            return $this->successResponse($request, 'Data saldo cuti berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus saldo cuti.', ['exception' => $e, 'balance_id' => $id]);
            return $this->errorResponse($request, 'Data saldo cuti gagal dihapus.');
        }
    }

    public function getByEmployee($employeeId): JsonResponse
    {
        try {
            $balances = LeaveBalance::where('employee_id', $employeeId)->get();
            return response()->json(['success' => true, 'data' => $balances]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil saldo cuti.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }

    public function getByYear($year): JsonResponse
    {
        try {
            $balances = LeaveBalance::with('employee')
                ->where('year', $year)
                ->get();

            return response()->json(['success' => true, 'data' => $balances]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil saldo cuti per tahun.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }

    public function resetForNewYear(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'employees' => 'nullable|array',
            'employees.*' => 'exists:employees,id',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $query = LeaveBalance::where('year', $validated['year']);

                if (!empty($validated['employees'])) {
                    $query->whereIn('employee_id', $validated['employees']);
                }

                $query->update([
                    'quota' => 12, // Default 12 days
                    'used' => 0,
                    'remaining' => 12,
                    'notes' => 'Reset tahunan',
                ]);
            });

            return $this->successResponse($request, 'Saldo cuti berhasil direset.');
        } catch (Throwable $e) {
            Log::error('Gagal reset saldo cuti.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Gagal reset saldo cuti.');
        }
    }
}
