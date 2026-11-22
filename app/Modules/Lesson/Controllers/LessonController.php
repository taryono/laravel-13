<?php

namespace App\Modules\Lesson\Controllers;

use App\DataTables\LessonDataTable;
use App\Http\Controllers\MainController;
use Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class LessonController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Lesson());
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
        LessonDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /lesson?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Lesson::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $lesson = new Lesson();
        $result = $this->getForm($lesson);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Lesson::create',
            compact('lesson', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'video_url' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'is_free_preview' => ['required', 'string', 'max:255'],
            'is_published' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Lesson::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Lesson berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Lesson.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Lesson gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $lesson = $this->model->findOrFail($id);
        $result = $this->getForm($lesson);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Lesson::edit',
            compact('lesson', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'video_url' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'string', 'max:255'],
            'order_index' => ['required', 'string', 'max:255'],
            'is_free_preview' => ['required', 'string', 'max:255'],
            'is_published' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $lesson = $this->model->findOrFail($id);
            DB::transaction(function () use ($lesson, $validated) {
                $lesson->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Lesson berhasil diperbarui.',
                $lesson
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Lesson.',
                [
                    'exception' => $e,
                    'Lesson_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Lesson gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $lesson = $this->model->findOrFail($id);

            DB::transaction(function () use ($lesson) {
                $lesson->delete();
            });

            return $this->successResponse(
                $request,
                'Data Lesson berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $lesson->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Lesson.',
                [
                    'exception' => $e,
                    'Lesson_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Lesson gagal dihapus.'
            );
        }
    }
}
