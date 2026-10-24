<?php

namespace App\Modules\AssignmentsQuiz\Controllers;

use App\DataTables\AssignmentsQuizDataTable;
use App\Http\Controllers\MainController;
use Models\AssignmentsQuiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AssignmentsQuizController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AssignmentsQuiz());
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
        AssignmentsQuizDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /assignments-quiz?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AssignmentsQuiz::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $assignmentsQuiz = new AssignmentsQuiz();
        $result = $this->getForm($assignmentsQuiz);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssignmentsQuiz::create',
            compact('assignmentsQuiz', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'course_id' => ['required', 'integer'],
            'lesson_id' => ['required', 'integer'],
            'type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'time_limit_minutes' => ['required', 'string', 'max:255'],
            'passing_score' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'string', 'max:255'],
            'is_published' => ['required', 'string', 'max:255'],
            'question_data' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AssignmentsQuiz::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssignmentsQuiz berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AssignmentsQuiz.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssignmentsQuiz gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $assignmentsQuiz = $this->model->findOrFail($id);
        $result = $this->getForm($assignmentsQuiz);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AssignmentsQuiz::edit',
            compact('assignmentsQuiz', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'course_id' => ['required', 'integer'],
            'lesson_id' => ['required', 'integer'],
            'type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'time_limit_minutes' => ['required', 'string', 'max:255'],
            'passing_score' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'string', 'max:255'],
            'is_published' => ['required', 'string', 'max:255'],
            'question_data' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $assignmentsQuiz = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $assignmentsQuiz,
                $validated
            ) {
                $assignmentsQuiz->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AssignmentsQuiz berhasil diperbarui.',
                $assignmentsQuiz
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui AssignmentsQuiz.',
                [
                    'exception' => $e,
                    'AssignmentsQuiz_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssignmentsQuiz gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $assignmentsQuiz = $this->model->findOrFail($id);

            DB::transaction(function () use ($assignmentsQuiz) {
                $assignmentsQuiz->delete();
            });

            return $this->successResponse(
                $request,
                'Data AssignmentsQuiz berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $assignmentsQuiz->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AssignmentsQuiz.',
                [
                    'exception' => $e,
                    'AssignmentsQuiz_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AssignmentsQuiz gagal dihapus.'
            );
        }
    } 
}
