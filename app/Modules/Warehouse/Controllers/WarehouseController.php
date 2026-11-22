<?php

namespace App\Modules\Warehouse\Controllers;

use App\DataTables\WarehouseDataTable;
use App\Http\Controllers\MainController;
use Models\Warehouse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class WarehouseController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Warehouse());
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
        WarehouseDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /warehouse?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Warehouse::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $warehouse = new Warehouse();
        $result = $this->getForm($warehouse);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Warehouse::create',
            compact('warehouse', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'manager_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Warehouse::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Warehouse berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Warehouse.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Warehouse gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $warehouse = $this->model->findOrFail($id);
        $result = $this->getForm($warehouse);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Warehouse::edit',
            compact('warehouse', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'manager_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $warehouse = $this->model->findOrFail($id);
            DB::transaction(function () use ($warehouse, $validated) {
                $warehouse->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Warehouse berhasil diperbarui.',
                $warehouse
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Warehouse.',
                [
                    'exception' => $e,
                    'Warehouse_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Warehouse gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $warehouse = $this->model->findOrFail($id);

            DB::transaction(function () use ($warehouse) {
                $warehouse->delete();
            });

            return $this->successResponse(
                $request,
                'Data Warehouse berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $warehouse->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Warehouse.',
                [
                    'exception' => $e,
                    'Warehouse_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Warehouse gagal dihapus.'
            );
        }
    }
}
