<?php

namespace App\Modules\QuestionMediaAnnotation\Controllers;

use App\DataTables\QuestionMediaAnnotationDataTable;
use App\Http\Controllers\MainController;
use Models\QuestionMediaAnnotation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class QuestionMediaAnnotationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new QuestionMediaAnnotation());
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
        QuestionMediaAnnotationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /question-media-annotation?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'QuestionMediaAnnotation::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $questionMediaAnnotation = new QuestionMediaAnnotation();
        $result = $this->getForm($questionMediaAnnotation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuestionMediaAnnotation::create',
            compact('questionMediaAnnotation', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_media_id' => ['required', 'integer'],
            'label' => ['required', 'string', 'max:255'],
            'position_x' => ['required', 'string', 'max:255'],
            'position_y' => ['required', 'string', 'max:255'],
            'position_z' => ['required', 'string', 'max:255'],
            'is_correct_target' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return QuestionMediaAnnotation::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuestionMediaAnnotation berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan QuestionMediaAnnotation.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionMediaAnnotation gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $questionMediaAnnotation = $this->model->findOrFail($id);
        $result = $this->getForm($questionMediaAnnotation);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuestionMediaAnnotation::edit',
            compact('questionMediaAnnotation', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'question_media_id' => ['required', 'integer'],
            'label' => ['required', 'string', 'max:255'],
            'position_x' => ['required', 'string', 'max:255'],
            'position_y' => ['required', 'string', 'max:255'],
            'position_z' => ['required', 'string', 'max:255'],
            'is_correct_target' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
        ]);

        try {
            $questionMediaAnnotation = $this->model->findOrFail($id);
            DB::transaction(function () use ($questionMediaAnnotation, $validated) {
                $questionMediaAnnotation->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuestionMediaAnnotation berhasil diperbarui.',
                $questionMediaAnnotation
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui QuestionMediaAnnotation.',
                [
                    'exception' => $e,
                    'QuestionMediaAnnotation_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionMediaAnnotation gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $questionMediaAnnotation = $this->model->findOrFail($id);

            DB::transaction(function () use ($questionMediaAnnotation) {
                $questionMediaAnnotation->delete();
            });

            return $this->successResponse(
                $request,
                'Data QuestionMediaAnnotation berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $questionMediaAnnotation->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus QuestionMediaAnnotation.',
                [
                    'exception' => $e,
                    'QuestionMediaAnnotation_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionMediaAnnotation gagal dihapus.'
            );
        }
    }
}
