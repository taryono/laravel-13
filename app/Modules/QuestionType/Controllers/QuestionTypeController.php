<?php

namespace App\Modules\QuestionType\Controllers;

use App\DataTables\QuestionTypeDataTable;
use App\Http\Controllers\MainController;
use Models\QuestionType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class QuestionTypeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new QuestionType());
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
        QuestionTypeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /question-type?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'QuestionType::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $questionType = new QuestionType();
        $result = $this->getForm($questionType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuestionType::create',
            compact('questionType', 'forms', 'groupedForms')
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
                return QuestionType::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuestionType berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan QuestionType.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionType gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $question_type = $this->model->findOrFail($id);
        $result = $this->getForm($question_type);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuestionType::edit',
            compact('question_type', 'forms', 'groupedForms')
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
            $questionType = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $questionType,
                $validated
            ) {
                $questionType->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuestionType berhasil diperbarui.',
                $questionType
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui QuestionType.',
                [
                    'exception' => $e,
                    'QuestionType_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionType gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $questionType = $this->model->findOrFail($id);

            DB::transaction(function () use ($questionType) {
                $questionType->delete();
            });

            return $this->successResponse(
                $request,
                'Data QuestionType berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $questionType->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus QuestionType.',
                [
                    'exception' => $e,
                    'QuestionType_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionType gagal dihapus.'
            );
        }
    } 
}
