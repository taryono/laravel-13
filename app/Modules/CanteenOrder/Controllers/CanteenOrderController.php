<?php

namespace App\Modules\CanteenOrder\Controllers;

use App\DataTables\CanteenOrderDataTable;
use App\Http\Controllers\MainController;
use Models\CanteenOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CanteenOrderController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CanteenOrder());
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
        CanteenOrderDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /canteen-order?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CanteenOrder::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $canteenOrder = new CanteenOrder();
        $result = $this->getForm($canteenOrder);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CanteenOrder::create',
            compact('canteenOrder', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'canteen_tenant_id' => ['required', 'integer'],
            'digital_wallet_id' => ['required', 'integer'],
            'order_number' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'commission_amount' => ['required', 'string', 'max:255'],
            'net_tenant_amount' => ['required', 'string', 'max:255'],
            'payment_type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_settled' => ['required', 'string', 'max:255'],
            'settlement_id' => ['required', 'integer'],
            'order_time' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CanteenOrder::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CanteenOrder berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CanteenOrder.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenOrder gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $canteenOrder = $this->model->findOrFail($id);
        $result = $this->getForm($canteenOrder);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CanteenOrder::edit',
            compact('canteenOrder', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'canteen_tenant_id' => ['required', 'integer'],
            'digital_wallet_id' => ['required', 'integer'],
            'order_number' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'commission_amount' => ['required', 'string', 'max:255'],
            'net_tenant_amount' => ['required', 'string', 'max:255'],
            'payment_type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_settled' => ['required', 'string', 'max:255'],
            'settlement_id' => ['required', 'integer'],
            'order_time' => ['required', 'string', 'max:255'],
        ]);

        try {
            $canteenOrder = $this->model->findOrFail($id);
            DB::transaction(function () use ($canteenOrder, $validated) {
                $canteenOrder->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CanteenOrder berhasil diperbarui.',
                $canteenOrder
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CanteenOrder.',
                [
                    'exception' => $e,
                    'CanteenOrder_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenOrder gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $canteenOrder = $this->model->findOrFail($id);

            DB::transaction(function () use ($canteenOrder) {
                $canteenOrder->delete();
            });

            return $this->successResponse(
                $request,
                'Data CanteenOrder berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $canteenOrder->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CanteenOrder.',
                [
                    'exception' => $e,
                    'CanteenOrder_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenOrder gagal dihapus.'
            );
        }
    }
}
