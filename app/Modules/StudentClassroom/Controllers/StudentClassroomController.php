<?php

namespace App\Modules\StudentClassroom\Controllers;

use App\DataTables\StudentClassroomDataTable;
use App\Http\Controllers\MainController;
use Models\StudentClassroom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentClassroomController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentClassroom());
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
        StudentClassroomDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-classroom?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentClassroom::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentClassroom = new StudentClassroom();
        $result = $this->getForm($studentClassroom);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentClassroom::create',
            compact('studentClassroom', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'student_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'enrollment_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentClassroom::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentClassroom berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentClassroom.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentClassroom gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentClassroom = $this->model->findOrFail($id);
        $result = $this->getForm($studentClassroom);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentClassroom::edit',
            compact('studentClassroom', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'student_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'enrollment_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentClassroom = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $studentClassroom,
                $validated
            ) {
                $studentClassroom->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentClassroom berhasil diperbarui.',
                $studentClassroom
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui StudentClassroom.',
                [
                    'exception' => $e,
                    'StudentClassroom_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentClassroom gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentClassroom = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentClassroom) {
                $studentClassroom->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentClassroom berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $studentClassroom->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentClassroom.',
                [
                    'exception' => $e,
                    'StudentClassroom_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentClassroom gagal dihapus.'
            );
        }
    } 
}
