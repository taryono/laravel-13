<?php

namespace App\Modules\QuestionStimulus\Controllers;

use App\DataTables\QuestionStimulusDataTable;
use App\Http\Controllers\MainController;
use Models\QuestionStimulus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class QuestionStimulusController extends MainController
{
    public function __construct()
    {
        parent::__construct(new QuestionStimulus());
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
        QuestionStimulusDataTable $dataTable
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
            'QuestionStimulus::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $questionStimulus = new QuestionStimulus();
        $result = $this->getForm($questionStimulus);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuestionStimulus::create',
            compact('questionStimulus', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'content_format' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return QuestionStimulus::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuestionStimulus berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan QuestionStimulus.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionStimulus gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $questionStimulus = $this->model->findOrFail($id);
        $result = $this->getForm($questionStimulus);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuestionStimulus::edit',
            compact('questionStimulus', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'content_format' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $questionStimulus = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $questionStimulus,
                $validated
            ) {
                $questionStimulus->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuestionStimulus berhasil diperbarui.',
                $questionStimulus
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui QuestionStimulus.',
                [
                    'exception' => $e,
                    'QuestionStimulus_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionStimulus gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $questionStimulus = $this->model->findOrFail($id);

            DB::transaction(function () use ($questionStimulus) {
                $questionStimulus->delete();
            });

            return $this->successResponse(
                $request,
                'Data QuestionStimulus berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $questionStimulus->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus QuestionStimulus.',
                [
                    'exception' => $e,
                    'QuestionStimulus_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionStimulus gagal dihapus.'
            );
        }
    } 
}
