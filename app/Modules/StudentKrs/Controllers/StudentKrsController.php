<?php

namespace App\Modules\StudentKrs\Controllers;

use App\DataTables\StudentKrsDataTable;
use App\Http\Controllers\MainController;
use Models\StudentKrs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentKrsController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentKrs());
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
        StudentKrsDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-krs?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentKrs::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentKrs = new StudentKrs();
        $result = $this->getForm($studentKrs);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentKrs::create',
            compact('studentKrs', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'academic_semester_id' => ['required', 'integer'],
            'study_program_id' => ['required', 'integer'],
            'academic_advisor_id' => ['required', 'integer'],
            'total_sks' => ['required', 'string', 'max:255'],
            'max_allowed_sks' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentKrs::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentKrs berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentKrs.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentKrs gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentKrs = $this->model->findOrFail($id);
        $result = $this->getForm($studentKrs);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentKrs::edit',
            compact('studentKrs', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'academic_semester_id' => ['required', 'integer'],
            'study_program_id' => ['required', 'integer'],
            'academic_advisor_id' => ['required', 'integer'],
            'total_sks' => ['required', 'string', 'max:255'],
            'max_allowed_sks' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentKrs = $this->model->findOrFail($id);
            DB::transaction(function () use ($studentKrs, $validated) {
                $studentKrs->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentKrs berhasil diperbarui.',
                $studentKrs
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StudentKrs.',
                [
                    'exception' => $e,
                    'StudentKrs_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentKrs gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentKrs = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentKrs) {
                $studentKrs->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentKrs berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $studentKrs->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentKrs.',
                [
                    'exception' => $e,
                    'StudentKrs_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentKrs gagal dihapus.'
            );
        }
    }
}
