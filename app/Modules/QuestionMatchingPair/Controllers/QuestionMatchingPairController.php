<?php

namespace App\Modules\QuestionMatchingPair\Controllers;

use App\DataTables\QuestionMatchingPairDataTable;
use App\Http\Controllers\MainController;
use Models\QuestionMatchingPair;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class QuestionMatchingPairController extends MainController
{
    public function __construct()
    {
        parent::__construct(new QuestionMatchingPair());
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
        QuestionMatchingPairDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /question-matching-pair?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'QuestionMatchingPair::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $questionMatchingPair = new QuestionMatchingPair();
        $result = $this->getForm($questionMatchingPair);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuestionMatchingPair::create',
            compact('questionMatchingPair', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_bank_id' => ['required', 'integer'],
            'left_item' => ['required', 'string', 'max:255'],
            'right_item' => ['required', 'string', 'max:255'],
            'content_format' => ['required', 'string', 'max:255'],
            'pair_key' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return QuestionMatchingPair::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuestionMatchingPair berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan QuestionMatchingPair.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionMatchingPair gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $questionMatchingPair = $this->model->findOrFail($id);
        $result = $this->getForm($questionMatchingPair);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuestionMatchingPair::edit',
            compact('questionMatchingPair', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'question_bank_id' => ['required', 'integer'],
            'left_item' => ['required', 'string', 'max:255'],
            'right_item' => ['required', 'string', 'max:255'],
            'content_format' => ['required', 'string', 'max:255'],
            'pair_key' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
        ]);

        try {
            $questionMatchingPair = $this->model->findOrFail($id);
            DB::transaction(function () use ($questionMatchingPair, $validated) {
                $questionMatchingPair->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuestionMatchingPair berhasil diperbarui.',
                $questionMatchingPair
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui QuestionMatchingPair.',
                [
                    'exception' => $e,
                    'QuestionMatchingPair_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionMatchingPair gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $questionMatchingPair = $this->model->findOrFail($id);

            DB::transaction(function () use ($questionMatchingPair) {
                $questionMatchingPair->delete();
            });

            return $this->successResponse(
                $request,
                'Data QuestionMatchingPair berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $questionMatchingPair->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus QuestionMatchingPair.',
                [
                    'exception' => $e,
                    'QuestionMatchingPair_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionMatchingPair gagal dihapus.'
            );
        }
    }
}
