<?php

namespace App\Modules\QuestionStimuli\Controllers;

use App\DataTables\QuestionStimuliDataTable;
use App\Http\Controllers\MainController;
use Models\QuestionStimuli;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class QuestionStimuliController extends MainController
{
    public function __construct()
    {
        parent::__construct(new QuestionStimuli());
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
        QuestionStimuliDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /question-stimulus?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'QuestionStimuli::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $question_stimuli = new QuestionStimuli();
        $result = $this->getForm($question_stimuli);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuestionStimuli::create',
            compact('question_stimuli', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return QuestionStimuli::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuestionStimuli berhasil ditambahkan.',
                $model
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan QuestionStimuli.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionStimuli gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $question_stimuli = $this->model->findOrFail($id);
        $result = $this->getForm($question_stimuli);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuestionStimuli::edit',
            compact('question_stimuli', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $question_stimuli = $this->model->findOrFail($id);
            DB::transaction(function () use ($question_stimuli, $validated) {
                $question_stimuli->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuestionStimuli berhasil diperbarui.',
                $question_stimuli
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui QuestionStimuli.',
                [
                    'exception' => $e,
                    'QuestionStimuli_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionStimuli gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $question_stimuli = $this->model->findOrFail($id);

            DB::transaction(function () use ($question_stimuli) {
                $question_stimuli->delete();
            });

            return $this->successResponse(
                $request,
                'Data QuestionStimuli berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $question_stimuli->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus QuestionStimuli.',
                [
                    'exception' => $e,
                    'QuestionStimuli_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionStimuli gagal dihapus.'
            );
        }
    }
}
