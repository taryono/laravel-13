<?php

namespace App\Modules\Bonus\Controllers;

use App\DataTables\BonusDataTable;
use App\Http\Controllers\MainController;
use Models\Bonus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
use Models\Employee;
use Models\PayrollPeriod;
class BonusController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Bonus());
    }

    public function index(Request $request, BonusDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Bonus::index');
    }

    public function create(): View
    {
        $bonus = new Bonus();
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id')->toArray();
        $periods = PayrollPeriod::where('is_closed', 0)->pluck('period', 'id')->toArray();
        return view('Bonus::create', compact('bonus', 'employees', 'periods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'bonus_code' => 'required|string|max:255|unique:bonuses,bonus_code',
            'bonus_type' => 'required|in:performance,thr,bonus_year_end,project_bonus,referral,other',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'description' => 'nullable|string',
            'payroll_period_id' => 'nullable|exists:payroll_periods,id',
            'is_paid' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        try {
            $bonus = DB::transaction(function () use ($validated) {
                return Bonus::create($validated);
            });

            return $this->successResponse($request, 'Data bonus berhasil ditambahkan.', $bonus);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan bonus.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data bonus gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $bonus = $this->model->findOrFail($id);
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id')->toArray();
        $periods = PayrollPeriod::where('is_closed', 0)->pluck('period', 'id')->toArray();
        return view('Bonus::edit', compact('bonus', 'employees', 'periods'));
    }

    public function update(Request $request, $id)
    {
        $bonus = $this->model->findOrFail($id);

        if ($bonus->is_paid) {
            return $this->errorResponse($request, 'Bonus yang sudah dibayar tidak dapat diubah.');
        }

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'bonus_code' => 'required|string|max:255|unique:bonuses,bonus_code,' . $id,
            'bonus_type' => 'required|in:performance,thr,bonus_year_end,project_bonus,referral,other',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'description' => 'nullable|string',
            'payroll_period_id' => 'nullable|exists:payroll_periods,id',
            'is_paid' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($bonus, $validated) {
                $bonus->update($validated);
            });

            return $this->successResponse($request, 'Data bonus berhasil diperbarui.', $bonus);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui bonus.', ['exception' => $e, 'bonus_id' => $id]);
            return $this->errorResponse($request, 'Data bonus gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bonus = $this->model->findOrFail($id);

            if ($bonus->is_paid) {
                return $this->errorResponse($request, 'Bonus yang sudah dibayar tidak dapat dihapus.');
            }

            DB::transaction(function () use ($bonus) {
                $bonus->delete();
            });

            return $this->successResponse($request, 'Data bonus berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus bonus.', ['exception' => $e, 'bonus_id' => $id]);
            return $this->errorResponse($request, 'Data bonus gagal dihapus.');
        }
    }

    public function markAsPaid(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bonus = $this->model->findOrFail($id);

            DB::transaction(function () use ($bonus) {
                $bonus->update([
                    'is_paid' => true,
                    'paid_at' => now(),
                ]);
            });

            return $this->successResponse($request, 'Bonus berhasil ditandai lunas.', $bonus);
        } catch (Throwable $e) {
            Log::error('Gagal menandai bonus lunas.', ['exception' => $e, 'bonus_id' => $id]);
            return $this->errorResponse($request, 'Gagal menandai bonus lunas.');
        }
    }

    public function bulkStore(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'bonus_type' => 'required|in:performance,thr,bonus_year_end,project_bonus,referral,other',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'description' => 'nullable|string',
            'payroll_period_id' => 'nullable|exists:payroll_periods,id',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                foreach ($validated['employee_ids'] as $employeeId) {
                    Bonus::create([
                        'employee_id' => $employeeId,
                        'bonus_code' => 'BONUS-' . date('Ymd') . '-' . uniqid(),
                        'bonus_type' => $validated['bonus_type'],
                        'amount' => $validated['amount'],
                        'payment_date' => $validated['payment_date'],
                        'description' => $validated['description'],
                        'payroll_period_id' => $validated['payroll_period_id'],
                        'is_paid' => false,
                    ]);
                }
            });

            return $this->successResponse($request, 'Bonus massal berhasil ditambahkan.');
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan bonus massal.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Gagal menambahkan bonus massal.');
        }
    }

    public function getByEmployee($employeeId): JsonResponse
    {
        try {
            $bonuses = Bonus::where('employee_id', $employeeId)
                ->orderBy('payment_date', 'desc')
                ->get();

            return response()->json(['success' => true, 'data' => $bonuses]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil bonus karyawan.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}
