<?php

namespace App\Modules\Submission\Controllers;

use App\DataTables\SubmissionDataTable;
use App\Http\Controllers\MainController;
use Models\Submission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SubmissionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Submission());
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
        SubmissionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /submission?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Submission::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $submission = new Submission();
        $result = $this->getForm($submission);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Submission::create',
            compact('submission', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assignment_quiz_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'submission_text' => ['required', 'string', 'max:255'],
            'file_urls' => ['required', 'string', 'max:255'],
            'score' => ['required', 'string', 'max:255'],
            'feedback' => ['required', 'string', 'max:255'],
            'graded_by' => ['required', 'string', 'max:255'],
            'submitted_at' => ['required', 'string', 'max:255'],
            'graded_at' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Submission::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Submission berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Submission.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Submission gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $submission = $this->model->findOrFail($id);
        $result = $this->getForm($submission);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Submission::edit',
            compact('submission', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'assignment_quiz_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'submission_text' => ['required', 'string', 'max:255'],
            'file_urls' => ['required', 'string', 'max:255'],
            'score' => ['required', 'string', 'max:255'],
            'feedback' => ['required', 'string', 'max:255'],
            'graded_by' => ['required', 'string', 'max:255'],
            'submitted_at' => ['required', 'string', 'max:255'],
            'graded_at' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $submission = $this->model->findOrFail($id);
            DB::transaction(function () use ($submission, $validated) {
                $submission->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Submission berhasil diperbarui.',
                $submission
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Submission.',
                [
                    'exception' => $e,
                    'Submission_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Submission gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $submission = $this->model->findOrFail($id);

            DB::transaction(function () use ($submission) {
                $submission->delete();
            });

            return $this->successResponse(
                $request,
                'Data Submission berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $submission->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Submission.',
                [
                    'exception' => $e,
                    'Submission_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Submission gagal dihapus.'
            );
        }
    }
}
