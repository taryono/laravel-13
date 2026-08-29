<?php

namespace App\Modules\JournalDetail\Controllers;

use App\DataTables\JournalDetailDataTable;
use App\Http\Controllers\MainController;
use Models\JournalDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Models\JournalEntry;
use Models\Account; 
use Throwable; 

class JournalDetailController extends MainController
{
    public function __construct()
    {
        parent::__construct(new JournalDetail());
    }

    public function index(Request $request, JournalDetailDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Finance::journal_detail.index');
    }

    public function create($journalEntryId = null): View
    {
        $detail = new JournalDetail();
        $journalEntries = JournalEntry::where('status', 'draft')->pluck('journal_number', 'id');
        $accounts = Account::where('is_active', 1)
            ->where('account_type', 'detail')
            ->orderBy('code')
            ->pluck('name', 'id');

        $result = $this->getForm($detail);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::journal_detail.edit', compact('detail', 'forms', 'groupedForms', 'journalEntries', 'accounts', 'journalEntryId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'journal_entry_id' => 'required|exists:journal_entries,id',
            'account_id' => 'required|exists:accounts,id',
            'debit' => 'nullable|numeric|min:0',
            'credit' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        if ($validated['debit'] == 0 && $validated['credit'] == 0) {
            return $this->errorResponse($request, 'Debit atau Credit harus lebih dari 0.');
        }

        if ($validated['debit'] > 0 && $validated['credit'] > 0) {
            return $this->errorResponse($request, 'Debit dan Credit tidak boleh sama-sama lebih dari 0.');
        }

        try {
            $detail = DB::transaction(function () use ($validated) {
                $detail = JournalDetail::create($validated);

                // Update journal totals
                $journal = $detail->journalEntry;
                $journal->total_debit += $detail->debit;
                $journal->total_credit += $detail->credit;
                $journal->save();

                return $detail;
            });

            return $this->successResponse($request, 'Detail jurnal berhasil ditambahkan.', $detail);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan detail jurnal.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Detail jurnal gagal ditambahkan.');
        }
    }

    public function edit($id): View
    {
        $detail = $this->model->findOrFail($id);
        $journalEntries = JournalEntry::where('status', 'draft')->pluck('journal_number', 'id');
        $accounts = Account::where('is_active', 1)
            ->where('account_type', 'detail')
            ->orderBy('code')
            ->pluck('name', 'id');

        $result = $this->getForm($detail);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::journal_detail.edit', compact('detail', 'forms', 'groupedForms', 'journalEntries', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $detail = $this->model->findOrFail($id);

        if ($detail->journalEntry->status === 'posted') {
            return $this->errorResponse($request, 'Jurnal yang sudah diposting tidak dapat diubah.');
        }

        $validated = $request->validate([
            'journal_entry_id' => 'required|exists:journal_entries,id',
            'account_id' => 'required|exists:accounts,id',
            'debit' => 'nullable|numeric|min:0',
            'credit' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        if ($validated['debit'] == 0 && $validated['credit'] == 0) {
            return $this->errorResponse($request, 'Debit atau Credit harus lebih dari 0.');
        }

        try {
            DB::transaction(function () use ($detail, $validated) {
                // Reverse old values
                $journal = $detail->journalEntry;
                $journal->total_debit -= $detail->debit;
                $journal->total_credit -= $detail->credit;

                // Update detail
                $detail->update($validated);

                // Add new values
                $journal->total_debit += $detail->debit;
                $journal->total_credit += $detail->credit;
                $journal->save();
            });

            return $this->successResponse($request, 'Detail jurnal berhasil diperbarui.', $detail);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui detail jurnal.', ['exception' => $e, 'detail_id' => $id]);
            return $this->errorResponse($request, 'Detail jurnal gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $detail = $this->model->findOrFail($id);

            if ($detail->journalEntry->status === 'posted') {
                return $this->errorResponse($request, 'Jurnal yang sudah diposting tidak dapat diubah.');
            }

            DB::transaction(function () use ($detail) {
                $journal = $detail->journalEntry;
                $journal->total_debit -= $detail->debit;
                $journal->total_credit -= $detail->credit;
                $journal->save();

                $detail->delete();
            });

            return $this->successResponse($request, 'Detail jurnal berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus detail jurnal.', ['exception' => $e, 'detail_id' => $id]);
            return $this->errorResponse($request, 'Detail jurnal gagal dihapus.');
        }
    }

    public function getByJournal($journalId): JsonResponse
    {
        try {
            $details = JournalDetail::with('account')
                ->where('journal_entry_id', $journalId)
                ->get();

            return response()->json(['success' => true, 'data' => $details]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil detail jurnal.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}