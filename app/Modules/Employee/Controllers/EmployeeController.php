<?php

namespace App\Modules\Employee\Controllers;

use App\DataTables\EmployeeDataTable;
use App\Http\Controllers\MainController;
use Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable; 

class EmployeeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Employee());
    }

    /**
     * Display a listing of employees.
     */
    public function index(Request $request, EmployeeDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Payroll::employee.index');
    }

    /**
     * Show form to create new employee.
     */
    public function create(): View
    {
        $employee = new Employee();
        $departments = Department::where('is_active', 1)->pluck('name', 'id');
        $positions = Position::where('is_active', 1)->pluck('name', 'id');
        $supervisors = Employee::where('is_active', 1)->pluck('full_name', 'id');

        $result = $this->getForm($employee);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Payroll::employee.edit',
            compact('employee', 'forms', 'groupedForms', 'departments', 'positions', 'supervisors')
        );
    }

    /**
     * Store a newly created employee.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:255|unique:employees,employee_id',
            'full_name' => 'required|string|max:255',
            'nick_name' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'supervisor_id' => 'nullable|exists:employees,id',
            'gender' => 'nullable|in:L,P',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:employees,email',
            'join_date' => 'required|date',
            'resign_date' => 'nullable|date|after:join_date',
            'employment_status' => 'required|in:tetap,kontrak,probation,freelance',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:50',
            'bpjs_ketenagakerjaan' => 'nullable|string|max:50',
            'bpjs_kesehatan' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        try {
            $employee = DB::transaction(function () use ($validated) {
                return Employee::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data karyawan berhasil ditambahkan.',
                $employee
            );

        } catch (Throwable $e) {
            Log::error('Gagal menambahkan karyawan.', [
                'exception' => $e,
                'data' => $validated,
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Data karyawan gagal ditambahkan.'
            );
        }
    }

    /**
     * Show form to edit employee.
     */
    public function edit($id): View
    {
        $employee = $this->model->findOrFail($id);
        $departments = Department::where('is_active', 1)->pluck('name', 'id');
        $positions = Position::where('is_active', 1)->pluck('name', 'id');
        $supervisors = Employee::where('is_active', 1)
            ->where('id', '!=', $id)
            ->pluck('full_name', 'id');

        $result = $this->getForm($employee);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Payroll::employee.edit',
            compact('employee', 'forms', 'groupedForms', 'departments', 'positions', 'supervisors')
        );
    }

    /**
     * Update employee.
     */
    public function update(Request $request, $id)
    {
        $employee = $this->model->findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|string|max:255|unique:employees,employee_id,' . $id,
            'full_name' => 'required|string|max:255',
            'nick_name' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'supervisor_id' => 'nullable|exists:employees,id',
            'gender' => 'nullable|in:L,P',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:employees,email,' . $id,
            'join_date' => 'required|date',
            'resign_date' => 'nullable|date|after:join_date',
            'employment_status' => 'required|in:tetap,kontrak,probation,freelance',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:50',
            'bpjs_ketenagakerjaan' => 'nullable|string|max:50',
            'bpjs_kesehatan' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($employee, $validated) {
                $employee->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data karyawan berhasil diperbarui.',
                $employee
            );

        } catch (Throwable $e) {
            Log::error('Gagal memperbarui karyawan.', [
                'exception' => $e,
                'employee_id' => $id,
                'data' => $validated,
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Data karyawan gagal diperbarui.'
            );
        }
    }

    /**
     * Delete employee.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $employee = $this->model->findOrFail($id);

            // Check if employee has payroll records
            if ($employee->payrollDetails()->exists()) {
                return $this->errorResponse(
                    $request,
                    'Karyawan tidak dapat dihapus karena sudah memiliki data payroll.'
                );
            }

            DB::transaction(function () use ($employee) {
                $employee->delete();
            });

            return $this->successResponse(
                $request,
                'Data karyawan berhasil dihapus.'
            );

        } catch (Throwable $e) {
            Log::error('Gagal menghapus karyawan.', [
                'exception' => $e,
                'employee_id' => $id,
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Data karyawan gagal dihapus.'
            );
        }
    }
}