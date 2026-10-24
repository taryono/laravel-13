<?php

namespace App\Modules\GoodsReceiptItem\Controllers;

use App\DataTables\GoodsReceiptItemDataTable;
use App\Http\Controllers\MainController;
use Models\GoodsReceiptItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class GoodsReceiptItemController extends MainController
{
    public function __construct()
    {
        parent::__construct(new GoodsReceiptItem());
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
        GoodsReceiptItemDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /goods-receipt-item?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'GoodsReceiptItem::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $goodsReceiptItem = new GoodsReceiptItem();
        $result = $this->getForm($goodsReceiptItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'GoodsReceiptItem::create',
            compact('goodsReceiptItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'goods_receipt_id' => ['required', 'integer'],
            'purchase_order_item_id' => ['required', 'integer'],
            'account_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:255'],
            'ordered_quantity' => ['required', 'string', 'max:255'],
            'received_quantity' => ['required', 'string', 'max:255'],
            'rejected_quantity' => ['required', 'string', 'max:255'],
            'unit_price' => ['required', 'string', 'max:255'],
            'subtotal' => ['required', 'string', 'max:255'],
            'condition' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_asset' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return GoodsReceiptItem::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data GoodsReceiptItem berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan GoodsReceiptItem.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GoodsReceiptItem gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $goodsReceiptItem = $this->model->findOrFail($id);
        $result = $this->getForm($goodsReceiptItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'GoodsReceiptItem::edit',
            compact('goodsReceiptItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'goods_receipt_id' => ['required', 'integer'],
            'purchase_order_item_id' => ['required', 'integer'],
            'account_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:255'],
            'ordered_quantity' => ['required', 'string', 'max:255'],
            'received_quantity' => ['required', 'string', 'max:255'],
            'rejected_quantity' => ['required', 'string', 'max:255'],
            'unit_price' => ['required', 'string', 'max:255'],
            'subtotal' => ['required', 'string', 'max:255'],
            'condition' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_asset' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $goodsReceiptItem = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $goodsReceiptItem,
                $validated
            ) {
                $goodsReceiptItem->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data GoodsReceiptItem berhasil diperbarui.',
                $goodsReceiptItem
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui GoodsReceiptItem.',
                [
                    'exception' => $e,
                    'GoodsReceiptItem_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GoodsReceiptItem gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $goodsReceiptItem = $this->model->findOrFail($id);

            DB::transaction(function () use ($goodsReceiptItem) {
                $goodsReceiptItem->delete();
            });

            return $this->successResponse(
                $request,
                'Data GoodsReceiptItem berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $goodsReceiptItem->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus GoodsReceiptItem.',
                [
                    'exception' => $e,
                    'GoodsReceiptItem_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GoodsReceiptItem gagal dihapus.'
            );
        }
    } 
}
