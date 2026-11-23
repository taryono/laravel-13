<?php

namespace App\Modules\StudentOutcomeAchievement\Controllers;

use App\DataTables\StudentOutcomeAchievementDataTable;
use App\Http\Controllers\MainController;
use Models\StudentOutcomeAchievement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentOutcomeAchievementController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentOutcomeAchievement());
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
        StudentOutcomeAchievementDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-outcome-achievement?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentOutcomeAchievement::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentOutcomeAchievement = new StudentOutcomeAchievement();
        $result = $this->getForm($studentOutcomeAchievement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentOutcomeAchievement::create',
            compact('studentOutcomeAchievement', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'learning_outcome_id' => ['required', 'integer'],
            'period_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'achievement_level' => ['required', 'string', 'max:255'],
            'score' => ['required', 'string', 'max:255'],
            'narrative' => ['required', 'string', 'max:255'],
            'teacher_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentOutcomeAchievement::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentOutcomeAchievement berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentOutcomeAchievement.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentOutcomeAchievement gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentOutcomeAchievement = $this->model->findOrFail($id);
        $result = $this->getForm($studentOutcomeAchievement);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentOutcomeAchievement::edit',
            compact('studentOutcomeAchievement', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'learning_outcome_id' => ['required', 'integer'],
            'period_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'achievement_level' => ['required', 'string', 'max:255'],
            'score' => ['required', 'string', 'max:255'],
            'narrative' => ['required', 'string', 'max:255'],
            'teacher_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentOutcomeAchievement = $this->model->findOrFail($id);
            DB::transaction(function () use ($studentOutcomeAchievement, $validated) {
                $studentOutcomeAchievement->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentOutcomeAchievement berhasil diperbarui.',
                $studentOutcomeAchievement
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StudentOutcomeAchievement.',
                [
                    'exception' => $e,
                    'StudentOutcomeAchievement_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentOutcomeAchievement gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentOutcomeAchievement = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentOutcomeAchievement) {
                $studentOutcomeAchievement->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentOutcomeAchievement berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $studentOutcomeAchievement->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentOutcomeAchievement.',
                [
                    'exception' => $e,
                    'StudentOutcomeAchievement_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentOutcomeAchievement gagal dihapus.'
            );
        }
    }
}
