<?php

namespace App\Modules\Grade\Controllers;

use App\DataTables\GradeDataTable;
use App\Http\Controllers\MainController;
use Models\Grade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class GradeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Grade());
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
        GradeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /grade?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Grade::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $grade = new Grade();
        $result = $this->getForm($grade);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Grade::create',
            compact('grade', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_classroom_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'period_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'score' => ['required', 'string', 'max:255'],
            'grade_letter' => ['required', 'string', 'max:255'],
            'predicate' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'assessment_type' => ['required', 'string', 'max:255'],
            'assessment_category' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'string', 'max:255'],
            'is_final' => ['required', 'string', 'max:255'],
            'is_counted_in_report' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Grade::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Grade berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Grade.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Grade gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $grade = $this->model->findOrFail($id);
        $result = $this->getForm($grade);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Grade::edit',
            compact('grade', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_classroom_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'period_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'score' => ['required', 'string', 'max:255'],
            'grade_letter' => ['required', 'string', 'max:255'],
            'predicate' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'assessment_type' => ['required', 'string', 'max:255'],
            'assessment_category' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'string', 'max:255'],
            'is_final' => ['required', 'string', 'max:255'],
            'is_counted_in_report' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $grade = $this->model->findOrFail($id);
            DB::transaction(function () use ($grade, $validated) {
                $grade->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Grade berhasil diperbarui.',
                $grade
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Grade.',
                [
                    'exception' => $e,
                    'Grade_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Grade gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $grade = $this->model->findOrFail($id);

            DB::transaction(function () use ($grade) {
                $grade->delete();
            });

            return $this->successResponse(
                $request,
                'Data Grade berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $grade->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Grade.',
                [
                    'exception' => $e,
                    'Grade_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Grade gagal dihapus.'
            );
        }
    }
}
