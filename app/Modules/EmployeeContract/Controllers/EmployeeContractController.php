<?php

namespace App\Modules\EmployeeContract\Controllers;

use App\DataTables\EmployeeContractDataTable;
use App\Http\Controllers\MainController;
use Models\EmployeeContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;  
use Models\Employee; 

class EmployeeContractController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EmployeeContract());
    }

    public function index(Request $request, EmployeeContractDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('EmployeeContract::index');
    }

    public function create(): View
    {
        $contract = new EmployeeContract();
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');

        $result = $this->getForm($contract);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('EmployeeContract::edit', compact('contract', 'forms', 'groupedForms', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'contract_code' => 'required|string|max:255|unique:employee_contracts,contract_code',
            'contract_type' => 'required|in:permanent,fixed_term,probation,freelance,outsource',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'probation_end_date' => 'nullable|date|after:start_date',
            'salary' => 'required|numeric|min:0',
            'benefits' => 'nullable|json',
            'document_path' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        try {
            $contract = DB::transaction(function () use ($validated) {
                return EmployeeContract::create($validated);
            });

            return $this->successResponse($request, 'Data kontrak karyawan berhasil ditambahkan.', $contract);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan kontrak karyawan.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data kontrak karyawan gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $contract = $this->model->findOrFail($id);
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');

        $result = $this->getForm($contract);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('EmployeeContract::edit', compact('contract', 'forms', 'groupedForms', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $contract = $this->model->findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'contract_code' => 'required|string|max:255|unique:employee_contracts,contract_code,' . $id,
            'contract_type' => 'required|in:permanent,fixed_term,probation,freelance,outsource',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'probation_end_date' => 'nullable|date|after:start_date',
            'salary' => 'required|numeric|min:0',
            'benefits' => 'nullable|json',
            'document_path' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($contract, $validated) {
                $contract->update($validated);
            });

            return $this->successResponse($request, 'Data kontrak karyawan berhasil diperbarui.', $contract);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui kontrak karyawan.', ['exception' => $e, 'contract_id' => $id]);
            return $this->errorResponse($request, 'Data kontrak karyawan gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $contract = $this->model->findOrFail($id);
            DB::transaction(function () use ($contract) {
                $contract->delete();
            });

            return $this->successResponse($request, 'Data kontrak karyawan berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus kontrak karyawan.', ['exception' => $e, 'contract_id' => $id]);
            return $this->errorResponse($request, 'Data kontrak karyawan gagal dihapus.');
        }
    }

    public function renew(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $oldContract = $this->model->findOrFail($id);

            DB::transaction(function () use ($oldContract, $request) {
                // End old contract
                $oldContract->update([
                    'is_active' => false,
                    'end_date' => now()->subDay(),
                ]);

                // Create new contract
                $newContract = $oldContract->replicate();
                $newContract->contract_code = $oldContract->contract_code . '-R' . date('Ymd');
                $newContract->start_date = now();
                $newContract->end_date = $request->get('end_date');
                $newContract->is_active = true;
                $newContract->save();
            });

            return $this->successResponse($request, 'Kontrak karyawan berhasil diperpanjang.');
        } catch (Throwable $e) {
            Log::error('Gagal memperpanjang kontrak.', ['exception' => $e, 'contract_id' => $id]);
            return $this->errorResponse($request, 'Gagal memperpanjang kontrak.');
        }
    }

    public function getByEmployee($employeeId): JsonResponse
    {
        try {
            $contracts = EmployeeContract::where('employee_id', $employeeId)
                ->orderBy('start_date', 'desc')
                ->get();

            return response()->json(['success' => true, 'data' => $contracts]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil kontrak karyawan.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }

    public function getActiveContract($employeeId): JsonResponse
    {
        try {
            $contract = EmployeeContract::where('employee_id', $employeeId)
                ->where('is_active', 1)
                ->first();

            return response()->json(['success' => true, 'data' => $contract]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil kontrak aktif.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}
