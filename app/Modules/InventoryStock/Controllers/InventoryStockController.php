<?php

namespace App\Modules\InventoryStock\Controllers;

use App\DataTables\InventoryStockDataTable;
use App\Http\Controllers\MainController;
use Models\InventoryStock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class InventoryStockController extends MainController
{
    public function __construct()
    {
        parent::__construct(new InventoryStock());
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
        InventoryStockDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /inventory-stock?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'InventoryStock::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $inventoryStock = new InventoryStock();
        $result = $this->getForm($inventoryStock);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'InventoryStock::create',
            compact('inventoryStock', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => ['required', 'integer'],
            'item_id' => ['required', 'integer'],
            'qty_on_hand' => ['required', 'string', 'max:255'],
            'qty_reserved' => ['required', 'string', 'max:255'],
            'qty_available' => ['required', 'string', 'max:255'],
            'avg_cost' => ['required', 'string', 'max:255'],
            'last_cost' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return InventoryStock::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data InventoryStock berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan InventoryStock.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data InventoryStock gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $inventoryStock = $this->model->findOrFail($id);
        $result = $this->getForm($inventoryStock);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'InventoryStock::edit',
            compact('inventoryStock', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'warehouse_id' => ['required', 'integer'],
            'item_id' => ['required', 'integer'],
            'qty_on_hand' => ['required', 'string', 'max:255'],
            'qty_reserved' => ['required', 'string', 'max:255'],
            'qty_available' => ['required', 'string', 'max:255'],
            'avg_cost' => ['required', 'string', 'max:255'],
            'last_cost' => ['required', 'string', 'max:255'],
        ]);

        try {
            $inventoryStock = $this->model->findOrFail($id);
            DB::transaction(function () use ($inventoryStock, $validated) {
                $inventoryStock->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data InventoryStock berhasil diperbarui.',
                $inventoryStock
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui InventoryStock.',
                [
                    'exception' => $e,
                    'InventoryStock_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data InventoryStock gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $inventoryStock = $this->model->findOrFail($id);

            DB::transaction(function () use ($inventoryStock) {
                $inventoryStock->delete();
            });

            return $this->successResponse(
                $request,
                'Data InventoryStock berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $inventoryStock->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus InventoryStock.',
                [
                    'exception' => $e,
                    'InventoryStock_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data InventoryStock gagal dihapus.'
            );
        }
    }
}
