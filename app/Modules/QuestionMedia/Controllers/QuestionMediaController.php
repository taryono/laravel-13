<?php

namespace App\Modules\QuestionMedia\Controllers;

use App\DataTables\QuestionMediaDataTable;
use App\Http\Controllers\MainController;
use Models\QuestionMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class QuestionMediaController extends MainController
{
    public function __construct()
    {
        parent::__construct(new QuestionMedia());
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
        QuestionMediaDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /question-media?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'QuestionMedia::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $questionMedia = new QuestionMedia();
        $result = $this->getForm($questionMedia);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuestionMedia::create',
            compact('questionMedia', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_bank_id' => ['required', 'integer'],
            'question_option_id' => ['required', 'integer'],
            'question_stimulus_id' => ['required', 'integer'],
            'media_type' => ['required', 'string', 'max:255'],
            'file_format' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:255'],
            'thumbnail_path' => ['required', 'string', 'max:255'],
            'viewer_config' => ['required', 'string', 'max:255'],
            'file_size_bytes' => ['required', 'string', 'max:255'],
            'caption' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return QuestionMedia::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuestionMedia berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan QuestionMedia.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionMedia gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $questionMedia = $this->model->findOrFail($id);
        $result = $this->getForm($questionMedia);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'QuestionMedia::edit',
            compact('questionMedia', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'question_bank_id' => ['required', 'integer'],
            'question_option_id' => ['required', 'integer'],
            'question_stimulus_id' => ['required', 'integer'],
            'media_type' => ['required', 'string', 'max:255'],
            'file_format' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:255'],
            'thumbnail_path' => ['required', 'string', 'max:255'],
            'viewer_config' => ['required', 'string', 'max:255'],
            'file_size_bytes' => ['required', 'string', 'max:255'],
            'caption' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
        ]);

        try {
            $questionMedia = $this->model->findOrFail($id);
            DB::transaction(function () use ($questionMedia, $validated) {
                $questionMedia->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data QuestionMedia berhasil diperbarui.',
                $questionMedia
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui QuestionMedia.',
                [
                    'exception' => $e,
                    'QuestionMedia_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionMedia gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $questionMedia = $this->model->findOrFail($id);

            DB::transaction(function () use ($questionMedia) {
                $questionMedia->delete();
            });

            return $this->successResponse(
                $request,
                'Data QuestionMedia berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $questionMedia->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus QuestionMedia.',
                [
                    'exception' => $e,
                    'QuestionMedia_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data QuestionMedia gagal dihapus.'
            );
        }
    }
}
