<?php

namespace App\Modules\LoanInstallment\Controllers;

use App\DataTables\LoanInstallmentDataTable;
use App\Http\Controllers\MainController;
use Models\LoanInstallment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
use Models\Loan;
use Models\PayrollPeriod;

class LoanInstallmentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new LoanInstallment());
    }

    public function index(Request $request, LoanInstallmentDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Payroll::loan_installment.index');
    }

    public function create(): View
    {
        $installment = new LoanInstallment();
        $loans = Loan::where('is_active', 1)->pluck('loan_number', 'id');
        $periods = PayrollPeriod::where('is_closed', 0)->pluck('code', 'id');

        $result = $this->getForm($installment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Payroll::loan_installment.edit', compact('installment', 'forms', 'groupedForms', 'loans', 'periods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'payroll_period_id' => 'nullable|exists:payroll_periods,id',
            'installment_number' => 'required|integer|min:1',
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0|lte:amount',
            'status' => 'required|in:pending,paid,overdue',
        ]);

        try {
            $installment = DB::transaction(function () use ($validated) {
                return LoanInstallment::create($validated);
            });

            return $this->successResponse($request, 'Data angsuran pinjaman berhasil ditambahkan.', $installment);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan angsuran.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data angsuran pinjaman gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $installment = $this->model->findOrFail($id);
        $loans = Loan::where('is_active', 1)->pluck('loan_number', 'id');
        $periods = PayrollPeriod::where('is_closed', 0)->pluck('code', 'id');

        $result = $this->getForm($installment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Payroll::loan_installment.edit', compact('installment', 'forms', 'groupedForms', 'loans', 'periods'));
    }

    public function update(Request $request, $id)
    {
        $installment = $this->model->findOrFail($id);

        if ($installment->status === 'paid') {
            return $this->errorResponse($request, 'Angsuran yang sudah lunas tidak dapat diubah.');
        }

        $validated = $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'payroll_period_id' => 'nullable|exists:payroll_periods,id',
            'installment_number' => 'required|integer|min:1',
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0|lte:amount',
            'status' => 'required|in:pending,paid,overdue',
        ]);

        try {
            DB::transaction(function () use ($installment, $validated) {
                $installment->update($validated);

                // Update remaining installments in loan
                if ($validated['status'] === 'paid') {
                    $loan = $installment->loan;
                    $loan->remaining_installments = max(0, $loan->remaining_installments_1);
                    $loan->save();
                }
            });

            return $this->successResponse($request, 'Data angsuran pinjaman berhasil diperbarui.', $installment);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui angsuran.', ['exception' => $e, 'installment_id' => $id]);
            return $this->errorResponse($request, 'Data angsuran pinjaman gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $installment = $this->model->findOrFail($id);

            if ($installment->status === 'paid') {
                return $this->errorResponse($request, 'Angsuran yang sudah lunas tidak dapat dihapus.');
            }

            DB::transaction(function () use ($installment) {
                $installment->delete();
            });

            return $this->successResponse($request, 'Data angsuran pinjaman berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus angsuran.', ['exception' => $e, 'installment_id' => $id]);
            return $this->errorResponse($request, 'Data angsuran pinjaman gagal dihapus.');
        }
    }

    public function pay(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $installment = $this->model->findOrFail($id);

            if ($installment->status === 'paid') {
                return $this->errorResponse($request, 'Angsuran sudah lunas.');
            }

            DB::transaction(function () use ($installment, $request) {
                $installment->update([
                    'status' => 'paid',
                    'paid_amount' => $installment->amount,
                    'paid_at' => now(),
                ]);

                // Update remaining installments
                $loan = $installment->loan;
                $loan->remaining_installments = max(0, $loan->remaining_installments_1);
                $loan->save();
            });

            return $this->successResponse($request, 'Angsuran berhasil dibayar.', $installment);
        } catch (Throwable $e) {
            Log::error('Gagal membayar angsuran.', ['exception' => $e, 'installment_id' => $id]);
            return $this->errorResponse($request, 'Gagal membayar angsuran.');
        }
    }

    public function bulkPay(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:loan_installments,id',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $installments = LoanInstallment::whereIn('id', $validated['ids'])
                    ->where('status', 'pending')
                    ->get();

                foreach ($installments as $installment) {
                    $installment->update([
                        'status' => 'paid',
                        'paid_amount' => $installment->amount,
                        'paid_at' => now(),
                    ]);

                    $loan = $installment->loan;
                    $loan->remaining_installments = max(0, $loan->remaining_installments_1);
                    $loan->save();
                }
            });

            return $this->successResponse($request, 'Angsuran berhasil dibayar secara massal.');
        } catch (Throwable $e) {
            Log::error('Gagal membayar angsuran massal.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Gagal membayar angsuran secara massal.');
        }
    }
}
