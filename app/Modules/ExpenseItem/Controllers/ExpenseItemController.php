<?php

namespace App\Modules\ExpenseItem\Controllers;

use App\DataTables\ExpenseItemDataTable;
use App\Http\Controllers\MainController;
use Models\ExpenseItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ExpenseItemController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ExpenseItem());
    }

    /**
     * Display a listing of the resource.
     *
     * Handles both:
     *_Normal page request
     *_Yajra DataTables AJAX request
     */
    public function index(
        Request $request,
        ExpenseItemDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /expense-item?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ExpenseItem::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $expenseItem = new ExpenseItem();
        $result = $this->getForm($expenseItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExpenseItem::create',
            compact('expenseItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_id' => ['required', 'integer'],
            'description' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'string', 'max:255'],
            'unit_amount' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'account_id' => ['required', 'integer'],
            'cost_center_id' => ['required', 'integer'],
            'tax_amount' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ExpenseItem::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExpenseItem berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ExpenseItem.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExpenseItem gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $expenseItem = $this->model->findOrFail($id);
        $result = $this->getForm($expenseItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExpenseItem::edit',
            compact('expenseItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'expense_id' => ['required', 'integer'],
            'description' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'string', 'max:255'],
            'unit_amount' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'account_id' => ['required', 'integer'],
            'cost_center_id' => ['required', 'integer'],
            'tax_amount' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $expenseItem = $this->model->findOrFail($id);
            DB::transaction(function () use ($expenseItem, $validated) {
                $expenseItem->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExpenseItem berhasil diperbarui.',
                $expenseItem
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ExpenseItem.',
                [
                    'exception' => $e,
                    'ExpenseItem_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExpenseItem gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $expenseItem = $this->model->findOrFail($id);

            DB::transaction(function () use ($expenseItem) {
                $expenseItem->delete();
            });

            return $this->successResponse(
                $request,
                'Data ExpenseItem berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $expenseItem->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ExpenseItem.',
                [
                    'exception' => $e,
                    'ExpenseItem_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExpenseItem gagal dihapus.'
            );
        }
    }
}
