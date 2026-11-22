<?php

namespace App\Modules\BookCategory\Controllers;

use App\DataTables\BookCategoryDataTable;
use App\Http\Controllers\MainController;
use Models\BookCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class BookCategoryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new BookCategory());
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
        BookCategoryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /book-category?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'BookCategory::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bookCategory = new BookCategory();
        $result = $this->getForm($bookCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BookCategory::create',
            compact('bookCategory', 'forms', 'groupedForms')
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
            'ddc_classification' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return BookCategory::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data BookCategory berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan BookCategory.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BookCategory gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $bookCategory = $this->model->findOrFail($id);
        $result = $this->getForm($bookCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'BookCategory::edit',
            compact('bookCategory', 'forms', 'groupedForms')
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
            'ddc_classification' => ['required', 'string', 'max:255'],
        ]);

        try {
            $bookCategory = $this->model->findOrFail($id);
            DB::transaction(function () use ($bookCategory, $validated) {
                $bookCategory->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data BookCategory berhasil diperbarui.',
                $bookCategory
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui BookCategory.',
                [
                    'exception' => $e,
                    'BookCategory_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BookCategory gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $bookCategory = $this->model->findOrFail($id);

            DB::transaction(function () use ($bookCategory) {
                $bookCategory->delete();
            });

            return $this->successResponse(
                $request,
                'Data BookCategory berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $bookCategory->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus BookCategory.',
                [
                    'exception' => $e,
                    'BookCategory_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data BookCategory gagal dihapus.'
            );
        }
    }
}
