<?php

namespace App\Modules\PurchaseRequestItem\Controllers;

use App\DataTables\PurchaseRequestItemDataTable;
use App\Http\Controllers\MainController;
use Models\PurchaseRequestItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PurchaseRequestItemController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PurchaseRequestItem());
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
        PurchaseRequestItemDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /purchase-request-item?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'PurchaseRequestItem::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $purchaseRequestItem = new PurchaseRequestItem();
        $result = $this->getForm($purchaseRequestItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PurchaseRequestItem::create',
            compact('purchaseRequestItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_request_id' => ['required', 'integer'],
            'rkas_item_id' => ['required', 'integer'],
            'account_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'specification' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'string', 'max:255'],
            'estimated_unit_price' => ['required', 'string', 'max:255'],
            'estimated_subtotal' => ['required', 'string', 'max:255'],
            'estimated_total' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return PurchaseRequestItem::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data PurchaseRequestItem berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan PurchaseRequestItem.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PurchaseRequestItem gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $purchaseRequestItem = $this->model->findOrFail($id);
        $result = $this->getForm($purchaseRequestItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'PurchaseRequestItem::edit',
            compact('purchaseRequestItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'purchase_request_id' => ['required', 'integer'],
            'rkas_item_id' => ['required', 'integer'],
            'account_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'specification' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'string', 'max:255'],
            'estimated_unit_price' => ['required', 'string', 'max:255'],
            'estimated_subtotal' => ['required', 'string', 'max:255'],
            'estimated_total' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $purchaseRequestItem = $this->model->findOrFail($id);
            DB::transaction(function () use ($purchaseRequestItem, $validated) {
                $purchaseRequestItem->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data PurchaseRequestItem berhasil diperbarui.',
                $purchaseRequestItem
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui PurchaseRequestItem.',
                [
                    'exception' => $e,
                    'PurchaseRequestItem_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PurchaseRequestItem gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $purchaseRequestItem = $this->model->findOrFail($id);

            DB::transaction(function () use ($purchaseRequestItem) {
                $purchaseRequestItem->delete();
            });

            return $this->successResponse(
                $request,
                'Data PurchaseRequestItem berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $purchaseRequestItem->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus PurchaseRequestItem.',
                [
                    'exception' => $e,
                    'PurchaseRequestItem_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data PurchaseRequestItem gagal dihapus.'
            );
        }
    }
}
