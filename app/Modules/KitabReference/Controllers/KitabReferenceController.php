<?php

namespace App\Modules\KitabReference\Controllers;

use App\DataTables\KitabReferenceDataTable;
use App\Http\Controllers\MainController;
use Models\KitabReference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class KitabReferenceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new KitabReference());
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
        KitabReferenceDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /kitab-reference?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'KitabReference::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $kitabReference = new KitabReference();
        $result = $this->getForm($kitabReference);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'KitabReference::create',
            compact('kitabReference', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'course_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
            'education_level_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'arabic_title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'author_arabic' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'publication_year' => ['required', 'string', 'max:255'],
            'edition' => ['required', 'string', 'max:255'],
            'volume' => ['required', 'string', 'max:255'],
            'language' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:255'],
            'total_pages' => ['required', 'string', 'max:255'],
            'total_chapters' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:255'],
            'cover_path' => ['required', 'string', 'max:255'],
            'is_digital' => ['required', 'string', 'max:255'],
            'is_printed' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return KitabReference::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data KitabReference berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan KitabReference.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data KitabReference gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $kitabReference = $this->model->findOrFail($id);
        $result = $this->getForm($kitabReference);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'KitabReference::edit',
            compact('kitabReference', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'course_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
            'education_level_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'arabic_title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'author_arabic' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'publication_year' => ['required', 'string', 'max:255'],
            'edition' => ['required', 'string', 'max:255'],
            'volume' => ['required', 'string', 'max:255'],
            'language' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:255'],
            'total_pages' => ['required', 'string', 'max:255'],
            'total_chapters' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:255'],
            'cover_path' => ['required', 'string', 'max:255'],
            'is_digital' => ['required', 'string', 'max:255'],
            'is_printed' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $kitabReference = $this->model->findOrFail($id);
            DB::transaction(function () use ($kitabReference, $validated) {
                $kitabReference->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data KitabReference berhasil diperbarui.',
                $kitabReference
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui KitabReference.',
                [
                    'exception' => $e,
                    'KitabReference_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data KitabReference gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $kitabReference = $this->model->findOrFail($id);

            DB::transaction(function () use ($kitabReference) {
                $kitabReference->delete();
            });

            return $this->successResponse(
                $request,
                'Data KitabReference berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $kitabReference->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus KitabReference.',
                [
                    'exception' => $e,
                    'KitabReference_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data KitabReference gagal dihapus.'
            );
        }
    }
}
