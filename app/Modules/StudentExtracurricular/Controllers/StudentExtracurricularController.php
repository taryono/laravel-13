<?php

namespace App\Modules\StudentExtracurricular\Controllers;

use App\DataTables\StudentExtracurricularDataTable;
use App\Http\Controllers\MainController;
use Models\StudentExtracurricular;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentExtracurricularController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentExtracurricular());
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
        StudentExtracurricularDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-extracurricular?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentExtracurricular::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentExtracurricular = new StudentExtracurricular();
        $result = $this->getForm($studentExtracurricular);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentExtracurricular::create',
            compact('studentExtracurricular', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'extracurricular_id' => ['required', 'integer'],
            'enrollment_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'grade_letter' => ['required', 'string', 'max:255'],
            'predicate' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'certificate_url' => ['required', 'string', 'max:255'],
            'achievement_notes' => ['required', 'string', 'max:255'],
            'evaluated_by' => ['required', 'string', 'max:255'],
            'evaluated_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentExtracurricular::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentExtracurricular berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentExtracurricular.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentExtracurricular gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentExtracurricular = $this->model->findOrFail($id);
        $result = $this->getForm($studentExtracurricular);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentExtracurricular::edit',
            compact('studentExtracurricular', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'extracurricular_id' => ['required', 'integer'],
            'enrollment_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'grade_letter' => ['required', 'string', 'max:255'],
            'predicate' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'certificate_url' => ['required', 'string', 'max:255'],
            'achievement_notes' => ['required', 'string', 'max:255'],
            'evaluated_by' => ['required', 'string', 'max:255'],
            'evaluated_at' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentExtracurricular = $this->model->findOrFail($id);
            DB::transaction(function () use ($studentExtracurricular, $validated) {
                $studentExtracurricular->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentExtracurricular berhasil diperbarui.',
                $studentExtracurricular
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StudentExtracurricular.',
                [
                    'exception' => $e,
                    'StudentExtracurricular_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentExtracurricular gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentExtracurricular = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentExtracurricular) {
                $studentExtracurricular->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentExtracurricular berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $studentExtracurricular->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentExtracurricular.',
                [
                    'exception' => $e,
                    'StudentExtracurricular_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentExtracurricular gagal dihapus.'
            );
        }
    }
}
