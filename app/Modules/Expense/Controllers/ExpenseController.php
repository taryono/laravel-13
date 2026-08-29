<?php

namespace App\Modules\Expense\Controllers;

use App\DataTables\ExpenseDataTable;
use App\Http\Controllers\MainController;
use Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Models\Account;
use App\Services\JournalService; 
use Throwable;  

class ExpenseController extends MainController
{
    protected JournalService $journalService;

    public function __construct(JournalService $journalService)
    {
        parent::__construct(new Expense());
        $this->journalService = $journalService;
    }

    public function index(Request $request, ExpenseDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Finance::expense.index');
    }

    public function create(): View
    {
        $expense = new Expense();
        $accounts = Account::where('is_active', 1)
            ->where('account_type', 'detail')
            ->orderBy('code')
            ->pluck('name', 'id');

        $result = $this->getForm($expense);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::expense.edit', compact('expense', 'forms', 'groupedForms', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_number' => 'required|string|max:255|unique:expenses,expense_number',
            'expense_date' => 'required|date',
            'vendor_name' => 'nullable|string|max:255',
            'expense_category' => 'required|string|max:100',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:cash,bank_transfer,check',
            'payment_status' => 'required|in:unpaid,paid,partial',
            'attachment_path' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
        ]);

        try {
            $expense = DB::transaction(function () use ($validated) {
                $expense = Expense::create($validated);

                // Create journal entry if paid
                if ($validated['payment_status'] === 'paid') {
                    $this->createExpenseJournal($expense);
                }

                return $expense;
            });

            return $this->successResponse($request, 'Data pengeluaran berhasil ditambahkan.', $expense);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan pengeluaran.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data pengeluaran gagal ditambahkan.');
        }
    }

    private function createExpenseJournal(Expense $expense): void
    {
        $accountExpense = Account::where('code', '5-1000')->first(); // Beban
        $accountCash = Account::where('code', '1-1000')->first(); // Kas

        $journalItems = [];

        // Debit: Beban
        $journalItems[] = [
            'account_id' => $accountExpense->id,
            'debit' => $expense->total_amount,
            'credit' => 0,
            'description' => 'Beban ' . $expense->expense_category . ' - ' . $expense->description,
        ];

        // Credit: Kas
        $journalItems[] = [
            'account_id' => $accountCash->id,
            'debit' => 0,
            'credit' => $expense->total_amount,
            'description' => 'Pembayaran pengeluaran ' . $expense->expense_number,
        ];

        $journal = $this->journalService->createJournal([
            'journal_date' => $expense->expense_date,
            'description' => 'Pengeluaran ' . $expense->expense_number . ' - ' . $expense->expense_category,
            'reference_number' => $expense->expense_number,
            'items' => $journalItems,
            'notes' => 'Pengeluaran #' . $expense->expense_number,
        ], $expense->total_amount, $expense->total_amount);

        $expense->update(['journal_entry_id' => $journal->id]);
    }

    public function edit($id): View
    {
        $expense = $this->model->findOrFail($id);
        $accounts = Account::where('is_active', 1)
            ->where('account_type', 'detail')
            ->orderBy('code')
            ->pluck('name', 'id');

        $result = $this->getForm($expense);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::expense.edit', compact('expense', 'forms', 'groupedForms', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $expense = $this->model->findOrFail($id);

        $validated = $request->validate([
            'expense_number' => 'required|string|max:255|unique:expenses,expense_number,' . $id,
            'expense_date' => 'required|date',
            'vendor_name' => 'nullable|string|max:255',
            'expense_category' => 'required|string|max:100',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:cash,bank_transfer,check',
            'payment_status' => 'required|in:unpaid,paid,partial',
            'attachment_path' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($expense, $validated) {
                $expense->update($validated);

                // Update journal entry if paid status changed
                if ($validated['payment_status'] === 'paid' && !$expense->journal_entry_id) {
                    $this->createExpenseJournal($expense);
                }
            });

            return $this->successResponse($request, 'Data pengeluaran berhasil diperbarui.', $expense);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui pengeluaran.', ['exception' => $e, 'expense_id' => $id]);
            return $this->errorResponse($request, 'Data pengeluaran gagal diperbarui.');
        }
    }

    public function show($id): View
    {
        $expense = $this->model->with('journalEntry.details.account')->findOrFail($id);
        return view('Finance::expense.show', compact('expense'));
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $expense = $this->model->findOrFail($id);

            DB::transaction(function () use ($expense) {
                if ($expense->journal_entry_id) {
                    $this->journalService->voidJournal($expense->journalEntry);
                }
                $expense->delete();
            });

            return $this->successResponse($request, 'Data pengeluaran berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus pengeluaran.', ['exception' => $e, 'expense_id' => $id]);
            return $this->errorResponse($request, 'Data pengeluaran gagal dihapus.');
        }
    }

    public function markAsPaid(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $expense = $this->model->findOrFail($id);

            DB::transaction(function () use ($expense) {
                $expense->update([
                    'payment_status' => 'paid',
                ]);

                if (!$expense->journal_entry_id) {
                    $this->createExpenseJournal($expense);
                }
            });

            return $this->successResponse($request, 'Pengeluaran berhasil ditandai lunas.', $expense);
        } catch (Throwable $e) {
            Log::error('Gagal menandai pengeluaran lunas.', ['exception' => $e, 'expense_id' => $id]);
            return $this->errorResponse($request, 'Gagal menandai pengeluaran lunas.');
        }
    }

    public function getByCategory($category): JsonResponse
    {
        try {
            $expenses = Expense::where('expense_category', $category)
                ->where('payment_status', '!=', 'paid')
                ->get();

            return response()->json(['success' => true, 'data' => $expenses]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil pengeluaran per kategori.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}
