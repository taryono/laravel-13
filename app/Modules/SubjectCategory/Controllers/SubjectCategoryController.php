<?php

namespace App\Modules\SubjectCategory\Controllers;

use App\DataTables\SubjectCategoryDataTable;
use App\Http\Controllers\MainController;
use Models\SubjectCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SubjectCategoryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new SubjectCategory());
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
        SubjectCategoryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /subject-category?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'SubjectCategory::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $subjectCategory = new SubjectCategory();
        $result = $this->getForm($subjectCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SubjectCategory::create',
            compact('subjectCategory', 'forms', 'groupedForms')
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
                return SubjectCategory::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data SubjectCategory berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan SubjectCategory.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SubjectCategory gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $subjectCategory = $this->model->findOrFail($id);
        $result = $this->getForm($subjectCategory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SubjectCategory::edit',
            compact('subjectCategory', 'forms', 'groupedForms')
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
            $subjectCategory = $this->model->findOrFail($id);
            DB::transaction(function () use ($subjectCategory, $validated) {
                $subjectCategory->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data SubjectCategory berhasil diperbarui.',
                $subjectCategory
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui SubjectCategory.',
                [
                    'exception' => $e,
                    'SubjectCategory_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SubjectCategory gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $subjectCategory = $this->model->findOrFail($id);

            DB::transaction(function () use ($subjectCategory) {
                $subjectCategory->delete();
            });

            return $this->successResponse(
                $request,
                'Data SubjectCategory berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $subjectCategory->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus SubjectCategory.',
                [
                    'exception' => $e,
                    'SubjectCategory_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SubjectCategory gagal dihapus.'
            );
        }
    }
}
