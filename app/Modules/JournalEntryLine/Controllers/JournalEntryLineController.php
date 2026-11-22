<?php

namespace App\Modules\JournalEntryLine\Controllers;

use App\DataTables\JournalEntryLineDataTable;
use App\Http\Controllers\MainController;
use Models\JournalEntryLine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class JournalEntryLineController extends MainController
{
    public function __construct()
    {
        parent::__construct(new JournalEntryLine());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     * - Normal page request
     * - Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        JournalEntryLineDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /journal-entry-line?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'JournalEntryLine::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $journalEntryLine = new JournalEntryLine();
        $result = $this->getForm($journalEntryLine);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'JournalEntryLine::create',
            compact('journalEntryLine', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'journal_entry_id' => ['required', 'integer'],
            'chart_of_account_id' => ['required', 'integer'],
            'line_number' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'debit' => ['required', 'string', 'max:255'],
            'credit' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'exchange_rate' => ['required', 'string', 'max:255'],
            'foreign_debit' => ['required', 'string', 'max:255'],
            'foreign_credit' => ['required', 'string', 'max:255'],
            'functional_debit' => ['required', 'string', 'max:255'],
            'functional_credit' => ['required', 'string', 'max:255'],
            'vendor_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return JournalEntryLine::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data JournalEntryLine berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan JournalEntryLine.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data JournalEntryLine gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $journalEntryLine = $this->model->findOrFail($id);
        $result = $this->getForm($journalEntryLine);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'JournalEntryLine::edit',
            compact('journalEntryLine', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'journal_entry_id' => ['required', 'integer'],
            'chart_of_account_id' => ['required', 'integer'],
            'line_number' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'debit' => ['required', 'string', 'max:255'],
            'credit' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'exchange_rate' => ['required', 'string', 'max:255'],
            'foreign_debit' => ['required', 'string', 'max:255'],
            'foreign_credit' => ['required', 'string', 'max:255'],
            'functional_debit' => ['required', 'string', 'max:255'],
            'functional_credit' => ['required', 'string', 'max:255'],
            'vendor_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $journalEntryLine = $this->model->findOrFail($id);
            DB::transaction(function () use ($journalEntryLine, $validated) {
                $journalEntryLine->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data JournalEntryLine berhasil diperbarui.',
                $journalEntryLine
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui JournalEntryLine.',
                [
                    'exception' => $e,
                    'JournalEntryLine_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data JournalEntryLine gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $journalEntryLine = $this->model->findOrFail($id);

            DB::transaction(function () use ($journalEntryLine) {
                $journalEntryLine->delete();
            });

            return $this->successResponse(
                $request,
                'Data JournalEntryLine berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $journalEntryLine->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus JournalEntryLine.',
                [
                    'exception' => $e,
                    'JournalEntryLine_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data JournalEntryLine gagal dihapus.'
            );
        }
    }
}
