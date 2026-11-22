<?php

namespace App\Modules\StudentCurriculum\Controllers;

use App\DataTables\StudentCurriculumDataTable;
use App\Http\Controllers\MainController;
use Models\StudentCurriculum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentCurriculumController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentCurriculum());
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
        StudentCurriculumDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-curriculum?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentCurriculum::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentCurriculum = new StudentCurriculum();
        $result = $this->getForm($studentCurriculum);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentCurriculum::create',
            compact('studentCurriculum', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentCurriculum::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentCurriculum berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentCurriculum.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentCurriculum gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentCurriculum = $this->model->findOrFail($id);
        $result = $this->getForm($studentCurriculum);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentCurriculum::edit',
            compact('studentCurriculum', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'curriculum_id' => ['required', 'integer'],
        ]);

        try {
            $studentCurriculum = $this->model->findOrFail($id);
            DB::transaction(function () use ($studentCurriculum, $validated) {
                $studentCurriculum->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentCurriculum berhasil diperbarui.',
                $studentCurriculum
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StudentCurriculum.',
                [
                    'exception' => $e,
                    'StudentCurriculum_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentCurriculum gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentCurriculum = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentCurriculum) {
                $studentCurriculum->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentCurriculum berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $studentCurriculum->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentCurriculum.',
                [
                    'exception' => $e,
                    'StudentCurriculum_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentCurriculum gagal dihapus.'
            );
        }
    }
}
