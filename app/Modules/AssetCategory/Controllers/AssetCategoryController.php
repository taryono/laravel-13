<?php

namespace App\Modules\AssetCategory\Controllers;

use App\DataTables\AssetCategoryDataTable;
use App\Http\Controllers\MainController;
use Models\AssetCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AssetCategoryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AssetCategory());
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
        AssetCategoryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /asset-category?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AssetCategory::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $assetCategory = new AssetCategory();
        $result = $this->getForm($assetCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssetCategory::create',
            compact('assetCategory', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AssetCategory::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssetCategory berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AssetCategory.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssetCategory gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $assetCategory = $this->model->findOrFail($id);
        $result = $this->getForm($assetCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssetCategory::edit',
            compact('assetCategory', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $assetCategory = $this->model->findOrFail($id);
            DB::transaction(function () use ($assetCategory, $validated) {
                $assetCategory->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssetCategory berhasil diperbarui.',
                $assetCategory
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AssetCategory.',
                [
                    'exception' => $e,
                    'AssetCategory_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssetCategory gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $assetCategory = $this->model->findOrFail($id);

            DB::transaction(function () use ($assetCategory) {
                $assetCategory->delete();
            });

            return $this->successResponse(
                $request,
                'Data AssetCategory berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $assetCategory->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AssetCategory.',
                [
                    'exception' => $e,
                    'AssetCategory_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssetCategory gagal dihapus.'
            );
        }
    }
}
