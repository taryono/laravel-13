<?php

namespace App\Modules\StockAdjustment\Controllers;

use App\DataTables\StockAdjustmentDataTable;
use App\Http\Controllers\MainController;
use Models\StockAdjustment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StockAdjustmentController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StockAdjustment());
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
        StockAdjustmentDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /stock-adjustment?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StockAdjustment::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $stockAdjustment = new StockAdjustment();
        $result = $this->getForm($stockAdjustment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StockAdjustment::create',
            compact('stockAdjustment', 'forms', 'groupedForms')
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
            'adjustment_number' => ['required', 'string', 'max:255'],
            'adjustment_date' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'notes' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StockAdjustment::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StockAdjustment berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StockAdjustment.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StockAdjustment gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $stockAdjustment = $this->model->findOrFail($id);
        $result = $this->getForm($stockAdjustment);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StockAdjustment::edit',
            compact('stockAdjustment', 'forms', 'groupedForms')
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
            'adjustment_number' => ['required', 'string', 'max:255'],
            'adjustment_date' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'notes' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $stockAdjustment = $this->model->findOrFail($id);
            DB::transaction(function () use ($stockAdjustment, $validated) {
                $stockAdjustment->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StockAdjustment berhasil diperbarui.',
                $stockAdjustment
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StockAdjustment.',
                [
                    'exception' => $e,
                    'StockAdjustment_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StockAdjustment gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $stockAdjustment = $this->model->findOrFail($id);

            DB::transaction(function () use ($stockAdjustment) {
                $stockAdjustment->delete();
            });

            return $this->successResponse(
                $request,
                'Data StockAdjustment berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $stockAdjustment->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StockAdjustment.',
                [
                    'exception' => $e,
                    'StockAdjustment_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StockAdjustment gagal dihapus.'
            );
        }
    }
}
