<?php

namespace App\Modules\LearningMaterial\Controllers;

use App\DataTables\LearningMaterialDataTable;
use App\Http\Controllers\MainController;
use Models\LearningMaterial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class LearningMaterialController extends MainController
{
    public function __construct()
    {
        parent::__construct(new LearningMaterial());
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
        LearningMaterialDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /learning-material?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'LearningMaterial::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $learningMaterial = new LearningMaterial();
        $result = $this->getForm($learningMaterial);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LearningMaterial::create',
            compact('learningMaterial', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'lesson_plan_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'curriculum_phase_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'file_name' => ['required', 'string', 'max:255'],
            'file_size' => ['required', 'string', 'max:255'],
            'mime_type' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'is_public' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return LearningMaterial::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data LearningMaterial berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan LearningMaterial.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LearningMaterial gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $learningMaterial = $this->model->findOrFail($id);
        $result = $this->getForm($learningMaterial);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LearningMaterial::edit',
            compact('learningMaterial', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'lesson_plan_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'curriculum_phase_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'file_name' => ['required', 'string', 'max:255'],
            'file_size' => ['required', 'string', 'max:255'],
            'mime_type' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'is_public' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $learningMaterial = $this->model->findOrFail($id);
            DB::transaction(function () use ($learningMaterial, $validated) {
                $learningMaterial->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data LearningMaterial berhasil diperbarui.',
                $learningMaterial
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui LearningMaterial.',
                [
                    'exception' => $e,
                    'LearningMaterial_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LearningMaterial gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $learningMaterial = $this->model->findOrFail($id);

            DB::transaction(function () use ($learningMaterial) {
                $learningMaterial->delete();
            });

            return $this->successResponse(
                $request,
                'Data LearningMaterial berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $learningMaterial->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus LearningMaterial.',
                [
                    'exception' => $e,
                    'LearningMaterial_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LearningMaterial gagal dihapus.'
            );
        }
    }
}
