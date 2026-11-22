<?php

namespace App\Modules\AssetDepreciation\Controllers;

use App\DataTables\AssetDepreciationDataTable;
use App\Http\Controllers\MainController;
use Models\AssetDepreciation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AssetDepreciationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AssetDepreciation());
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
        AssetDepreciationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /asset-depreciation?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AssetDepreciation::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $assetDepreciation = new AssetDepreciation();
        $result = $this->getForm($assetDepreciation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssetDepreciation::create',
            compact('assetDepreciation', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organizationname' => ['required', 'string', 'max:255'],
            'accounting_periodperiod' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AssetDepreciation::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssetDepreciation berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AssetDepreciation.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssetDepreciation gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $assetDepreciation = $this->model->findOrFail($id);
        $result = $this->getForm($assetDepreciation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssetDepreciation::edit',
            compact('assetDepreciation', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organizationname' => ['required', 'string', 'max:255'],
            'accounting_periodperiod' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
        ]);

        try {
            $assetDepreciation = $this->model->findOrFail($id);
            DB::transaction(function () use ($assetDepreciation, $validated) {
                $assetDepreciation->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssetDepreciation berhasil diperbarui.',
                $assetDepreciation
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AssetDepreciation.',
                [
                    'exception' => $e,
                    'AssetDepreciation_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssetDepreciation gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $assetDepreciation = $this->model->findOrFail($id);

            DB::transaction(function () use ($assetDepreciation) {
                $assetDepreciation->delete();
            });

            return $this->successResponse(
                $request,
                'Data AssetDepreciation berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $assetDepreciation->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AssetDepreciation.',
                [
                    'exception' => $e,
                    'AssetDepreciation_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssetDepreciation gagal dihapus.'
            );
        }
    }
}
