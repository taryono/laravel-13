<?php

namespace App\Modules\AssetMaintenance\Controllers;

use App\DataTables\AssetMaintenanceDataTable;
use App\Http\Controllers\MainController;
use Models\AssetMaintenance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AssetMaintenanceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AssetMaintenance());
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
        AssetMaintenanceDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /asset-maintenance?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AssetMaintenance::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $assetMaintenance = new AssetMaintenance();
        $result = $this->getForm($assetMaintenance);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssetMaintenance::create',
            compact('assetMaintenance', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'fixed_asset_id' => ['required', 'integer'],
            'vendor_id' => ['required', 'integer'],
            'maintenance_type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'scheduled_date' => ['required', 'string', 'max:255'],
            'completion_date' => ['required', 'string', 'max:255'],
            'cost' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'technician_name' => ['required', 'string', 'max:255'],
            'invoice_number' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AssetMaintenance::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssetMaintenance berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AssetMaintenance.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssetMaintenance gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $assetMaintenance = $this->model->findOrFail($id);
        $result = $this->getForm($assetMaintenance);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssetMaintenance::edit',
            compact('assetMaintenance', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'fixed_asset_id' => ['required', 'integer'],
            'vendor_id' => ['required', 'integer'],
            'maintenance_type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'scheduled_date' => ['required', 'string', 'max:255'],
            'completion_date' => ['required', 'string', 'max:255'],
            'cost' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'technician_name' => ['required', 'string', 'max:255'],
            'invoice_number' => ['required', 'string', 'max:255'],
            'journal_entry_id' => ['required', 'integer'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $assetMaintenance = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $assetMaintenance,
                $validated
            ) {
                $assetMaintenance->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssetMaintenance berhasil diperbarui.',
                $assetMaintenance
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui AssetMaintenance.',
                [
                    'exception' => $e,
                    'AssetMaintenance_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssetMaintenance gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $assetMaintenance = $this->model->findOrFail($id);

            DB::transaction(function () use ($assetMaintenance) {
                $assetMaintenance->delete();
            });

            return $this->successResponse(
                $request,
                'Data AssetMaintenance berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $assetMaintenance->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AssetMaintenance.',
                [
                    'exception' => $e,
                    'AssetMaintenance_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssetMaintenance gagal dihapus.'
            );
        }
    } 
}
