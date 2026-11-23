<?php

namespace App\Modules\Course\Controllers;

use App\DataTables\CourseDataTable;
use App\Http\Controllers\MainController;
use Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CourseController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Course());
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
        CourseDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /course?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Course::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $course = new Course();
        $result = $this->getForm($course);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Course::create',
            compact('course', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'thumbnail_url' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:255'],
            'duration_hours' => ['required', 'string', 'max:255'],
            'is_published' => ['required', 'string', 'max:255'],
            'published_at' => ['required', 'string', 'max:255'],
            'enrollment_count' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Course::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Course berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Course.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Course gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $course = $this->model->findOrFail($id);
        $result = $this->getForm($course);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Course::edit',
            compact('course', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'thumbnail_url' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:255'],
            'duration_hours' => ['required', 'string', 'max:255'],
            'is_published' => ['required', 'string', 'max:255'],
            'published_at' => ['required', 'string', 'max:255'],
            'enrollment_count' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $course = $this->model->findOrFail($id);
            DB::transaction(function () use ($course, $validated) {
                $course->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Course berhasil diperbarui.',
                $course
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Course.',
                [
                    'exception' => $e,
                    'Course_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Course gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $course = $this->model->findOrFail($id);

            DB::transaction(function () use ($course) {
                $course->delete();
            });

            return $this->successResponse(
                $request,
                'Data Course berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $course->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Course.',
                [
                    'exception' => $e,
                    'Course_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Course gagal dihapus.'
            );
        }
    }
}
