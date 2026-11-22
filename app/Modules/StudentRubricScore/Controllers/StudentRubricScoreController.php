<?php

namespace App\Modules\StudentRubricScore\Controllers;

use App\DataTables\StudentRubricScoreDataTable;
use App\Http\Controllers\MainController;
use Models\StudentRubricScore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentRubricScoreController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentRubricScore());
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
        StudentRubricScoreDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-rubric-score?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentRubricScore::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentRubricScore = new StudentRubricScore();
        $result = $this->getForm($studentRubricScore);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentRubricScore::create',
            compact('studentRubricScore', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'student_assessment_id' => ['required', 'integer'],
            'criteria_key' => ['required', 'string', 'max:255'],
            'level_key' => ['required', 'string', 'max:255'],
            'score' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentRubricScore::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentRubricScore berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentRubricScore.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentRubricScore gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentRubricScore = $this->model->findOrFail($id);
        $result = $this->getForm($studentRubricScore);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentRubricScore::edit',
            compact('studentRubricScore', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'student_assessment_id' => ['required', 'integer'],
            'criteria_key' => ['required', 'string', 'max:255'],
            'level_key' => ['required', 'string', 'max:255'],
            'score' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentRubricScore = $this->model->findOrFail($id);
            DB::transaction(function () use ($studentRubricScore, $validated) {
                $studentRubricScore->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentRubricScore berhasil diperbarui.',
                $studentRubricScore
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StudentRubricScore.',
                [
                    'exception' => $e,
                    'StudentRubricScore_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentRubricScore gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentRubricScore = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentRubricScore) {
                $studentRubricScore->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentRubricScore berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $studentRubricScore->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentRubricScore.',
                [
                    'exception' => $e,
                    'StudentRubricScore_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentRubricScore gagal dihapus.'
            );
        }
    }
}
