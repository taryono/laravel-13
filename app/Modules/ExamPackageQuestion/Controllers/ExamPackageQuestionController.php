<?php

namespace App\Modules\ExamPackageQuestion\Controllers;

use App\DataTables\ExamPackageQuestionDataTable;
use App\Http\Controllers\MainController;
use Models\ExamPackageQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ExamPackageQuestionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ExamPackageQuestion());
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
        ExamPackageQuestionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /exam-package-question?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ExamPackageQuestion::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $examPackageQuestion = new ExamPackageQuestion();
        $result = $this->getForm($examPackageQuestion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExamPackageQuestion::create',
            compact('examPackageQuestion', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'exam_package_id' => ['required', 'integer'],
            'question_bank_id' => ['required', 'integer'],
            'question_type_id' => ['required', 'integer'],
            'question_text_snapshot' => ['required', 'string', 'max:255'],
            'content_format' => ['required', 'string', 'max:255'],
            'options_snapshot' => ['required', 'string', 'max:255'],
            'correct_answer_snapshot' => ['required', 'string', 'max:255'],
            'stimulus_snapshot' => ['required', 'string', 'max:255'],
            'points' => ['required', 'string', 'max:255'],
            'partial_scoring' => ['required', 'string', 'max:255'],
            'question_order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ExamPackageQuestion::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExamPackageQuestion berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ExamPackageQuestion.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExamPackageQuestion gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $examPackageQuestion = $this->model->findOrFail($id);
        $result = $this->getForm($examPackageQuestion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExamPackageQuestion::edit',
            compact('examPackageQuestion', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'exam_package_id' => ['required', 'integer'],
            'question_bank_id' => ['required', 'integer'],
            'question_type_id' => ['required', 'integer'],
            'question_text_snapshot' => ['required', 'string', 'max:255'],
            'content_format' => ['required', 'string', 'max:255'],
            'options_snapshot' => ['required', 'string', 'max:255'],
            'correct_answer_snapshot' => ['required', 'string', 'max:255'],
            'stimulus_snapshot' => ['required', 'string', 'max:255'],
            'points' => ['required', 'string', 'max:255'],
            'partial_scoring' => ['required', 'string', 'max:255'],
            'question_order' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $examPackageQuestion = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $examPackageQuestion,
                $validated
            ) {
                $examPackageQuestion->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExamPackageQuestion berhasil diperbarui.',
                $examPackageQuestion
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ExamPackageQuestion.',
                [
                    'exception' => $e,
                    'ExamPackageQuestion_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExamPackageQuestion gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $examPackageQuestion = $this->model->findOrFail($id);

            DB::transaction(function () use ($examPackageQuestion) {
                $examPackageQuestion->delete();
            });

            return $this->successResponse(
                $request,
                'Data ExamPackageQuestion berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $examPackageQuestion->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ExamPackageQuestion.',
                [
                    'exception' => $e,
                    'ExamPackageQuestion_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExamPackageQuestion gagal dihapus.'
            );
        }
    } 
}
