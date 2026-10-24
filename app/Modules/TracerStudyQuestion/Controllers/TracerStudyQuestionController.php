<?php

namespace App\Modules\TracerStudyQuestion\Controllers;

use App\DataTables\TracerStudyQuestionDataTable;
use App\Http\Controllers\MainController;
use Models\TracerStudyQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TracerStudyQuestionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new TracerStudyQuestion());
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
        TracerStudyQuestionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /tracer-study-question?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'TracerStudyQuestion::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tracerStudyQuestion = new TracerStudyQuestion();
        $result = $this->getForm($tracerStudyQuestion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TracerStudyQuestion::create',
            compact('tracerStudyQuestion', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'tracer_study_id' => ['required', 'integer'],
            'question_order' => ['required', 'string', 'max:255'],
            'question_text' => ['required', 'string', 'max:255'],
            'question_type' => ['required', 'string', 'max:255'],
            'options_json' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return TracerStudyQuestion::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data TracerStudyQuestion berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan TracerStudyQuestion.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TracerStudyQuestion gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $tracerStudyQuestion = $this->model->findOrFail($id);
        $result = $this->getForm($tracerStudyQuestion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TracerStudyQuestion::edit',
            compact('tracerStudyQuestion', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'tracer_study_id' => ['required', 'integer'],
            'question_order' => ['required', 'string', 'max:255'],
            'question_text' => ['required', 'string', 'max:255'],
            'question_type' => ['required', 'string', 'max:255'],
            'options_json' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
        ]);

        try {
            $tracerStudyQuestion = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $tracerStudyQuestion,
                $validated
            ) {
                $tracerStudyQuestion->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data TracerStudyQuestion berhasil diperbarui.',
                $tracerStudyQuestion
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui TracerStudyQuestion.',
                [
                    'exception' => $e,
                    'TracerStudyQuestion_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TracerStudyQuestion gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $tracerStudyQuestion = $this->model->findOrFail($id);

            DB::transaction(function () use ($tracerStudyQuestion) {
                $tracerStudyQuestion->delete();
            });

            return $this->successResponse(
                $request,
                'Data TracerStudyQuestion berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $tracerStudyQuestion->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus TracerStudyQuestion.',
                [
                    'exception' => $e,
                    'TracerStudyQuestion_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TracerStudyQuestion gagal dihapus.'
            );
        }
    } 
}
