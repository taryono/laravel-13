<?php

namespace App\Modules\QuestionOption\Controllers;

use App\DataTables\QuestionOptionDataTable;
use App\Http\Controllers\MainController;
use Models\QuestionOption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class QuestionOptionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new QuestionOption());
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
        QuestionOptionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /question-option?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'QuestionOption::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $questionOption = new QuestionOption();
        $result = $this->getForm($questionOption);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuestionOption::create',
            compact('questionOption', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_bank_id' => ['required', 'integer'],
            'option_label' => ['required', 'string', 'max:255'],
            'option_text' => ['required', 'string', 'max:255'],
            'content_format' => ['required', 'string', 'max:255'],
            'is_correct' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return QuestionOption::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuestionOption berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan QuestionOption.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionOption gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $questionOption = $this->model->findOrFail($id);
        $result = $this->getForm($questionOption);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuestionOption::edit',
            compact('questionOption', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'question_bank_id' => ['required', 'integer'],
            'option_label' => ['required', 'string', 'max:255'],
            'option_text' => ['required', 'string', 'max:255'],
            'content_format' => ['required', 'string', 'max:255'],
            'is_correct' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
        ]);

        try {
            $questionOption = $this->model->findOrFail($id);
            DB::transaction(function () use ($questionOption, $validated) {
                $questionOption->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuestionOption berhasil diperbarui.',
                $questionOption
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui QuestionOption.',
                [
                    'exception' => $e,
                    'QuestionOption_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionOption gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $questionOption = $this->model->findOrFail($id);

            DB::transaction(function () use ($questionOption) {
                $questionOption->delete();
            });

            return $this->successResponse(
                $request,
                'Data QuestionOption berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $questionOption->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus QuestionOption.',
                [
                    'exception' => $e,
                    'QuestionOption_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionOption gagal dihapus.'
            );
        }
    }
}
