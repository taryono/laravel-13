<?php

namespace App\Modules\BankReconciliationItem\Controllers;

use App\DataTables\BankReconciliationItemDataTable;
use App\Http\Controllers\MainController;
use Models\BankReconciliationItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BankReconciliationItemController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BankReconciliationItem());
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
        BankReconciliationItemDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /bank-reconciliation-item?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'BankReconciliationItem::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bankReconciliationItem = new BankReconciliationItem();
        $result = $this->getForm($bankReconciliationItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BankReconciliationItem::create',
            compact('bankReconciliationItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'bank_reconciliation_id' => ['required', 'integer'],
            'bank_mutation_id' => ['required', 'integer'],
            'journal_entry_id' => ['required', 'integer'],
            'match_type' => ['required', 'string', 'max:255'],
            'match_score' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'difference_amount' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return BankReconciliationItem::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data BankReconciliationItem berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan BankReconciliationItem.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BankReconciliationItem gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $bankReconciliationItem = $this->model->findOrFail($id);
        $result = $this->getForm($bankReconciliationItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BankReconciliationItem::edit',
            compact('bankReconciliationItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'bank_reconciliation_id' => ['required', 'integer'],
            'bank_mutation_id' => ['required', 'integer'],
            'journal_entry_id' => ['required', 'integer'],
            'match_type' => ['required', 'string', 'max:255'],
            'match_score' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'difference_amount' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $bankReconciliationItem = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $bankReconciliationItem,
                $validated
            ) {
                $bankReconciliationItem->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data BankReconciliationItem berhasil diperbarui.',
                $bankReconciliationItem
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui BankReconciliationItem.',
                [
                    'exception' => $e,
                    'BankReconciliationItem_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BankReconciliationItem gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bankReconciliationItem = $this->model->findOrFail($id);

            DB::transaction(function () use ($bankReconciliationItem) {
                $bankReconciliationItem->delete();
            });

            return $this->successResponse(
                $request,
                'Data BankReconciliationItem berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $bankReconciliationItem->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus BankReconciliationItem.',
                [
                    'exception' => $e,
                    'BankReconciliationItem_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BankReconciliationItem gagal dihapus.'
            );
        }
    } 
}
