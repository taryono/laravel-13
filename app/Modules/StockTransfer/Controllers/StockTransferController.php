<?php

namespace App\Modules\StockTransfer\Controllers;

use App\DataTables\StockTransferDataTable;
use App\Http\Controllers\MainController;
use Models\StockTransfer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StockTransferController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StockTransfer());
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
        StockTransferDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /stock-transfer?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StockTransfer::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $stockTransfer = new StockTransfer();
        $result = $this->getForm($stockTransfer);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StockTransfer::create',
            compact('stockTransfer', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'transfer_number' => ['required', 'string', 'max:255'],
            'from_warehouse_id' => ['required', 'integer'],
            'to_warehouse_id' => ['required', 'integer'],
            'transfer_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'shipped_at' => ['required', 'string', 'max:255'],
            'received_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StockTransfer::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StockTransfer berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StockTransfer.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StockTransfer gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $stockTransfer = $this->model->findOrFail($id);
        $result = $this->getForm($stockTransfer);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StockTransfer::edit',
            compact('stockTransfer', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'transfer_number' => ['required', 'string', 'max:255'],
            'from_warehouse_id' => ['required', 'integer'],
            'to_warehouse_id' => ['required', 'integer'],
            'transfer_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'shipped_at' => ['required', 'string', 'max:255'],
            'received_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $stockTransfer = $this->model->findOrFail($id);
            DB::transaction(function () use ($stockTransfer, $validated) {
                $stockTransfer->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StockTransfer berhasil diperbarui.',
                $stockTransfer
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StockTransfer.',
                [
                    'exception' => $e,
                    'StockTransfer_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StockTransfer gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $stockTransfer = $this->model->findOrFail($id);

            DB::transaction(function () use ($stockTransfer) {
                $stockTransfer->delete();
            });

            return $this->successResponse(
                $request,
                'Data StockTransfer berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $stockTransfer->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StockTransfer.',
                [
                    'exception' => $e,
                    'StockTransfer_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StockTransfer gagal dihapus.'
            );
        }
    }
}
