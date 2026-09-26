<?php

namespace App\Modules\AssetDepreciationLog\Controllers;

use App\DataTables\AssetDepreciationLogDataTable;
use App\Http\Controllers\MainController;
use Models\AssetDepreciationLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AssetDepreciationLogController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AssetDepreciationLog());
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
        AssetDepreciationLogDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /asset-depreciation-log?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AssetDepreciationLog::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $assetDepreciationLog = new AssetDepreciationLog();
        $result = $this->getForm($assetDepreciationLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssetDepreciationLog::create',
            compact('assetDepreciationLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'asset_id' => ['required', 'integer'],
            'period_year' => ['required', 'string', 'max:255'],
            'period_month' => ['required', 'string', 'max:255'],
            'period_date' => ['required', 'string', 'max:255'],
            'opening_book_value' => ['required', 'string', 'max:255'],
            'depreciation_amount' => ['required', 'string', 'max:255'],
            'accumulated_depreciation' => ['required', 'string', 'max:255'],
            'closing_book_value' => ['required', 'string', 'max:255'],
            'depreciation_method' => ['required', 'string', 'max:255'],
            'purchase_price' => ['required', 'string', 'max:255'],
            'salvage_value' => ['required', 'string', 'max:255'],
            'useful_life_months' => ['required', 'string', 'max:255'],
            'is_posted' => ['required', 'string', 'max:255'],
            'posted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AssetDepreciationLog::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssetDepreciationLog berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AssetDepreciationLog.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssetDepreciationLog gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $assetDepreciationLog = $this->model->findOrFail($id);
        $result = $this->getForm($assetDepreciationLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssetDepreciationLog::edit',
            compact('assetDepreciationLog', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'asset_id' => ['required', 'integer'],
            'period_year' => ['required', 'string', 'max:255'],
            'period_month' => ['required', 'string', 'max:255'],
            'period_date' => ['required', 'string', 'max:255'],
            'opening_book_value' => ['required', 'string', 'max:255'],
            'depreciation_amount' => ['required', 'string', 'max:255'],
            'accumulated_depreciation' => ['required', 'string', 'max:255'],
            'closing_book_value' => ['required', 'string', 'max:255'],
            'depreciation_method' => ['required', 'string', 'max:255'],
            'purchase_price' => ['required', 'string', 'max:255'],
            'salvage_value' => ['required', 'string', 'max:255'],
            'useful_life_months' => ['required', 'string', 'max:255'],
            'is_posted' => ['required', 'string', 'max:255'],
            'posted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $assetDepreciationLog = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $assetDepreciationLog,
                $validated
            ) {
                $assetDepreciationLog->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssetDepreciationLog berhasil diperbarui.',
                $assetDepreciationLog
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui AssetDepreciationLog.',
                [
                    'exception' => $e,
                    'AssetDepreciationLog_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssetDepreciationLog gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $assetDepreciationLog = $this->model->findOrFail($id);

            DB::transaction(function () use ($assetDepreciationLog) {
                $assetDepreciationLog->delete();
            });

            return $this->successResponse(
                $request,
                'Data AssetDepreciationLog berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $assetDepreciationLog->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AssetDepreciationLog.',
                [
                    'exception' => $e,
                    'AssetDepreciationLog_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssetDepreciationLog gagal dihapus.'
            );
        }
    } 
}
