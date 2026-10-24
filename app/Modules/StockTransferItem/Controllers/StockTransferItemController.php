<?php

namespace App\Modules\StockTransferItem\Controllers;

use App\DataTables\StockTransferItemDataTable;
use App\Http\Controllers\MainController;
use Models\StockTransferItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StockTransferItemController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StockTransferItem());
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
        StockTransferItemDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /stock-transfer-item?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StockTransferItem::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $stockTransferItem = new StockTransferItem();
        $result = $this->getForm($stockTransferItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StockTransferItem::create',
            compact('stockTransferItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'stock_transfer_id' => ['required', 'integer'],
            'item_id' => ['required', 'integer'],
            'qty_requested' => ['required', 'string', 'max:255'],
            'qty_sent' => ['required', 'string', 'max:255'],
            'qty_received' => ['required', 'string', 'max:255'],
            'unit_cost' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StockTransferItem::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StockTransferItem berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StockTransferItem.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StockTransferItem gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $stockTransferItem = $this->model->findOrFail($id);
        $result = $this->getForm($stockTransferItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StockTransferItem::edit',
            compact('stockTransferItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'stock_transfer_id' => ['required', 'integer'],
            'item_id' => ['required', 'integer'],
            'qty_requested' => ['required', 'string', 'max:255'],
            'qty_sent' => ['required', 'string', 'max:255'],
            'qty_received' => ['required', 'string', 'max:255'],
            'unit_cost' => ['required', 'string', 'max:255'],
        ]);

        try {
            $stockTransferItem = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $stockTransferItem,
                $validated
            ) {
                $stockTransferItem->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StockTransferItem berhasil diperbarui.',
                $stockTransferItem
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui StockTransferItem.',
                [
                    'exception' => $e,
                    'StockTransferItem_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StockTransferItem gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $stockTransferItem = $this->model->findOrFail($id);

            DB::transaction(function () use ($stockTransferItem) {
                $stockTransferItem->delete();
            });

            return $this->successResponse(
                $request,
                'Data StockTransferItem berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $stockTransferItem->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StockTransferItem.',
                [
                    'exception' => $e,
                    'StockTransferItem_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StockTransferItem gagal dihapus.'
            );
        }
    } 
}
