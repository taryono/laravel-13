<?php

namespace App\Modules\Reconciliation\Controllers;

use App\DataTables\ReconciliationDataTable;
use App\Http\Controllers\MainController;
use Models\Reconciliation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
use Models\BankAccount;
use Models\BankTransaction; 

class ReconciliationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Reconciliation());
    }

    public function index(Request $request, ReconciliationDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Reconciliation::index');
    }

    public function create(): View
    {
        $reconciliation = new Reconciliation();
        $bankAccounts = BankAccount::where('is_active', 1)->pluck('bank_name', 'id');

        $result = $this->getForm($reconciliation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Reconciliation::edit', compact('reconciliation', 'forms', 'groupedForms', 'bankAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'reconciliation_date' => 'required|date',
            'statement_balance' => 'required|numeric|min:0',
            'book_balance' => 'required|numeric|min:0',
            'difference' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        try {
            $reconciliation = DB::transaction(function () use ($validated) {
                return Reconciliation::create($validated);
            });

            return $this->successResponse($request, 'Data rekonsiliasi berhasil ditambahkan.', $reconciliation);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan rekonsiliasi.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data rekonsiliasi gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $reconciliation = $this->model->findOrFail($id);
        $bankAccounts = BankAccount::where('is_active', 1)->pluck('bank_name', 'id');

        $result = $this->getForm($reconciliation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Reconciliation::edit', compact('reconciliation', 'forms', 'groupedForms', 'bankAccounts'));
    }

    public function update(Request $request, $id)
    {
        $reconciliation = $this->model->findOrFail($id);

        if ($reconciliation->status === 'completed') {
            return $this->errorResponse($request, 'Rekonsiliasi yang sudah selesai tidak dapat diubah.');
        }

        $validated = $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'reconciliation_date' => 'required|date',
            'statement_balance' => 'required|numeric|min:0',
            'book_balance' => 'required|numeric|min:0',
            'difference' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($reconciliation, $validated) {
                $reconciliation->update($validated);
            });

            return $this->successResponse($request, 'Data rekonsiliasi berhasil diperbarui.', $reconciliation);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui rekonsiliasi.', ['exception' => $e, 'reconciliation_id' => $id]);
            return $this->errorResponse($request, 'Data rekonsiliasi gagal diperbarui.');
        }
    }

    public function show($id): View
    {
        $reconciliation = $this->model->with(['bankAccount', 'details'])->findOrFail($id);
        return view('Reconciliation::show', compact('reconciliation'));
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $reconciliation = $this->model->findOrFail($id);

            if ($reconciliation->status === 'completed') {
                return $this->errorResponse($request, 'Rekonsiliasi yang sudah selesai tidak dapat dihapus.');
            }

            DB::transaction(function () use ($reconciliation) {
                $reconciliation->details()->delete();
                $reconciliation->delete();
            });

            return $this->successResponse($request, 'Data rekonsiliasi berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus rekonsiliasi.', ['exception' => $e, 'reconciliation_id' => $id]);
            return $this->errorResponse($request, 'Data rekonsiliasi gagal dihapus.');
        }
    }

    public function process(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $reconciliation = $this->model->findOrFail($id);

            DB::transaction(function () use ($reconciliation) {
                $reconciliation->update([
                    'status' => 'completed',
                    'completed_by' => auth()->id(),
                    'completed_at' => now(),
                ]);

                // Mark all matching transactions as reconciled
                $reconciliation->details()->where('type', 'match')
                    ->each(function ($detail) {
                        if ($detail->bank_transaction_id) {
                            BankTransaction::where('id', $detail->bank_transaction_id)
                                ->update([
                                    'is_reconciled' => true,
                                    'reconciled_at' => now(),
                                ]);
                        }
                    });
            });

            return $this->successResponse($request, 'Rekonsiliasi berhasil diproses.', $reconciliation);
        } catch (Throwable $e) {
            Log::error('Gagal memproses rekonsiliasi.', ['exception' => $e, 'reconciliation_id' => $id]);
            return $this->errorResponse($request, 'Gagal memproses rekonsiliasi.');
        }
    }
}
