<?php

namespace App\Modules\EmployeeMutation\Controllers;

use App\DataTables\EmployeeMutationDataTable;
use App\Http\Controllers\MainController;
use Models\EmployeeMutation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable; 
use Models\Employee;
use Models\Department;
use Models\Position; 
class EmployeeMutationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EmployeeMutation());
    }

    public function index(Request $request, EmployeeMutationDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Payroll::employee_mutation.index');
    }

    public function create(): View
    {
        $mutation = new EmployeeMutation();
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');
        $departments = Department::where('is_active', 1)->pluck('name', 'id');
        $positions = Position::where('is_active', 1)->pluck('name', 'id');

        $result = $this->getForm($mutation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Payroll::employee_mutation.edit', compact('mutation', 'forms', 'groupedForms', 'employees', 'departments', 'positions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'mutation_code' => 'required|string|max:255|unique:employee_mutations,mutation_code',
            'mutation_type' => 'required|in:promotion,demotion,transfer,department_change,position_change',
            'old_department_id' => 'nullable|exists:departments,id',
            'new_department_id' => 'nullable|exists:departments,id',
            'old_position_id' => 'nullable|exists:positions,id',
            'new_position_id' => 'nullable|exists:positions,id',
            'old_salary' => 'nullable|numeric|min:0',
            'new_salary' => 'nullable|numeric|min:0',
            'effective_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,approved,rejected,cancelled',
        ]);

        try {
            $mutation = DB::transaction(function () use ($validated) {
                return EmployeeMutation::create($validated);
            });

            return $this->successResponse($request, 'Data mutasi karyawan berhasil ditambahkan.', $mutation);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan mutasi.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data mutasi karyawan gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $mutation = $this->model->findOrFail($id);
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');
        $departments = Department::where('is_active', 1)->pluck('name', 'id');
        $positions = Position::where('is_active', 1)->pluck('name', 'id');

        $result = $this->getForm($mutation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Payroll::employee_mutation.edit', compact('mutation', 'forms', 'groupedForms', 'employees', 'departments', 'positions'));
    }

    public function update(Request $request, $id)
    {
        $mutation = $this->model->findOrFail($id);

        if ($mutation->status === 'approved') {
            return $this->errorResponse($request, 'Mutasi yang sudah disetujui tidak dapat diubah.');
        }

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'mutation_code' => 'required|string|max:255|unique:employee_mutations,mutation_code,' . $id,
            'mutation_type' => 'required|in:promotion,demotion,transfer,department_change,position_change',
            'old_department_id' => 'nullable|exists:departments,id',
            'new_department_id' => 'nullable|exists:departments,id',
            'old_position_id' => 'nullable|exists:positions,id',
            'new_position_id' => 'nullable|exists:positions,id',
            'old_salary' => 'nullable|numeric|min:0',
            'new_salary' => 'nullable|numeric|min:0',
            'effective_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,approved,rejected,cancelled',
        ]);

        try {
            DB::transaction(function () use ($mutation, $validated) {
                $mutation->update($validated);
            });

            return $this->successResponse($request, 'Data mutasi karyawan berhasil diperbarui.', $mutation);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui mutasi.', ['exception' => $e, 'mutation_id' => $id]);
            return $this->errorResponse($request, 'Data mutasi karyawan gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $mutation = $this->model->findOrFail($id);

            if ($mutation->status === 'approved') {
                return $this->errorResponse($request, 'Mutasi yang sudah disetujui tidak dapat dihapus.');
            }

            DB::transaction(function () use ($mutation) {
                $mutation->delete();
            });

            return $this->successResponse($request, 'Data mutasi karyawan berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus mutasi.', ['exception' => $e, 'mutation_id' => $id]);
            return $this->errorResponse($request, 'Data mutasi karyawan gagal dihapus.');
        }
    }

    public function approve(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $mutation = $this->model->findOrFail($id);

            DB::transaction(function () use ($mutation, $request) {
                $mutation->update([
                    'status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);

                // Apply mutation to employee
                $employee = $mutation->employee;
                
                if ($mutation->new_department_id) {
                    $employee->department_id = $mutation->new_department_id;
                }
                
                if ($mutation->new_position_id) {
                    $employee->position_id = $mutation->new_position_id;
                }
                
                if ($mutation->new_salary) {
                    $employee->base_salary = $mutation->new_salary;
                }
                
                $employee->save();
            });

            return $this->successResponse($request, 'Mutasi karyawan berhasil disetujui dan diterapkan.', $mutation);
        } catch (Throwable $e) {
            Log::error('Gagal menyetujui mutasi.', ['exception' => $e, 'mutation_id' => $id]);
            return $this->errorResponse($request, 'Gagal menyetujui mutasi.');
        }
    }

    public function reject(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $mutation = $this->model->findOrFail($id);

            DB::transaction(function () use ($mutation) {
                $mutation->update([
                    'status' => 'rejected',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);
            });

            return $this->successResponse($request, 'Mutasi karyawan berhasil ditolak.', $mutation);
        } catch (Throwable $e) {
            Log::error('Gagal menolak mutasi.', ['exception' => $e, 'mutation_id' => $id]);
            return $this->errorResponse($request, 'Gagal menolak mutasi.');
        }
    }

    public function getByEmployee($employeeId): JsonResponse
    {
        try {
            $mutations = EmployeeMutation::where('employee_id', $employeeId)
                ->orderBy('effective_date', 'desc')
                ->get();

            return response()->json(['success' => true, 'data' => $mutations]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil mutasi karyawan.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}