<?php

namespace App\Modules\StudentLearningPlan\Controllers;

use App\DataTables\StudentLearningPlanDataTable;
use App\Http\Controllers\MainController;
use Models\StudentLearningPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentLearningPlanController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentLearningPlan());
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
        StudentLearningPlanDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-learning-plan?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentLearningPlan::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentLearningPlan = new StudentLearningPlan();
        $result = $this->getForm($studentLearningPlan);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentLearningPlan::create',
            compact('studentLearningPlan', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'age_group' => ['required', 'string', 'max:255'],
            'plan_data' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentLearningPlan::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentLearningPlan berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentLearningPlan.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentLearningPlan gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentLearningPlan = $this->model->findOrFail($id);
        $result = $this->getForm($studentLearningPlan);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentLearningPlan::edit',
            compact('studentLearningPlan', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'age_group' => ['required', 'string', 'max:255'],
            'plan_data' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentLearningPlan = $this->model->findOrFail($id);
            DB::transaction(function () use ($studentLearningPlan, $validated) {
                $studentLearningPlan->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentLearningPlan berhasil diperbarui.',
                $studentLearningPlan
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StudentLearningPlan.',
                [
                    'exception' => $e,
                    'StudentLearningPlan_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentLearningPlan gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentLearningPlan = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentLearningPlan) {
                $studentLearningPlan->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentLearningPlan berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $studentLearningPlan->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentLearningPlan.',
                [
                    'exception' => $e,
                    'StudentLearningPlan_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentLearningPlan gagal dihapus.'
            );
        }
    }
}
