<?php

namespace App\Modules\StockAdjustmentItem\Controllers;

use App\DataTables\StockAdjustmentItemDataTable;
use App\Http\Controllers\MainController;
use Models\StockAdjustmentItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StockAdjustmentItemController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StockAdjustmentItem());
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
        StockAdjustmentItemDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /stock-adjustment-item?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StockAdjustmentItem::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $stockAdjustmentItem = new StockAdjustmentItem();
        $result = $this->getForm($stockAdjustmentItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StockAdjustmentItem::create',
            compact('stockAdjustmentItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'stock_adjustment_id' => ['required', 'integer'],
            'item_id' => ['required', 'integer'],
            'system_qty' => ['required', 'string', 'max:255'],
            'actual_qty' => ['required', 'string', 'max:255'],
            'difference_qty' => ['required', 'string', 'max:255'],
            'unit_cost' => ['required', 'string', 'max:255'],
            'total_cost_diff' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StockAdjustmentItem::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StockAdjustmentItem berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StockAdjustmentItem.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StockAdjustmentItem gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $stockAdjustmentItem = $this->model->findOrFail($id);
        $result = $this->getForm($stockAdjustmentItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StockAdjustmentItem::edit',
            compact('stockAdjustmentItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'stock_adjustment_id' => ['required', 'integer'],
            'item_id' => ['required', 'integer'],
            'system_qty' => ['required', 'string', 'max:255'],
            'actual_qty' => ['required', 'string', 'max:255'],
            'difference_qty' => ['required', 'string', 'max:255'],
            'unit_cost' => ['required', 'string', 'max:255'],
            'total_cost_diff' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
        ]);

        try {
            $stockAdjustmentItem = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $stockAdjustmentItem,
                $validated
            ) {
                $stockAdjustmentItem->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StockAdjustmentItem berhasil diperbarui.',
                $stockAdjustmentItem
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui StockAdjustmentItem.',
                [
                    'exception' => $e,
                    'StockAdjustmentItem_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StockAdjustmentItem gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $stockAdjustmentItem = $this->model->findOrFail($id);

            DB::transaction(function () use ($stockAdjustmentItem) {
                $stockAdjustmentItem->delete();
            });

            return $this->successResponse(
                $request,
                'Data StockAdjustmentItem berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $stockAdjustmentItem->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StockAdjustmentItem.',
                [
                    'exception' => $e,
                    'StockAdjustmentItem_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StockAdjustmentItem gagal dihapus.'
            );
        }
    } 
}
