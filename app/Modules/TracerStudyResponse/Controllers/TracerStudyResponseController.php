<?php

namespace App\Modules\TracerStudyResponse\Controllers;

use App\DataTables\TracerStudyResponseDataTable;
use App\Http\Controllers\MainController;
use Models\TracerStudyResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TracerStudyResponseController extends MainController
{
    public function __construct()
    {
        parent::__construct(new TracerStudyResponse());
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
        TracerStudyResponseDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /tracer-study-response?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'TracerStudyResponse::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tracerStudyResponse = new TracerStudyResponse();
        $result = $this->getForm($tracerStudyResponse);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TracerStudyResponse::create',
            compact('tracerStudyResponse', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tracer_study_id' => ['required', 'integer'],
            'alumni_id' => ['required', 'integer'],
            'question_id' => ['required', 'integer'],
            'answer_text' => ['required', 'string', 'max:255'],
            'answer_json' => ['required', 'string', 'max:255'],
            'submitted_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return TracerStudyResponse::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data TracerStudyResponse berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan TracerStudyResponse.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TracerStudyResponse gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $tracerStudyResponse = $this->model->findOrFail($id);
        $result = $this->getForm($tracerStudyResponse);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TracerStudyResponse::edit',
            compact('tracerStudyResponse', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tracer_study_id' => ['required', 'integer'],
            'alumni_id' => ['required', 'integer'],
            'question_id' => ['required', 'integer'],
            'answer_text' => ['required', 'string', 'max:255'],
            'answer_json' => ['required', 'string', 'max:255'],
            'submitted_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $tracerStudyResponse = $this->model->findOrFail($id);
            DB::transaction(function () use ($tracerStudyResponse, $validated) {
                $tracerStudyResponse->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data TracerStudyResponse berhasil diperbarui.',
                $tracerStudyResponse
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui TracerStudyResponse.',
                [
                    'exception' => $e,
                    'TracerStudyResponse_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TracerStudyResponse gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $tracerStudyResponse = $this->model->findOrFail($id);

            DB::transaction(function () use ($tracerStudyResponse) {
                $tracerStudyResponse->delete();
            });

            return $this->successResponse(
                $request,
                'Data TracerStudyResponse berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $tracerStudyResponse->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus TracerStudyResponse.',
                [
                    'exception' => $e,
                    'TracerStudyResponse_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TracerStudyResponse gagal dihapus.'
            );
        }
    }
}
