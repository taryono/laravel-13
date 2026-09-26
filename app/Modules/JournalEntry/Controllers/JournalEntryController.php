<?php

namespace App\Modules\JournalEntry\Controllers;

use App\DataTables\JournalEntryDataTable;
use App\Http\Controllers\MainController;
use Models\JournalEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Models\ChartOfAccount;
use App\Services\JournalService;
use Throwable;

class JournalEntryController extends MainController
{
    protected JournalService $journalService;

    public function __construct(JournalService $journalService)
    {
        parent::__construct(new JournalEntry());
        $this->journalService = $journalService;
    }

    /**
     * Display a listing of journal entries.
     */
    public function index(Request $request, JournalEntryDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('JournalEntry::index');
    }

    /**
     * Show form to create new journal entry.
     */
    public function create(): View
    {
        $journalEntry = new JournalEntry();
        $accounts = ChartOfAccount::where('is_active', 1)
            ->where('account_type', 'detail')
            ->orderBy('code')
            ->get();

        $result = $this->getForm($journalEntry);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'JournalEntry::edit',
            compact('journalEntry', 'forms', 'groupedForms', 'accounts')
        );
    }

    /**
     * Store a newly created journal entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'journal_date' => 'required|date',
            'description' => 'required|string',
            'reference_number' => 'nullable|string|max:255',
            'items' => 'required|array|min:2',
            'items.*.chart_of_account_id' => 'required|exists:accounts,id',
            'items.*.debit' => 'nullable|numeric|min:0',
            'items.*.credit' => 'nullable|numeric|min:0',
            'items.*.description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Validate debit = credit
        $totalDebit = array_sum(array_column($validated['items'], 'debit'));
        $totalCredit = array_sum(array_column($validated['items'], 'credit'));

        if ($totalDebit != $totalCredit) {
            return $this->errorResponse(
                $request,
                'Total Debit harus sama dengan Total Kredit.'
            );
        }

        try {
            $journalEntry = DB::transaction(function () use ($validated, $totalDebit, $totalCredit) {
                return $this->journalService->createJournal($validated, $totalDebit, $totalCredit);
            });

            return $this->successResponse(
                $request,
                'Jurnal berhasil dibuat.',
                $journalEntry
            );
        } catch (Throwable $e) {
            Log::error('Gagal membuat jurnal.', [
                'exception' => $e,
                'data' => $validated,
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Jurnal gagal dibuat: ' . $e->getMessage()
            );
        }
    }

    /**
     * Show journal entry.
     */
    public function show($id): View
    {
        $journalEntry = $this->model->with([
            'details.account',
            'details.account.category'
        ])->findOrFail($id);

        return view('JournalEntry::show', compact('journalEntry'));
    }

    /**
     * Edit journal entry.
     */
    public function edit($id): View
    {
        $journalEntry = $this->model->with('details')->findOrFail($id);

        if ($journalEntry->status === 'posted') {
            abort(403, 'Jurnal yang sudah diposting tidak dapat diubah.');
        }

        $accounts = ChartOfAccount::where('is_active', 1)
            ->where('account_type', 'detail')
            ->orderBy('code')
            ->get();

        $result = $this->getForm($journalEntry);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'JournalEntry::edit',
            compact('journalEntry', 'forms', 'groupedForms', 'accounts')
        );
    }

    /**
     * Update journal entry.
     */
    public function update(Request $request, $id)
    {
        $journalEntry = $this->model->with('details')->findOrFail($id);

        if ($journalEntry->status === 'posted') {
            return $this->errorResponse(
                $request,
                'Jurnal yang sudah diposting tidak dapat diubah.'
            );
        }

        $validated = $request->validate([
            'journal_date' => 'required|date',
            'description' => 'required|string',
            'reference_number' => 'nullable|string|max:255',
            'items' => 'required|array|min:2',
            'items.*.chart_of_account_id' => 'required|exists:accounts,id',
            'items.*.debit' => 'nullable|numeric|min:0',
            'items.*.credit' => 'nullable|numeric|min:0',
            'items.*.description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Validate debit = credit
        $totalDebit = array_sum(array_column($validated['items'], 'debit'));
        $totalCredit = array_sum(array_column($validated['items'], 'credit'));

        if ($totalDebit != $totalCredit) {
            return $this->errorResponse(
                $request,
                'Total Debit harus sama dengan Total Kredit.'
            );
        }

        try {
            DB::transaction(function () use ($journalEntry, $validated, $totalDebit, $totalCredit) {
                $this->journalService->updateJournal($journalEntry, $validated, $totalDebit, $totalCredit);
            });

            return $this->successResponse(
                $request,
                'Jurnal berhasil diperbarui.',
                $journalEntry->fresh()
            );
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui jurnal.', [
                'exception' => $e,
                'journal_id' => $id,
                'data' => $validated,
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Jurnal gagal diperbarui: ' . $e->getMessage()
            );
        }
    }

    /**
     * Post journal entry.
     */
    public function post(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $journalEntry = $this->model->findOrFail($id);

            if ($journalEntry->status === 'posted') {
                return $this->errorResponse(
                    $request,
                    'Jurnal sudah diposting sebelumnya.'
                );
            }

            DB::transaction(function () use ($journalEntry) {
                $this->journalService->postJournal($journalEntry);
            });

            return $this->successResponse(
                $request,
                'Jurnal berhasil diposting.',
                $journalEntry->fresh()
            );
        } catch (Throwable $e) {
            Log::error('Gagal memposting jurnal.', [
                'exception' => $e,
                'journal_id' => $id,
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Jurnal gagal diposting: ' . $e->getMessage()
            );
        }
    }

    /**
     * Void journal entry.
     */
    public function void(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $journalEntry = $this->model->findOrFail($id);

            if ($journalEntry->status !== 'posted') {
                return $this->errorResponse(
                    $request,
                    'Hanya jurnal yang sudah diposting yang dapat dibatalkan.'
                );
            }

            DB::transaction(function () use ($journalEntry) {
                $this->journalService->voidJournal($journalEntry);
            });

            return $this->successResponse(
                $request,
                'Jurnal berhasil dibatalkan.',
                $journalEntry->fresh()
            );
        } catch (Throwable $e) {
            Log::error('Gagal membatalkan jurnal.', [
                'exception' => $e,
                'journal_id' => $id,
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Jurnal gagal dibatalkan: ' . $e->getMessage()
            );
        }
    }

    /**
     * Delete journal entry.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $journalEntry = $this->model->findOrFail($id);

            if ($journalEntry->status === 'posted') {
                return $this->errorResponse(
                    $request,
                    'Jurnal yang sudah diposting tidak dapat dihapus. Void terlebih dahulu.'
                );
            }

            DB::transaction(function () use ($journalEntry) {
                $journalEntry->details()->delete();
                $journalEntry->delete();
            });

            return $this->successResponse(
                $request,
                'Jurnal berhasil dihapus.'
            );
        } catch (Throwable $e) {
            Log::error('Gagal menghapus jurnal.', [
                'exception' => $e,
                'journal_id' => $id,
                'user_id' => auth()->id(),
            ]);

            return $this->errorResponse(
                $request,
                'Jurnal gagal dihapus.'
            );
        }
    }
}
