<?php

namespace App\Modules\GoodsReceipt\Controllers;

use App\DataTables\GoodsReceiptDataTable;
use App\Http\Controllers\MainController;
use Models\GoodsReceipt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class GoodsReceiptController extends MainController
{
    public function __construct()
    {
        parent::__construct(new GoodsReceipt());
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
        GoodsReceiptDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /goods-receipt?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'GoodsReceipt::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $goodsReceipt = new GoodsReceipt();
        $result = $this->getForm($goodsReceipt);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'GoodsReceipt::create',
            compact('goodsReceipt', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'purchase_order_id' => ['required', 'integer'],
            'vendor_id' => ['required', 'integer'],
            'receipt_number' => ['required', 'string', 'max:255'],
            'receipt_date' => ['required', 'string', 'max:255'],
            'received_by' => ['required', 'string', 'max:255'],
            'checked_by' => ['required', 'string', 'max:255'],
            'delivery_note_number' => ['required', 'string', 'max:255'],
            'vehicle_number' => ['required', 'string', 'max:255'],
            'condition' => ['required', 'string', 'max:255'],
            'condition_notes' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return GoodsReceipt::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data GoodsReceipt berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan GoodsReceipt.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GoodsReceipt gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $goodsReceipt = $this->model->findOrFail($id);
        $result = $this->getForm($goodsReceipt);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'GoodsReceipt::edit',
            compact('goodsReceipt', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'purchase_order_id' => ['required', 'integer'],
            'vendor_id' => ['required', 'integer'],
            'receipt_number' => ['required', 'string', 'max:255'],
            'receipt_date' => ['required', 'string', 'max:255'],
            'received_by' => ['required', 'string', 'max:255'],
            'checked_by' => ['required', 'string', 'max:255'],
            'delivery_note_number' => ['required', 'string', 'max:255'],
            'vehicle_number' => ['required', 'string', 'max:255'],
            'condition' => ['required', 'string', 'max:255'],
            'condition_notes' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $goodsReceipt = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $goodsReceipt,
                $validated
            ) {
                $goodsReceipt->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data GoodsReceipt berhasil diperbarui.',
                $goodsReceipt
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui GoodsReceipt.',
                [
                    'exception' => $e,
                    'GoodsReceipt_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GoodsReceipt gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $goodsReceipt = $this->model->findOrFail($id);

            DB::transaction(function () use ($goodsReceipt) {
                $goodsReceipt->delete();
            });

            return $this->successResponse(
                $request,
                'Data GoodsReceipt berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $goodsReceipt->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus GoodsReceipt.',
                [
                    'exception' => $e,
                    'GoodsReceipt_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data GoodsReceipt gagal dihapus.'
            );
        }
    } 
}
