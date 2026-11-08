<?php

namespace App\Modules\Employee\Controllers;

use App\DataTables\EmployeeDataTable;
use App\Http\Controllers\MainController;
use Models\Employee;
use Models\Department;
use Models\Position;
use Models\EmploymentStatus;
use Models\SalaryStructure;
use Models\BankAccount;
use App\Http\Requests\StoreEmployeeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Support\EmployeeNumberGenerator;
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

        return $dataTable->render('Employee::index');
    }

    /**
     * Show form to create new employee.
     */
    public function create(): View
    { 
        return view('Employee::create', [
            'employee'            => new Employee(),
            'departments'         => Department::pluck('name', 'id')->toArray(),
            'positions'           => Position::pluck('name', 'id')->toArray(),
            'supervisors'         => Employee::where('is_active', 1)->pluck('name', 'id')->toArray(),
            'employmentStatuses'  => EmploymentStatus::where('is_active', 1)->pluck('name', 'id')->toArray(),
            'salaryStructures'    => SalaryStructure::pluck('name', 'id')->toArray(),
            'bankAccounts'        => BankAccount::where('is_active', 1)->pluck('bank_name', 'id')->toArray(),
        ]);
    }

    /**
     * Store a newly created employee.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $validated = $this->validateEmployee($request);

        try {
            $employee = DB::transaction(function () use ($validated, $request) {
                // Handle photo
                if ($request->hasFile('photo')) {
                    $validated['photo_path'] = $request->file('photo')->store('employees/photos', 'public');
                }

                // organization_id dari auth (jangan percaya input)
                $validated['organization_id'] = Auth::user()->organization_id;
                $validated['created_by']      = Auth::id();

                // Auto-generate employee_number (via observer lebih baik)
                $validated['employee_number'] = $validated['employee_number']
                    ?? EmployeeNumberGenerator::generate($validated['organization_id']);

                $employee = Employee::create($validated);

                // Simpan bank accounts
                foreach ($request->input('bank_accounts', []) as $bank) {
                    if (empty($bank['bank_name']) && empty($bank['account_number'])) {
                        continue; // skip baris kosong
                    }
                    $employee->bankAccounts()->create([
                        'organization_id' => $employee->organization_id,
                        'bank_name'       => $bank['bank_name'] ?? null,
                        'account_number'  => $bank['account_number'] ?? null,
                        'account_name'    => $bank['account_name'] ?? null,
                        'is_primary'      => !empty($bank['is_primary']),
                    ]);
                }

                return $employee;
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
                'user_id' => Auth::id(),
            ]);

            return $this->errorResponse(
                $request,
                'Data karyawan gagal ditambahkan: ' . $e->getMessage()
            );
        }
    }

    /**
     * Show form to edit employee.
     */
    public function edit($id): View
    {
        $employee = $this->model->findOrFail($id);
        
         $employee->load('bankAccounts');

        return view('Employee::edit', [
            'employee'            => $employee,
            'departments'         => Department::pluck('name', 'id'),
            'positions'           => Position::pluck('name', 'id'),
            'supervisors'         => Employee::where('id', '!=', $employee->id)
                                            ->where('is_active', 1)
                                            ->pluck('name', 'id'),
            'employmentStatuses'  => EmploymentStatus::where('is_active', 1)->pluck('name', 'id'),
            'salaryStructures'    => SalaryStructure::pluck('name', 'id'),
        ]);
    }

    /**
     * Update employee.
     */
    public function update(Request $request, $id)
    {
        $employee = $this->model->findOrFail($id);
        $validated = $this->validateEmployee($request, $id);

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
                'nik' => $id,
                'data' => $validated,
                'user_id' => Auth::id(),
            ]);

            return $this->errorResponse(
                $request,
                'Data karyawan gagal diperbarui: ' . $e->getMessage()
            );
        }
    }

    /**
     * Show employee details.
     */
    public function show($id): View
    {
        $employee = $this->model->with([
            'department', 
            'position', 
            'supervisor',
            'subordinates'
        ])->findOrFail($id);

        return view('Employee::show', compact('employee'));
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

            // Check if employee has attendance records
            if ($employee->attendanceLogs()->exists() || $employee->attendanceDaily()->exists()) {
                return $this->errorResponse(
                    $request,
                    'Karyawan tidak dapat dihapus karena sudah memiliki data absensi.'
                );
            }

            // Check if employee has subordinates
            if ($employee->subordinates()->exists()) {
                return $this->errorResponse(
                    $request,
                    'Karyawan tidak dapat dihapus karena masih memiliki bawahan.'
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
                'nik' => $id,
                'user_id' => Auth::id(),
            ]);

            return $this->errorResponse(
                $request,
                'Data karyawan gagal dihapus: ' . $e->getMessage()
            );
        }
    }

    /**
     * Get employee options for dropdown (AJAX)
     */
    public function options(Request $request): JsonResponse
    {
        $search = $request->get('q');
        $employees = Employee::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('nik', 'LIKE', "%{$search}%");
        })
        ->where('is_active', true)
        ->limit(20)
        ->get(['id', 'nik', 'name']);

        return response()->json($employees);
    }

    /**
     * Get employees by department (AJAX)
     */
    public function getByDepartment(Request $request): JsonResponse
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);

        $employees = Employee::where('department_id', $request->department_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'nik', 'name']);

        return response()->json([
            'success' => true,
            'data' => $employees,
        ]);
    }

    /**
     * Validate employee data.
     */
    private function validateEmployee(Request $request, $id = null): array
    {
        $rules = [
            'nik' => 'required|string|max:255|unique:employees,nik' . ($id ? ',' . $id : ''),
            'name' => 'required|string|max:255',
            'nick_name' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'supervisor_id' => 'nullable|exists:employees,id' . ($id ? '|not_in:' . $id : ''),
            'gender' => 'nullable|in:L,P',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date|before:today',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:employees,email' . ($id ? ',' . $id : ''),
            'join_date' => 'required|date|before_or_equal:today',
            'resign_date' => 'nullable|date|after:join_date|before_or_equal:today',
            'employment_status' => 'required|in:tetap,kontrak,probation,freelance',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:50',
            'bpjs_ketenagakerjaan' => 'nullable|string|max:50',
            'bpjs_kesehatan' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ];

        return $request->validate($rules);
    }
}