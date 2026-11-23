<?php

namespace App\Modules\CurriculumCourse\Controllers;

use App\DataTables\CurriculumCourseDataTable;
use App\Http\Controllers\MainController;
use Models\CurriculumCourse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CurriculumCourseController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CurriculumCourse());
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
        CurriculumCourseDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /curriculum-course?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CurriculumCourse::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $curriculumCourse = new CurriculumCourse();
        $result = $this->getForm($curriculumCourse);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CurriculumCourse::create',
            compact('curriculumCourse', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'study_program_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'sks_theory' => ['required', 'string', 'max:255'],
            'sks_practical' => ['required', 'string', 'max:255'],
            'total_sks' => ['required', 'string', 'max:255'],
            'semester_recommendation' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'syllabus_url' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CurriculumCourse::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CurriculumCourse berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CurriculumCourse.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumCourse gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $curriculumCourse = $this->model->findOrFail($id);
        $result = $this->getForm($curriculumCourse);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CurriculumCourse::edit',
            compact('curriculumCourse', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'study_program_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'sks_theory' => ['required', 'string', 'max:255'],
            'sks_practical' => ['required', 'string', 'max:255'],
            'total_sks' => ['required', 'string', 'max:255'],
            'semester_recommendation' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'syllabus_url' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $curriculumCourse = $this->model->findOrFail($id);
            DB::transaction(function () use ($curriculumCourse, $validated) {
                $curriculumCourse->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CurriculumCourse berhasil diperbarui.',
                $curriculumCourse
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CurriculumCourse.',
                [
                    'exception' => $e,
                    'CurriculumCourse_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumCourse gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $curriculumCourse = $this->model->findOrFail($id);

            DB::transaction(function () use ($curriculumCourse) {
                $curriculumCourse->delete();
            });

            return $this->successResponse(
                $request,
                'Data CurriculumCourse berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $curriculumCourse->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CurriculumCourse.',
                [
                    'exception' => $e,
                    'CurriculumCourse_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CurriculumCourse gagal dihapus.'
            );
        }
    }
}
