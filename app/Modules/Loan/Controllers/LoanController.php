<?php

namespace App\Modules\Loan\Controllers;

use App\DataTables\LoanDataTable;
use App\Http\Controllers\MainController;
use Models\Loan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Models\LoanInstallment;
use Models\Employee; 
use Throwable;    

class LoanController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Loan());
    }

    public function index(Request $request, LoanDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Payroll::loan.index');
    }

    public function create(): View
    {
        $loan = new Loan();
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');

        $result = $this->getForm($loan);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Payroll::loan.edit', compact('loan', 'forms', 'groupedForms', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'loan_number' => 'required|string|max:255|unique:loans,loan_number',
            'loan_type' => 'required|in:pinjaman_karyawan,koperasi,bank,lainnya',
            'principal_amount' => 'required|numeric|min:0',
            'interest_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'installment_count' => 'required|integer|min:1',
            'installment_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            $loan = DB::transaction(function () use ($validated) {
                $validated['remaining_installments'] = $validated['installment_count'];
                $loan = Loan::create($validated);

                // Create installments
                $this->generateInstallments($loan);

                return $loan;
            });

            return $this->successResponse($request, 'Data pinjaman berhasil ditambahkan.', $loan);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan pinjaman.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data pinjaman gagal ditambahkan.');
        }
    }

    private function generateInstallments(Loan $loan): void
    {
        $installmentAmount = $loan->total_amount / $loan->installment_count;
        $currentDate = clone $loan->start_date;

        for ($i = 1; $i <= $loan->installment_count; $i++) {
            LoanInstallment::create([
                'loan_id' => $loan->id,
                'installment_number' => $i,
                'due_date' => $currentDate->addMonth(),
                'amount' => $installmentAmount,
                'status' => 'pending',
            ]);
        }
    }

    public function edit($id): View
    {
        $loan = $this->model->with('installments')->findOrFail($id);
        $employees = Employee::where('is_active', 1)->pluck('full_name', 'id');

        $result = $this->getForm($loan);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Payroll::loan.edit', compact('loan', 'forms', 'groupedForms', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $loan = $this->model->findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'loan_number' => 'required|string|max:255|unique:loans,loan_number,' . $id,
            'loan_type' => 'required|in:pinjaman_karyawan,koperasi,bank,lainnya',
            'principal_amount' => 'required|numeric|min:0',
            'interest_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'installment_count' => 'required|integer|min:1',
            'installment_amount' => 'required|numeric|min:0',
            'remaining_installments' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($loan, $validated) {
                $loan->update($validated);
            });

            return $this->successResponse($request, 'Data pinjaman berhasil diperbarui.', $loan);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui pinjaman.', ['exception' => $e, 'loan_id' => $id]);
            return $this->errorResponse($request, 'Data pinjaman gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $loan = $this->model->findOrFail($id);

            if ($loan->installments()->where('status', 'paid')->exists()) {
                return $this->errorResponse($request, 'Pinjaman tidak dapat dihapus karena sudah ada pembayaran.');
            }

            DB::transaction(function () use ($loan) {
                $loan->installments()->delete();
                $loan->delete();
            });

            return $this->successResponse($request, 'Data pinjaman berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus pinjaman.', ['exception' => $e, 'loan_id' => $id]);
            return $this->errorResponse($request, 'Data pinjaman gagal dihapus.');
        }
    }

    public function show($id): View
    {
        $loan = $this->model->with(['employee', 'installments'])->findOrFail($id);
        return view('Payroll::loan.show', compact('loan'));
    }

    public function getByEmployee($employeeId): JsonResponse
    {
        try {
            $loans = Loan::where('employee_id', $employeeId)
                ->where('is_active', 1)
                ->with('installments')
                ->get();

            return response()->json(['success' => true, 'data' => $loans]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil pinjaman karyawan.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}