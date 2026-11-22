<?php

namespace App\Modules\LessonPlan\Controllers;

use App\DataTables\LessonPlanDataTable;
use App\Http\Controllers\MainController;
use Models\LessonPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class LessonPlanController extends MainController
{
    public function __construct()
    {
        parent::__construct(new LessonPlan());
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
        LessonPlanDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /lesson-plan?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'LessonPlan::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $lessonPlan = new LessonPlan();
        $result = $this->getForm($lessonPlan);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LessonPlan::create',
            compact('lessonPlan', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'curriculum_phase_id' => ['required', 'integer'],
            'period_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'learning_objectives' => ['required', 'string', 'max:255'],
            'learning_outcomes' => ['required', 'string', 'max:255'],
            'activities' => ['required', 'string', 'max:255'],
            'assessment_plan' => ['required', 'string', 'max:255'],
            'media' => ['required', 'string', 'max:255'],
            'resources' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'string', 'max:255'],
            'meeting_number' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'rejection_notes' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return LessonPlan::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data LessonPlan berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan LessonPlan.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LessonPlan gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $lessonPlan = $this->model->findOrFail($id);
        $result = $this->getForm($lessonPlan);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LessonPlan::edit',
            compact('lessonPlan', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'class_level_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'curriculum_phase_id' => ['required', 'integer'],
            'period_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'learning_objectives' => ['required', 'string', 'max:255'],
            'learning_outcomes' => ['required', 'string', 'max:255'],
            'activities' => ['required', 'string', 'max:255'],
            'assessment_plan' => ['required', 'string', 'max:255'],
            'media' => ['required', 'string', 'max:255'],
            'resources' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'string', 'max:255'],
            'meeting_number' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'rejection_notes' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $lessonPlan = $this->model->findOrFail($id);
            DB::transaction(function () use ($lessonPlan, $validated) {
                $lessonPlan->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data LessonPlan berhasil diperbarui.',
                $lessonPlan
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui LessonPlan.',
                [
                    'exception' => $e,
                    'LessonPlan_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LessonPlan gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $lessonPlan = $this->model->findOrFail($id);

            DB::transaction(function () use ($lessonPlan) {
                $lessonPlan->delete();
            });

            return $this->successResponse(
                $request,
                'Data LessonPlan berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $lessonPlan->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus LessonPlan.',
                [
                    'exception' => $e,
                    'LessonPlan_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LessonPlan gagal dihapus.'
            );
        }
    }
}
