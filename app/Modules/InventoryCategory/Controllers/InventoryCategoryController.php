<?php

namespace App\Modules\InventoryCategory\Controllers;

use App\DataTables\InventoryCategoryDataTable;
use App\Http\Controllers\MainController;
use Models\InventoryCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class InventoryCategoryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new InventoryCategory());
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
        InventoryCategoryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /inventory-category?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'InventoryCategory::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $inventoryCategory = new InventoryCategory();
        $result = $this->getForm($inventoryCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'InventoryCategory::create',
            compact('inventoryCategory', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'parent_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return InventoryCategory::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data InventoryCategory berhasil ditambahkan.',
                $model
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan InventoryCategory.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data InventoryCategory gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $inventoryCategory = $this->model->findOrFail($id);
        $result = $this->getForm($inventoryCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'InventoryCategory::edit',
            compact('inventoryCategory', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'parent_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $inventoryCategory = $this->model->findOrFail($id);
            DB::transaction(function () use ($inventoryCategory, $validated) {
                $inventoryCategory->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data InventoryCategory berhasil diperbarui.',
                $inventoryCategory
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui InventoryCategory.',
                [
                    'exception' => $e,
                    'InventoryCategory_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data InventoryCategory gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $inventoryCategory = $this->model->findOrFail($id);

            DB::transaction(function () use ($inventoryCategory) {
                $inventoryCategory->delete();
            });

            return $this->successResponse(
                $request,
                'Data InventoryCategory berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $inventoryCategory->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus InventoryCategory.',
                [
                    'exception' => $e,
                    'InventoryCategory_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data InventoryCategory gagal dihapus.'
            );
        }
    }
}
