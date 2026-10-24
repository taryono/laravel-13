<?php

namespace App\Modules\PurchaseOrderItem\Controllers;

use App\DataTables\PurchaseOrderItemDataTable;
use App\Http\Controllers\MainController;
use Models\PurchaseOrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PurchaseOrderItemController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PurchaseOrderItem());
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
        PurchaseOrderItemDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /purchase-order-item?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PurchaseOrderItem::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $purchaseOrderItem = new PurchaseOrderItem();
        $result = $this->getForm($purchaseOrderItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PurchaseOrderItem::create',
            compact('purchaseOrderItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'purchase_order_id' => ['required', 'integer'],
            'purchase_request_item_id' => ['required', 'integer'],
            'account_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'specification' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'string', 'max:255'],
            'unit_price' => ['required', 'string', 'max:255'],
            'discount_amount' => ['required', 'string', 'max:255'],
            'subtotal' => ['required', 'string', 'max:255'],
            'tax_amount' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'received_quantity' => ['required', 'string', 'max:255'],
            'line_number' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PurchaseOrderItem::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PurchaseOrderItem berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PurchaseOrderItem.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PurchaseOrderItem gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $purchaseOrderItem = $this->model->findOrFail($id);
        $result = $this->getForm($purchaseOrderItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PurchaseOrderItem::edit',
            compact('purchaseOrderItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'purchase_order_id' => ['required', 'integer'],
            'purchase_request_item_id' => ['required', 'integer'],
            'account_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'specification' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'string', 'max:255'],
            'unit_price' => ['required', 'string', 'max:255'],
            'discount_amount' => ['required', 'string', 'max:255'],
            'subtotal' => ['required', 'string', 'max:255'],
            'tax_amount' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'received_quantity' => ['required', 'string', 'max:255'],
            'line_number' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $purchaseOrderItem = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $purchaseOrderItem,
                $validated
            ) {
                $purchaseOrderItem->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PurchaseOrderItem berhasil diperbarui.',
                $purchaseOrderItem
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui PurchaseOrderItem.',
                [
                    'exception' => $e,
                    'PurchaseOrderItem_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PurchaseOrderItem gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $purchaseOrderItem = $this->model->findOrFail($id);

            DB::transaction(function () use ($purchaseOrderItem) {
                $purchaseOrderItem->delete();
            });

            return $this->successResponse(
                $request,
                'Data PurchaseOrderItem berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $purchaseOrderItem->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PurchaseOrderItem.',
                [
                    'exception' => $e,
                    'PurchaseOrderItem_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PurchaseOrderItem gagal dihapus.'
            );
        }
    } 
}
