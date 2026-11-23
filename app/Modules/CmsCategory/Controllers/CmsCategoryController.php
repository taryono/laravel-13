<?php

namespace App\Modules\CmsCategory\Controllers;

use App\DataTables\CmsCategoryDataTable;
use App\Http\Controllers\MainController;
use Models\CmsCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CmsCategoryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CmsCategory());
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
        CmsCategoryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cms-category?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CmsCategory::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cmsCategory = new CmsCategory();
        $result = $this->getForm($cmsCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsCategory::create',
            compact('cmsCategory', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CmsCategory::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsCategory berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CmsCategory.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsCategory gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cmsCategory = $this->model->findOrFail($id);
        $result = $this->getForm($cmsCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CmsCategory::edit',
            compact('cmsCategory', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'parent_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cmsCategory = $this->model->findOrFail($id);
            DB::transaction(function () use ($cmsCategory, $validated) {
                $cmsCategory->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CmsCategory berhasil diperbarui.',
                $cmsCategory
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CmsCategory.',
                [
                    'exception' => $e,
                    'CmsCategory_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsCategory gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cmsCategory = $this->model->findOrFail($id);

            DB::transaction(function () use ($cmsCategory) {
                $cmsCategory->delete();
            });

            return $this->successResponse(
                $request,
                'Data CmsCategory berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $cmsCategory->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CmsCategory.',
                [
                    'exception' => $e,
                    'CmsCategory_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CmsCategory gagal dihapus.'
            );
        }
    }
}
