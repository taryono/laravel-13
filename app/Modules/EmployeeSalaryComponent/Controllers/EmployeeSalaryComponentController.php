<?php

namespace App\Modules\EmployeeSalaryComponent\Controllers;

use App\DataTables\EmployeeSalaryComponentDataTable;
use App\Http\Controllers\MainController;
use Models\EmployeeSalaryComponent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable; 

class EmployeeSalaryComponentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EmployeeSalaryComponent());
    }

    public function index(Request $request, EmployeeSalaryComponentDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Payroll::employee_salary_component.index');
    }

    public function create(): View
    {
        $employeeComponent = new EmployeeSalaryComponent();
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');
        $components = SalaryComponent::where('is_active', 1)->pluck('name', 'id');

        $result = $this->getForm($employeeComponent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Payroll::employee_salary_component.edit', compact('employeeComponent', 'forms', 'groupedForms', 'employees', 'components'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary_component_id' => 'required|exists:salary_components,id',
            'value' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:effective_date',
            'is_active' => 'boolean',
        ]);

        try {
            $employeeComponent = DB::transaction(function () use ($validated) {
                return EmployeeSalaryComponent::create($validated);
            });

            return $this->successResponse($request, 'Data komponen gaji karyawan berhasil ditambahkan.', $employeeComponent);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan komponen gaji karyawan.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data komponen gaji karyawan gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $employeeComponent = $this->model->findOrFail($id);
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');
        $components = SalaryComponent::where('is_active', 1)->pluck('name', 'id');

        $result = $this->getForm($employeeComponent);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Payroll::employee_salary_component.edit', compact('employeeComponent', 'forms', 'groupedForms', 'employees', 'components'));
    }

    public function update(Request $request, $id)
    {
        $employeeComponent = $this->model->findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary_component_id' => 'required|exists:salary_components,id',
            'value' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:effective_date',
            'is_active' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($employeeComponent, $validated) {
                $employeeComponent->update($validated);
            });

            return $this->successResponse($request, 'Data komponen gaji karyawan berhasil diperbarui.', $employeeComponent);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui komponen gaji karyawan.', ['exception' => $e, 'component_id' => $id]);
            return $this->errorResponse($request, 'Data komponen gaji karyawan gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $employeeComponent = $this->model->findOrFail($id);
            DB::transaction(function () use ($employeeComponent) {
                $employeeComponent->delete();
            });

            return $this->successResponse($request, 'Data komponen gaji karyawan berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus komponen gaji karyawan.', ['exception' => $e, 'component_id' => $id]);
            return $this->errorResponse($request, 'Data komponen gaji karyawan gagal dihapus.');
        }
    }

    public function getByEmployee($employeeId): JsonResponse
    {
        try {
            $components = EmployeeSalaryComponent::with('component')
                ->where('employee_id', $employeeId)
                ->where('is_active', 1)
                ->get();

            return response()->json(['success' => true, 'data' => $components]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil komponen karyawan.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }

    public function bulkStore(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'components' => 'required|array',
            'components.*.salary_component_id' => 'required|exists:salary_components,id',
            'components.*.value' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                foreach ($validated['components'] as $component) {
                    EmployeeSalaryComponent::updateOrCreate(
                        [
                            'employee_id' => $validated['employee_id'],
                            'salary_component_id' => $component['salary_component_id'],
                            'effective_date' => $validated['effective_date'],
                        ],
                        [
                            'value' => $component['value'],
                            'is_active' => true,
                        ]
                    );
                }
            });

            return $this->successResponse($request, 'Data komponen gaji karyawan berhasil ditambahkan secara massal.');
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan komponen gaji karyawan secara massal.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Gagal menambahkan komponen gaji karyawan secara massal.');
        }
    }
}
