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
use Models\ChartOfAccount;
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

        return $dataTable->render('Expense::index');
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create()
    {
        $expense = null;
        $categories = ExpenseCategory::active()->orderBy('sort_order')->get();
        $journalEntries = JournalEntry::orderBy('journal_number')->get();
        $approvers = User::where('is_active', true)->get();

        return view('expense.form', compact(
            'expense',
            'categories',
            'journalEntries',
            'approvers'
        ));
    }

    /**
     * Store a newly created expense.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_number' => 'required|string|max:255|unique:expenses',
            'expense_date' => 'required|date',
            'vendor_name' => 'nullable|string|max:255',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:cash,bank_transfer,check',
            'payment_status' => 'required|in:unpaid,paid,partial',
            'journal_entry_id' => 'nullable|exists:journal_entries,id',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'approved_by' => 'nullable|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            // Generate total jika tidak diisi
            $validated['total_amount'] = $validated['total_amount'] ??
                ($validated['amount'] + ($validated['tax'] ?? 0));

            // Upload attachment
            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment')->store('expenses', 'public');
                $validated['attachment_path'] = $path;
            }

            // Set created_by
            $validated['created_by'] = request()->user()?->id;

            // Set approved_at jika approved_by diisi
            if (!empty($validated['approved_by'])) {
                $validated['approved_at'] = now();
            }

            $expense = Expense::create($validated);

            DB::commit();

            return redirect()
                ->route('expense.index')
                ->with('success', 'Pengeluaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified expense.
     */
    public function edit(Expense $expense)
    {

        $categories =
            ExpenseCategory::active()->orderBy('sort_order')->get();
        $journalEntries = JournalEntry::orderBy('journal_number')->get();
        $approvers = User::where('is_active', true)->get();

        return view('expense.form', compact(
            'expense',
            'categories',
            'journalEntries',
            'approvers'
        ));
    }

    private function createExpenseJournal(Expense $expense): void
    {
        $accountExpense = ChartOfAccount::where('code', '5-1000')->first(); // Beban
        $accountCash = ChartOfAccount::where('code', '1-1000')->first(); // Kas

        $journalItems = [];

        // Debit: Beban
        $journalItems[] = [
            'chart_of_account_id' => $accountExpense->id,
            'debit' => $expense->total_amount,
            'credit' => 0,
            'description' => 'Beban ' . $expense->expense_category . ' - ' . $expense->description,
        ];

        // Credit: Kas
        $journalItems[] = [
            'chart_of_account_id' => $accountCash->id,
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
        return view('Expense::show', compact('expense'));
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
