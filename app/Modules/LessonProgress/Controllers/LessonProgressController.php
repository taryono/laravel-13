<?php

namespace App\Modules\LessonProgress\Controllers;

use App\DataTables\LessonProgressDataTable;
use App\Http\Controllers\MainController;
use Models\LessonProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class LessonProgressController extends MainController
{
    public function __construct()
    {
        parent::__construct(new LessonProgress());
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
        LessonProgressDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /lesson-progress?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'LessonProgress::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $lessonProgress = new LessonProgress();
        $result = $this->getForm($lessonProgress);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LessonProgress::create',
            compact('lessonProgress', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'lesson_id' => ['required', 'integer'],
            'is_completed' => ['required', 'string', 'max:255'],
            'progress_percent' => ['required', 'string', 'max:255'],
            'last_position_seconds' => ['required', 'string', 'max:255'],
            'started_at' => ['required', 'string', 'max:255'],
            'completed_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return LessonProgress::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data LessonProgress berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan LessonProgress.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LessonProgress gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $lessonProgress = $this->model->findOrFail($id);
        $result = $this->getForm($lessonProgress);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'LessonProgress::edit',
            compact('lessonProgress', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'lesson_id' => ['required', 'integer'],
            'is_completed' => ['required', 'string', 'max:255'],
            'progress_percent' => ['required', 'string', 'max:255'],
            'last_position_seconds' => ['required', 'string', 'max:255'],
            'started_at' => ['required', 'string', 'max:255'],
            'completed_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $lessonProgress = $this->model->findOrFail($id);
            DB::transaction(function () use ($lessonProgress, $validated) {
                $lessonProgress->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data LessonProgress berhasil diperbarui.',
                $lessonProgress
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui LessonProgress.',
                [
                    'exception' => $e,
                    'LessonProgress_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LessonProgress gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $lessonProgress = $this->model->findOrFail($id);

            DB::transaction(function () use ($lessonProgress) {
                $lessonProgress->delete();
            });

            return $this->successResponse(
                $request,
                'Data LessonProgress berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $lessonProgress->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus LessonProgress.',
                [
                    'exception' => $e,
                    'LessonProgress_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data LessonProgress gagal dihapus.'
            );
        }
    }
}
