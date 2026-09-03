<?php

namespace App\Modules\Deduction\Controllers;

use App\DataTables\DeductionDataTable;
use App\Http\Controllers\MainController;
use Models\Deduction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Models\Employee;
use Models\PayrollPeriod; 
use Throwable;  

class DeductionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Deduction());
    }

    public function index(Request $request, DeductionDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Deduction::index');
    }

    public function create(): View
    {
        $deduction = new Deduction();
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');
        $periods = PayrollPeriod::where('is_closed', 0)->pluck('code', 'id');

        $result = $this->getForm($deduction);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Deduction::create', compact('deduction', 'forms', 'groupedForms', 'employees', 'periods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payroll_period_id' => 'nullable|exists:payroll_periods,id',
            'deduction_code' => 'required|string|max:255|unique:deductions,deduction_code',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurring_until' => 'nullable|date|required_if:is_recurring,true',
            'is_paid' => 'boolean',
        ]);

        try {
            $deduction = DB::transaction(function () use ($validated) {
                return Deduction::create($validated);
            });

            return $this->successResponse($request, 'Data potongan berhasil ditambahkan.', $deduction);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan potongan.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data potongan gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $deduction = $this->model->findOrFail($id);
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');
        $periods = PayrollPeriod::where('is_closed', 0)->pluck('code', 'id');

        $result = $this->getForm($deduction);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Deduction::edit', compact('deduction', 'forms', 'groupedForms', 'employees', 'periods'));
    }

    public function update(Request $request, $id)
    {
        $deduction = $this->model->findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payroll_period_id' => 'nullable|exists:payroll_periods,id',
            'deduction_code' => 'required|string|max:255|unique:deductions,deduction_code,' . $id,
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurring_until' => 'nullable|date|required_if:is_recurring,true',
            'is_paid' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($deduction, $validated) {
                $deduction->update($validated);
            });

            return $this->successResponse($request, 'Data potongan berhasil diperbarui.', $deduction);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui potongan.', ['exception' => $e, 'deduction_id' => $id]);
            return $this->errorResponse($request, 'Data potongan gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $deduction = $this->model->findOrFail($id);

            if ($deduction->is_paid) {
                return $this->errorResponse($request, 'Potongan yang sudah dibayar tidak dapat dihapus.');
            }

            DB::transaction(function () use ($deduction) {
                $deduction->delete();
            });

            return $this->successResponse($request, 'Data potongan berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus potongan.', ['exception' => $e, 'deduction_id' => $id]);
            return $this->errorResponse($request, 'Data potongan gagal dihapus.');
        }
    }

    public function markAsPaid(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $deduction = $this->model->findOrFail($id);

            DB::transaction(function () use ($deduction) {
                $deduction->update([
                    'is_paid' => true,
                ]);
            });

            return $this->successResponse($request, 'Potongan berhasil ditandai lunas.', $deduction);
        } catch (Throwable $e) {
            Log::error('Gagal menandai potongan lunas.', ['exception' => $e, 'deduction_id' => $id]);
            return $this->errorResponse($request, 'Gagal menandai potongan lunas.');
        }
    }

    public function getByEmployee($employeeId): JsonResponse
    {
        try {
            $deductions = Deduction::where('employee_id', $employeeId)
                ->where('is_paid', 0)
                ->get();

            return response()->json(['success' => true, 'data' => $deductions]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil potongan karyawan.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}
