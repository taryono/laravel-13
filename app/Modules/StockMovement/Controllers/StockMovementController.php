<?php

namespace App\Modules\StockMovement\Controllers;

use App\DataTables\StockMovementDataTable;
use App\Http\Controllers\MainController;
use Models\StockMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StockMovementController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StockMovement());
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
        StockMovementDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /stock-movement?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StockMovement::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $stockMovement = new StockMovement();
        $result = $this->getForm($stockMovement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StockMovement::create',
            compact('stockMovement', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'warehouse_id' => ['required', 'integer'],
            'item_id' => ['required', 'integer'],
            'movement_type' => ['required', 'string', 'max:255'],
            'reference_type' => ['required', 'string', 'max:255'],
            'reference_id' => ['required', 'integer'],
            'reference_number' => ['required', 'string', 'max:255'],
            'qty' => ['required', 'string', 'max:255'],
            'unit_cost' => ['required', 'string', 'max:255'],
            'total_cost' => ['required', 'string', 'max:255'],
            'balance_after' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'movement_date' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StockMovement::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StockMovement berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StockMovement.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StockMovement gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $stockMovement = $this->model->findOrFail($id);
        $result = $this->getForm($stockMovement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StockMovement::edit',
            compact('stockMovement', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'warehouse_id' => ['required', 'integer'],
            'item_id' => ['required', 'integer'],
            'movement_type' => ['required', 'string', 'max:255'],
            'reference_type' => ['required', 'string', 'max:255'],
            'reference_id' => ['required', 'integer'],
            'reference_number' => ['required', 'string', 'max:255'],
            'qty' => ['required', 'string', 'max:255'],
            'unit_cost' => ['required', 'string', 'max:255'],
            'total_cost' => ['required', 'string', 'max:255'],
            'balance_after' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'movement_date' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $stockMovement = $this->model->findOrFail($id);
            DB::transaction(function () use ($stockMovement, $validated) {
                $stockMovement->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StockMovement berhasil diperbarui.',
                $stockMovement
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StockMovement.',
                [
                    'exception' => $e,
                    'StockMovement_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StockMovement gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $stockMovement = $this->model->findOrFail($id);

            DB::transaction(function () use ($stockMovement) {
                $stockMovement->delete();
            });

            return $this->successResponse(
                $request,
                'Data StockMovement berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $stockMovement->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StockMovement.',
                [
                    'exception' => $e,
                    'StockMovement_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StockMovement gagal dihapus.'
            );
        }
    }
}
