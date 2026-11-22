<?php

namespace App\Modules\QuizAttempt\Controllers;

use App\DataTables\QuizAttemptDataTable;
use App\Http\Controllers\MainController;
use Models\QuizAttempt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class QuizAttemptController extends MainController
{
    public function __construct()
    {
        parent::__construct(new QuizAttempt());
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
        QuizAttemptDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /quiz-attempt?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'QuizAttempt::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $quizAttempt = new QuizAttempt();
        $result = $this->getForm($quizAttempt);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuizAttempt::create',
            compact('quizAttempt', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assignment_quiz_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'answers' => ['required', 'string', 'max:255'],
            'score' => ['required', 'string', 'max:255'],
            'started_at' => ['required', 'string', 'max:255'],
            'submitted_at' => ['required', 'string', 'max:255'],
            'time_spent_seconds' => ['required', 'string', 'max:255'],
            'is_completed' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return QuizAttempt::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuizAttempt berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan QuizAttempt.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuizAttempt gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $quizAttempt = $this->model->findOrFail($id);
        $result = $this->getForm($quizAttempt);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuizAttempt::edit',
            compact('quizAttempt', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'assignment_quiz_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'answers' => ['required', 'string', 'max:255'],
            'score' => ['required', 'string', 'max:255'],
            'started_at' => ['required', 'string', 'max:255'],
            'submitted_at' => ['required', 'string', 'max:255'],
            'time_spent_seconds' => ['required', 'string', 'max:255'],
            'is_completed' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $quizAttempt = $this->model->findOrFail($id);
            DB::transaction(function () use ($quizAttempt, $validated) {
                $quizAttempt->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuizAttempt berhasil diperbarui.',
                $quizAttempt
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui QuizAttempt.',
                [
                    'exception' => $e,
                    'QuizAttempt_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuizAttempt gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $quizAttempt = $this->model->findOrFail($id);

            DB::transaction(function () use ($quizAttempt) {
                $quizAttempt->delete();
            });

            return $this->successResponse(
                $request,
                'Data QuizAttempt berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $quizAttempt->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus QuizAttempt.',
                [
                    'exception' => $e,
                    'QuizAttempt_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuizAttempt gagal dihapus.'
            );
        }
    }
}
