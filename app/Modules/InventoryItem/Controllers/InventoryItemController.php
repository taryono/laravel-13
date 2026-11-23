<?php

namespace App\Modules\InventoryItem\Controllers;

use App\DataTables\InventoryItemDataTable;
use App\Http\Controllers\MainController;
use Models\InventoryItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class InventoryItemController extends MainController
{
    public function __construct()
    {
        parent::__construct(new InventoryItem());
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
        InventoryItemDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /inventory-item?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'InventoryItem::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $inventoryItem = new InventoryItem();
        $result = $this->getForm($inventoryItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'InventoryItem::create',
            compact('inventoryItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'category_id' => ['required', 'integer'],
            'uom_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'barcode' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'item_type' => ['required', 'string', 'max:255'],
            'min_stock' => ['required', 'string', 'max:255'],
            'max_stock' => ['required', 'string', 'max:255'],
            'standard_cost' => ['required', 'string', 'max:255'],
            'selling_price' => ['required', 'string', 'max:255'],
            'valuation_method' => ['required', 'string', 'max:255'],
            'inventory_account_id' => ['required', 'integer'],
            'cogs_account_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return InventoryItem::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data InventoryItem berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan InventoryItem.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data InventoryItem gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $inventoryItem = $this->model->findOrFail($id);
        $result = $this->getForm($inventoryItem);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'InventoryItem::edit',
            compact('inventoryItem', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'category_id' => ['required', 'integer'],
            'uom_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'barcode' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'item_type' => ['required', 'string', 'max:255'],
            'min_stock' => ['required', 'string', 'max:255'],
            'max_stock' => ['required', 'string', 'max:255'],
            'standard_cost' => ['required', 'string', 'max:255'],
            'selling_price' => ['required', 'string', 'max:255'],
            'valuation_method' => ['required', 'string', 'max:255'],
            'inventory_account_id' => ['required', 'integer'],
            'cogs_account_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $inventoryItem = $this->model->findOrFail($id);
            DB::transaction(function () use ($inventoryItem, $validated) {
                $inventoryItem->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data InventoryItem berhasil diperbarui.',
                $inventoryItem
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui InventoryItem.',
                [
                    'exception' => $e,
                    'InventoryItem_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data InventoryItem gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $inventoryItem = $this->model->findOrFail($id);

            DB::transaction(function () use ($inventoryItem) {
                $inventoryItem->delete();
            });

            return $this->successResponse(
                $request,
                'Data InventoryItem berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $inventoryItem->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus InventoryItem.',
                [
                    'exception' => $e,
                    'InventoryItem_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data InventoryItem gagal dihapus.'
            );
        }
    }
}
