<?php

namespace App\Modules\CanteenOrderItem\Controllers;

use App\DataTables\CanteenOrderItemDataTable;
use App\Http\Controllers\MainController;
use Models\CanteenOrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CanteenOrderItemController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CanteenOrderItem());
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
        CanteenOrderItemDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /canteen-order-item?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CanteenOrderItem::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $canteenOrderItem = new CanteenOrderItem();
        $result = $this->getForm($canteenOrderItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CanteenOrderItem::create',
            compact('canteenOrderItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'canteen_order_id' => ['required', 'integer'],
            'canteen_menu_id' => ['required', 'integer'],
            'qty' => ['required', 'string', 'max:255'],
            'unit_price' => ['required', 'string', 'max:255'],
            'total_price' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CanteenOrderItem::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CanteenOrderItem berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CanteenOrderItem.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenOrderItem gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $canteenOrderItem = $this->model->findOrFail($id);
        $result = $this->getForm($canteenOrderItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CanteenOrderItem::edit',
            compact('canteenOrderItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'canteen_order_id' => ['required', 'integer'],
            'canteen_menu_id' => ['required', 'integer'],
            'qty' => ['required', 'string', 'max:255'],
            'unit_price' => ['required', 'string', 'max:255'],
            'total_price' => ['required', 'string', 'max:255'],
        ]);

        try {
            $canteenOrderItem = $this->model->findOrFail($id);
            DB::transaction(function () use ($canteenOrderItem, $validated) {
                $canteenOrderItem->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CanteenOrderItem berhasil diperbarui.',
                $canteenOrderItem
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CanteenOrderItem.',
                [
                    'exception' => $e,
                    'CanteenOrderItem_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenOrderItem gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $canteenOrderItem = $this->model->findOrFail($id);

            DB::transaction(function () use ($canteenOrderItem) {
                $canteenOrderItem->delete();
            });

            return $this->successResponse(
                $request,
                'Data CanteenOrderItem berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $canteenOrderItem->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CanteenOrderItem.',
                [
                    'exception' => $e,
                    'CanteenOrderItem_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CanteenOrderItem gagal dihapus.'
            );
        }
    }
}
