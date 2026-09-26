<?php

namespace App\Modules\BankTransaction\Controllers;

use App\DataTables\BankTransactionDataTable;
use App\Http\Controllers\MainController;
use Models\BankTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Models\BankAccount;
use Models\ChartOfAccount;
use App\Services\JournalService;
use Throwable;

class BankTransactionController extends MainController
{
    protected JournalService $journalService;

    public function __construct(JournalService $journalService)
    {
        parent::__construct(new BankTransaction());
        $this->journalService = $journalService;
    }

    public function index(Request $request, BankTransactionDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('BankTransaction::index');
    }

    public function create(): View
    {
        $transaction = new BankTransaction();
        $bankAccounts = BankAccount::where('is_active', 1)->pluck('bank_name', 'id');

        return view('BankTransaction::create', compact('transaction', 'bankAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_chart_of_account_id' => 'required|exists:bank_accounts,id',
            'transaction_date' => 'required|date',
            'transaction_type' => 'required|in:debit,credit,transfer',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'balance_after' => 'required|numeric',
            'reference_number' => 'nullable|string|max:255',
            'is_reconciled' => 'boolean',
        ]);

        try {
            $transaction = DB::transaction(function () use ($validated) {
                $transaction = BankTransaction::create($validated);

                // Update bank account balance
                $bankAccount = $transaction->bankAccount;
                $bankAccount->current_balance = $validated['balance_after'];
                $bankAccount->save();

                // Create journal entry
                $this->createTransactionJournal($transaction);

                return $transaction;
            });

            return $this->successResponse($request, 'Data transaksi bank berhasil ditambahkan.', $transaction);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan transaksi bank.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data transaksi bank gagal ditambahkan.');
        }
    }

    private function createTransactionJournal(BankTransaction $transaction): void
    {
        $accountBank = $transaction->bankAccount->account;
        $journalItems = [];

        if ($transaction->transaction_type === 'debit') {
            // Debit: Bank ChartOfAccount
            $journalItems[] = [
                'chart_of_account_id' => $accountBank->id,
                'debit' => $transaction->amount,
                'credit' => 0,
                'description' => $transaction->description,
            ];

            // Credit: Pendapatan Lain atau Akun terkait
            $otherAccount = ChartOfAccount::where('code', '4-9000')->first(); // Pendapatan Lain-lain
            $journalItems[] = [
                'chart_of_account_id' => $otherAccount->id,
                'debit' => 0,
                'credit' => $transaction->amount,
                'description' => $transaction->description,
            ];
        } else {
            // Credit: Bank ChartOfAccount
            $journalItems[] = [
                'chart_of_account_id' => $accountBank->id,
                'debit' => 0,
                'credit' => $transaction->amount,
                'description' => $transaction->description,
            ];

            // Debit: Beban atau Akun terkait
            $otherAccount = ChartOfAccount::where('code', '5-9000')->first(); // Beban Lain-lain
            $journalItems[] = [
                'chart_of_account_id' => $otherAccount->id,
                'debit' => $transaction->amount,
                'credit' => 0,
                'description' => $transaction->description,
            ];
        }

        $journal = $this->journalService->createJournal([
            'journal_date' => $transaction->transaction_date,
            'description' => 'Transaksi Bank: ' . $transaction->description,
            'reference_number' => $transaction->reference_number,
            'items' => $journalItems,
            'notes' => 'Transaksi Bank #' . $transaction->id,
        ], $transaction->amount, $transaction->amount);

        $transaction->update(['journal_entry_id' => $journal->id]);
    }

    public function edit($id): View
    {
        $transaction = $this->model->findOrFail($id);
        $bankAccounts = BankAccount::where('is_active', 1)->pluck('bank_name', 'id');

        $result = $this->getForm($transaction);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('BankTransaction::edit', compact('transaction', 'forms', 'groupedForms', 'bankAccounts'));
    }

    public function update(Request $request, $id)
    {
        $transaction = $this->model->findOrFail($id);

        if ($transaction->is_reconciled) {
            return $this->errorResponse($request, 'Transaksi yang sudah direkonsiliasi tidak dapat diubah.');
        }

        $validated = $request->validate([
            'bank_chart_of_account_id' => 'required|exists:bank_accounts,id',
            'transaction_date' => 'required|date',
            'transaction_type' => 'required|in:debit,credit,transfer',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'balance_after' => 'required|numeric',
            'reference_number' => 'nullable|string|max:255',
            'is_reconciled' => 'boolean',
        ]);

        try {
            DB::transaction(function () use ($transaction, $validated) {
                // Reverse old journal
                if ($transaction->journal_entry_id) {
                    $this->journalService->voidJournal($transaction->journalEntry);
                }

                $transaction->update($validated);

                // Update bank account balance
                $bankAccount = $transaction->bankAccount;
                $bankAccount->current_balance = $validated['balance_after'];
                $bankAccount->save();

                // Create new journal
                $this->createTransactionJournal($transaction);
            });

            return $this->successResponse($request, 'Data transaksi bank berhasil diperbarui.', $transaction);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui transaksi bank.', ['exception' => $e, 'transaction_id' => $id]);
            return $this->errorResponse($request, 'Data transaksi bank gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $transaction = $this->model->findOrFail($id);

            if ($transaction->is_reconciled) {
                return $this->errorResponse($request, 'Transaksi yang sudah direkonsiliasi tidak dapat dihapus.');
            }

            DB::transaction(function () use ($transaction) {
                if ($transaction->journal_entry_id) {
                    $this->journalService->voidJournal($transaction->journalEntry);
                }
                $transaction->delete();
            });

            return $this->successResponse($request, 'Data transaksi bank berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus transaksi bank.', ['exception' => $e, 'transaction_id' => $id]);
            return $this->errorResponse($request, 'Data transaksi bank gagal dihapus.');
        }
    }

    public function reconcile(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $transaction = $this->model->findOrFail($id);

            DB::transaction(function () use ($transaction) {
                $transaction->update([
                    'is_reconciled' => true,
                    'reconciled_at' => now(),
                ]);
            });

            return $this->successResponse($request, 'Transaksi berhasil direkonsiliasi.', $transaction);
        } catch (Throwable $e) {
            Log::error('Gagal rekonsiliasi transaksi.', ['exception' => $e, 'transaction_id' => $id]);
            return $this->errorResponse($request, 'Gagal melakukan rekonsiliasi.');
        }
    }

    public function getByBankAccount($bankAccountId): JsonResponse
    {
        try {
            $transactions = BankTransaction::where('bank_chart_of_account_id', $bankAccountId)
                ->orderBy('transaction_date', 'desc')
                ->get();

            return response()->json(['success' => true, 'data' => $transactions]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil transaksi bank.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}
