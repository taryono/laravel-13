<?php

namespace App\Modules\StudentAttendance\Controllers;

use App\DataTables\StudentAttendanceDataTable;
use App\Http\Controllers\MainController;
use Models\StudentAttendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class StudentAttendanceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new StudentAttendance());
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
        StudentAttendanceDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /student-attendance?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'StudentAttendance::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $studentAttendance = new StudentAttendance();
        $result = $this->getForm($studentAttendance);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentAttendance::create',
            compact('studentAttendance', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'schedule_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
            'date' => ['required', 'date'],
            'status' => ['required', 'string', 'max:255'],
            'check_in_time' => ['required', 'string', 'max:255'],
            'check_out_time' => ['required', 'string', 'max:255'],
            'late_minutes' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return StudentAttendance::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentAttendance berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan StudentAttendance.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentAttendance gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $studentAttendance = $this->model->findOrFail($id);
        $result = $this->getForm($studentAttendance);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'StudentAttendance::edit',
            compact('studentAttendance', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'schedule_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
            'date' => ['required', 'date'],
            'status' => ['required', 'string', 'max:255'],
            'check_in_time' => ['required', 'string', 'max:255'],
            'check_out_time' => ['required', 'string', 'max:255'],
            'late_minutes' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'verified_by' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $studentAttendance = $this->model->findOrFail($id);
            DB::transaction(function () use ($studentAttendance, $validated) {
                $studentAttendance->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data StudentAttendance berhasil diperbarui.',
                $studentAttendance
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui StudentAttendance.',
                [
                    'exception' => $e,
                    'StudentAttendance_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentAttendance gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $studentAttendance = $this->model->findOrFail($id);

            DB::transaction(function () use ($studentAttendance) {
                $studentAttendance->delete();
            });

            return $this->successResponse(
                $request,
                'Data StudentAttendance berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $studentAttendance->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus StudentAttendance.',
                [
                    'exception' => $e,
                    'StudentAttendance_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data StudentAttendance gagal dihapus.'
            );
        }
    }
}
