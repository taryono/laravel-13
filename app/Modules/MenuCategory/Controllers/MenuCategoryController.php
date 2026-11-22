<?php

namespace App\Modules\MenuCategory\Controllers;

use App\DataTables\MenuCategoryDataTable;
use App\Http\Controllers\MainController;
use Models\MenuCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class MenuCategoryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new MenuCategory());
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
        MenuCategoryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /menu-category?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'MenuCategory::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $menuCategory = new MenuCategory();
        $result = $this->getForm($menuCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'MenuCategory::create',
            compact('menuCategory', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_system' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'parent_id' => ['required', 'integer'],
            'show_to_trial' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return MenuCategory::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data MenuCategory berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan MenuCategory.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MenuCategory gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $menuCategory = $this->model->findOrFail($id);
        $result = $this->getForm($menuCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'MenuCategory::edit',
            compact('menuCategory', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_system' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'parent_id' => ['required', 'integer'],
            'show_to_trial' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $menuCategory = $this->model->findOrFail($id);
            DB::transaction(function () use ($menuCategory, $validated) {
                $menuCategory->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data MenuCategory berhasil diperbarui.',
                $menuCategory
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui MenuCategory.',
                [
                    'exception' => $e,
                    'MenuCategory_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MenuCategory gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $menuCategory = $this->model->findOrFail($id);

            DB::transaction(function () use ($menuCategory) {
                $menuCategory->delete();
            });

            return $this->successResponse(
                $request,
                'Data MenuCategory berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $menuCategory->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus MenuCategory.',
                [
                    'exception' => $e,
                    'MenuCategory_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MenuCategory gagal dihapus.'
            );
        }
    }
}
