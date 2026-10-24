<?php

namespace App\Modules\ExpensePayment\Controllers;

use App\DataTables\ExpensePaymentDataTable;
use App\Http\Controllers\MainController;
use Models\ExpensePayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ExpensePaymentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ExpensePayment());
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
        ExpensePaymentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /expense-payment?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ExpensePayment::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $expensePayment = new ExpensePayment();
        $result = $this->getForm($expensePayment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExpensePayment::create',
            compact('expensePayment', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'expense_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'payment_number' => ['required', 'string', 'max:255'],
            'payment_date' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:255'],
            'bank_account_id' => ['required', 'integer'],
            'reference_number' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ExpensePayment::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExpensePayment berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ExpensePayment.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExpensePayment gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $expensePayment = $this->model->findOrFail($id);
        $result = $this->getForm($expensePayment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExpensePayment::edit',
            compact('expensePayment', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'expense_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'payment_number' => ['required', 'string', 'max:255'],
            'payment_date' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:255'],
            'bank_account_id' => ['required', 'integer'],
            'reference_number' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $expensePayment = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $expensePayment,
                $validated
            ) {
                $expensePayment->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExpensePayment berhasil diperbarui.',
                $expensePayment
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ExpensePayment.',
                [
                    'exception' => $e,
                    'ExpensePayment_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExpensePayment gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $expensePayment = $this->model->findOrFail($id);

            DB::transaction(function () use ($expensePayment) {
                $expensePayment->delete();
            });

            return $this->successResponse(
                $request,
                'Data ExpensePayment berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $expensePayment->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ExpensePayment.',
                [
                    'exception' => $e,
                    'ExpensePayment_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExpensePayment gagal dihapus.'
            );
        }
    } 
}
