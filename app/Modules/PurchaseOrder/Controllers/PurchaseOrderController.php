<?php

namespace App\Modules\PurchaseOrder\Controllers;

use App\DataTables\PurchaseOrderDataTable;
use App\Http\Controllers\MainController;
use Models\PurchaseOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PurchaseOrderController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PurchaseOrder());
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
        PurchaseOrderDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /purchase-order?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PurchaseOrder::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $purchaseOrder = new PurchaseOrder();
        $result = $this->getForm($purchaseOrder);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PurchaseOrder::create',
            compact('purchaseOrder', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'vendor_id' => ['required', 'integer'],
            'purchase_request_id' => ['required', 'integer'],
            'fiscal_period_id' => ['required', 'integer'],
            'currency_id' => ['required', 'integer'],
            'exchange_rate' => ['required', 'string', 'max:255'],
            'po_number' => ['required', 'string', 'max:255'],
            'po_date' => ['required', 'string', 'max:255'],
            'expected_delivery_date' => ['required', 'string', 'max:255'],
            'delivery_address' => ['required', 'string', 'max:255'],
            'payment_terms' => ['required', 'string', 'max:255'],
            'subtotal' => ['required', 'string', 'max:255'],
            'discount_amount' => ['required', 'string', 'max:255'],
            'tax_base' => ['required', 'string', 'max:255'],
            'ppn_rate' => ['required', 'string', 'max:255'],
            'ppn_amount' => ['required', 'string', 'max:255'],
            'shipping_amount' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'functional_total' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'sent_at' => ['required', 'string', 'max:255'],
            'completed_at' => ['required', 'string', 'max:255'],
            'cancelled_at' => ['required', 'string', 'max:255'],
            'cancellation_reason' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'sip_lah_order_id' => ['required', 'integer'],
            'sip_lah_order_url' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PurchaseOrder::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PurchaseOrder berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PurchaseOrder.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PurchaseOrder gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $purchaseOrder = $this->model->findOrFail($id);
        $result = $this->getForm($purchaseOrder);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PurchaseOrder::edit',
            compact('purchaseOrder', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'vendor_id' => ['required', 'integer'],
            'purchase_request_id' => ['required', 'integer'],
            'fiscal_period_id' => ['required', 'integer'],
            'currency_id' => ['required', 'integer'],
            'exchange_rate' => ['required', 'string', 'max:255'],
            'po_number' => ['required', 'string', 'max:255'],
            'po_date' => ['required', 'string', 'max:255'],
            'expected_delivery_date' => ['required', 'string', 'max:255'],
            'delivery_address' => ['required', 'string', 'max:255'],
            'payment_terms' => ['required', 'string', 'max:255'],
            'subtotal' => ['required', 'string', 'max:255'],
            'discount_amount' => ['required', 'string', 'max:255'],
            'tax_base' => ['required', 'string', 'max:255'],
            'ppn_rate' => ['required', 'string', 'max:255'],
            'ppn_amount' => ['required', 'string', 'max:255'],
            'shipping_amount' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'functional_total' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'sent_at' => ['required', 'string', 'max:255'],
            'completed_at' => ['required', 'string', 'max:255'],
            'cancelled_at' => ['required', 'string', 'max:255'],
            'cancellation_reason' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'sip_lah_order_id' => ['required', 'integer'],
            'sip_lah_order_url' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $purchaseOrder = $this->model->findOrFail($id);
            DB::transaction(function () use ($purchaseOrder, $validated) {
                $purchaseOrder->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PurchaseOrder berhasil diperbarui.',
                $purchaseOrder
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui PurchaseOrder.',
                [
                    'exception' => $e,
                    'PurchaseOrder_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PurchaseOrder gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $purchaseOrder = $this->model->findOrFail($id);

            DB::transaction(function () use ($purchaseOrder) {
                $purchaseOrder->delete();
            });

            return $this->successResponse(
                $request,
                'Data PurchaseOrder berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $purchaseOrder->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PurchaseOrder.',
                [
                    'exception' => $e,
                    'PurchaseOrder_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PurchaseOrder gagal dihapus.'
            );
        }
    }
}
