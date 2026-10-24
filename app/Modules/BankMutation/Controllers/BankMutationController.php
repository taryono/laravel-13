<?php

namespace App\Modules\BankMutation\Controllers;

use App\DataTables\BankMutationDataTable;
use App\Http\Controllers\MainController;
use Models\BankMutation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BankMutationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BankMutation());
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
        BankMutationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /bank-mutation?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'BankMutation::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bankMutation = new BankMutation();
        $result = $this->getForm($bankMutation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BankMutation::create',
            compact('bankMutation', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'bank_account_id' => ['required', 'integer'],
            'import_batch_id' => ['required', 'integer'],
            'mutation_date' => ['required', 'string', 'max:255'],
            'value_date' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'reference_number' => ['required', 'string', 'max:255'],
            'debit' => ['required', 'string', 'max:255'],
            'credit' => ['required', 'string', 'max:255'],
            'balance' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'exchange_rate' => ['required', 'string', 'max:255'],
            'functional_debit' => ['required', 'string', 'max:255'],
            'functional_credit' => ['required', 'string', 'max:255'],
            'reconciliation_status' => ['required', 'string', 'max:255'],
            'matched_journal_entry_id' => ['required', 'integer'],
            'matched_at' => ['required', 'string', 'max:255'],
            'matched_by' => ['required', 'string', 'max:255'],
            'match_notes' => ['required', 'string', 'max:255'],
            'raw_data' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return BankMutation::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data BankMutation berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan BankMutation.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BankMutation gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $bankMutation = $this->model->findOrFail($id);
        $result = $this->getForm($bankMutation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BankMutation::edit',
            compact('bankMutation', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'bank_account_id' => ['required', 'integer'],
            'import_batch_id' => ['required', 'integer'],
            'mutation_date' => ['required', 'string', 'max:255'],
            'value_date' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'reference_number' => ['required', 'string', 'max:255'],
            'debit' => ['required', 'string', 'max:255'],
            'credit' => ['required', 'string', 'max:255'],
            'balance' => ['required', 'string', 'max:255'],
            'currency_id' => ['required', 'integer'],
            'exchange_rate' => ['required', 'string', 'max:255'],
            'functional_debit' => ['required', 'string', 'max:255'],
            'functional_credit' => ['required', 'string', 'max:255'],
            'reconciliation_status' => ['required', 'string', 'max:255'],
            'matched_journal_entry_id' => ['required', 'integer'],
            'matched_at' => ['required', 'string', 'max:255'],
            'matched_by' => ['required', 'string', 'max:255'],
            'match_notes' => ['required', 'string', 'max:255'],
            'raw_data' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $bankMutation = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $bankMutation,
                $validated
            ) {
                $bankMutation->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data BankMutation berhasil diperbarui.',
                $bankMutation
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui BankMutation.',
                [
                    'exception' => $e,
                    'BankMutation_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BankMutation gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bankMutation = $this->model->findOrFail($id);

            DB::transaction(function () use ($bankMutation) {
                $bankMutation->delete();
            });

            return $this->successResponse(
                $request,
                'Data BankMutation berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $bankMutation->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus BankMutation.',
                [
                    'exception' => $e,
                    'BankMutation_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BankMutation gagal dihapus.'
            );
        }
    } 
}
