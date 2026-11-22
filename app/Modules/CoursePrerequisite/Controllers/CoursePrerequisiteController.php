<?php

namespace App\Modules\CoursePrerequisite\Controllers;

use App\DataTables\CoursePrerequisiteDataTable;
use App\Http\Controllers\MainController;
use Models\CoursePrerequisite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CoursePrerequisiteController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CoursePrerequisite());
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
        CoursePrerequisiteDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /course-prerequisite?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CoursePrerequisite::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $coursePrerequisite = new CoursePrerequisite();
        $result = $this->getForm($coursePrerequisite);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CoursePrerequisite::create',
            compact('coursePrerequisite', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer'],
            'prerequisite_course_id' => ['required', 'integer'],
            'min_grade' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CoursePrerequisite::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CoursePrerequisite berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CoursePrerequisite.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CoursePrerequisite gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $coursePrerequisite = $this->model->findOrFail($id);
        $result = $this->getForm($coursePrerequisite);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CoursePrerequisite::edit',
            compact('coursePrerequisite', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer'],
            'prerequisite_course_id' => ['required', 'integer'],
            'min_grade' => ['required', 'string', 'max:255'],
        ]);

        try {
            $coursePrerequisite = $this->model->findOrFail($id);
            DB::transaction(function () use ($coursePrerequisite, $validated) {
                $coursePrerequisite->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CoursePrerequisite berhasil diperbarui.',
                $coursePrerequisite
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui CoursePrerequisite.',
                [
                    'exception' => $e,
                    'CoursePrerequisite_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CoursePrerequisite gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $coursePrerequisite = $this->model->findOrFail($id);

            DB::transaction(function () use ($coursePrerequisite) {
                $coursePrerequisite->delete();
            });

            return $this->successResponse(
                $request,
                'Data CoursePrerequisite berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $coursePrerequisite->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CoursePrerequisite.',
                [
                    'exception' => $e,
                    'CoursePrerequisite_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CoursePrerequisite gagal dihapus.'
            );
        }
    }
}
