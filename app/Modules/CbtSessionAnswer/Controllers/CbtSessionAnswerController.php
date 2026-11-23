<?php

namespace App\Modules\CbtSessionAnswer\Controllers;

use App\DataTables\CbtSessionAnswerDataTable;
use App\Http\Controllers\MainController;
use Models\CbtSessionAnswer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CbtSessionAnswerController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CbtSessionAnswer());
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
        CbtSessionAnswerDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cbt-session-answer?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CbtSessionAnswer::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cbtSessionAnswer = new CbtSessionAnswer();
        $result = $this->getForm($cbtSessionAnswer);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CbtSessionAnswer::create',
            compact('cbtSessionAnswer', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cbt_session_id' => ['required', 'integer'],
            'exam_package_question_id' => ['required', 'integer'],
            'student_answer' => ['required', 'string', 'max:255'],
            'is_correct' => ['required', 'string', 'max:255'],
            'score' => ['required', 'string', 'max:255'],
            'answered_at' => ['required', 'string', 'max:255'],
            'time_spent_seconds' => ['required', 'string', 'max:255'],
            'graded_by' => ['required', 'string', 'max:255'],
            'grading_note' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CbtSessionAnswer::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtSessionAnswer berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CbtSessionAnswer.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtSessionAnswer gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cbtSessionAnswer = $this->model->findOrFail($id);
        $result = $this->getForm($cbtSessionAnswer);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CbtSessionAnswer::edit',
            compact('cbtSessionAnswer', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'cbt_session_id' => ['required', 'integer'],
            'exam_package_question_id' => ['required', 'integer'],
            'student_answer' => ['required', 'string', 'max:255'],
            'is_correct' => ['required', 'string', 'max:255'],
            'score' => ['required', 'string', 'max:255'],
            'answered_at' => ['required', 'string', 'max:255'],
            'time_spent_seconds' => ['required', 'string', 'max:255'],
            'graded_by' => ['required', 'string', 'max:255'],
            'grading_note' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $cbtSessionAnswer = $this->model->findOrFail($id);
            DB::transaction(function () use ($cbtSessionAnswer, $validated) {
                $cbtSessionAnswer->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CbtSessionAnswer berhasil diperbarui.',
                $cbtSessionAnswer
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CbtSessionAnswer.',
                [
                    'exception' => $e,
                    'CbtSessionAnswer_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtSessionAnswer gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cbtSessionAnswer = $this->model->findOrFail($id);

            DB::transaction(function () use ($cbtSessionAnswer) {
                $cbtSessionAnswer->delete();
            });

            return $this->successResponse(
                $request,
                'Data CbtSessionAnswer berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $cbtSessionAnswer->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CbtSessionAnswer.',
                [
                    'exception' => $e,
                    'CbtSessionAnswer_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CbtSessionAnswer gagal dihapus.'
            );
        }
    }
}
