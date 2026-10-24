<?php

namespace App\Modules\FixedAsset\Controllers;

use App\DataTables\FixedAssetDataTable;
use App\Http\Controllers\MainController;
use Models\FixedAsset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class FixedAssetController extends MainController
{
    public function __construct()
    {
        parent::__construct(new FixedAsset());
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
        FixedAssetDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /fixed-asset?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'FixedAsset::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $fixedAsset = new FixedAsset();
        $result = $this->getForm($fixedAsset);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FixedAsset::create',
            compact('fixedAsset', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'asset_category_id' => ['required', 'integer'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'asset_code' => ['required', 'string', 'max:255'],
            'serial_number' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'specification' => ['required', 'string', 'max:255'],
            'acquisition_date' => ['required', 'string', 'max:255'],
            'acquisition_cost' => ['required', 'string', 'max:255'],
            'residual_value' => ['required', 'string', 'max:255'],
            'useful_life_months' => ['required', 'string', 'max:255'],
            'useful_life_years' => ['required', 'string', 'max:255'],
            'depreciation_method' => ['required', 'string', 'max:255'],
            'accumulated_depreciation' => ['required', 'string', 'max:255'],
            'book_value' => ['required', 'string', 'max:255'],
            'asset_account_id' => ['required', 'integer'],
            'accumulated_depreciation_account_id' => ['required', 'integer'],
            'depreciation_expense_account_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'condition' => ['required', 'string', 'max:255'],
            'disposed_at' => ['required', 'string', 'max:255'],
            'disposal_value' => ['required', 'string', 'max:255'],
            'disposal_reason' => ['required', 'string', 'max:255'],
            'photo_url' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return FixedAsset::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data FixedAsset berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan FixedAsset.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FixedAsset gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $fixedAsset = $this->model->findOrFail($id);
        $result = $this->getForm($fixedAsset);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FixedAsset::edit',
            compact('fixedAsset', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'asset_category_id' => ['required', 'integer'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'asset_code' => ['required', 'string', 'max:255'],
            'serial_number' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'specification' => ['required', 'string', 'max:255'],
            'acquisition_date' => ['required', 'string', 'max:255'],
            'acquisition_cost' => ['required', 'string', 'max:255'],
            'residual_value' => ['required', 'string', 'max:255'],
            'useful_life_months' => ['required', 'string', 'max:255'],
            'useful_life_years' => ['required', 'string', 'max:255'],
            'depreciation_method' => ['required', 'string', 'max:255'],
            'accumulated_depreciation' => ['required', 'string', 'max:255'],
            'book_value' => ['required', 'string', 'max:255'],
            'asset_account_id' => ['required', 'integer'],
            'accumulated_depreciation_account_id' => ['required', 'integer'],
            'depreciation_expense_account_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'condition' => ['required', 'string', 'max:255'],
            'disposed_at' => ['required', 'string', 'max:255'],
            'disposal_value' => ['required', 'string', 'max:255'],
            'disposal_reason' => ['required', 'string', 'max:255'],
            'photo_url' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $fixedAsset = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $fixedAsset,
                $validated
            ) {
                $fixedAsset->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data FixedAsset berhasil diperbarui.',
                $fixedAsset
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui FixedAsset.',
                [
                    'exception' => $e,
                    'FixedAsset_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FixedAsset gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $fixedAsset = $this->model->findOrFail($id);

            DB::transaction(function () use ($fixedAsset) {
                $fixedAsset->delete();
            });

            return $this->successResponse(
                $request,
                'Data FixedAsset berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $fixedAsset->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus FixedAsset.',
                [
                    'exception' => $e,
                    'FixedAsset_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FixedAsset gagal dihapus.'
            );
        }
    } 
}
