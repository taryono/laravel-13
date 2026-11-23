<?php

namespace App\Modules\AttendanceCorrection\Controllers;

use App\DataTables\AttendanceCorrectionDataTable;
use App\Http\Controllers\MainController;
use Models\AttendanceCorrection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AttendanceCorrectionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AttendanceCorrection());
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
        AttendanceCorrectionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /attendance-correction?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AttendanceCorrection::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $attendanceCorrection = new AttendanceCorrection();
        $result = $this->getForm($attendanceCorrection);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AttendanceCorrection::create',
            compact('attendanceCorrection', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_attendance_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'correction_type' => ['required', 'string', 'max:255'],
            'original_check_in' => ['required', 'string', 'max:255'],
            'original_check_out' => ['required', 'string', 'max:255'],
            'original_status' => ['required', 'string', 'max:255'],
            'requested_check_in' => ['required', 'string', 'max:255'],
            'requested_check_out' => ['required', 'string', 'max:255'],
            'requested_status' => ['required', 'string', 'max:255'],
            'requested_overtime_minutes' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'attachment_path' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'rejected_by' => ['required', 'string', 'max:255'],
            'rejected_at' => ['required', 'string', 'max:255'],
            'rejection_reason' => ['required', 'string', 'max:255'],
            'applied_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AttendanceCorrection::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AttendanceCorrection berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AttendanceCorrection.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AttendanceCorrection gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $attendanceCorrection = $this->model->findOrFail($id);
        $result = $this->getForm($attendanceCorrection);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AttendanceCorrection::edit',
            compact('attendanceCorrection', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_attendance_id' => ['required', 'integer'],
            'employee_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'correction_type' => ['required', 'string', 'max:255'],
            'original_check_in' => ['required', 'string', 'max:255'],
            'original_check_out' => ['required', 'string', 'max:255'],
            'original_status' => ['required', 'string', 'max:255'],
            'requested_check_in' => ['required', 'string', 'max:255'],
            'requested_check_out' => ['required', 'string', 'max:255'],
            'requested_status' => ['required', 'string', 'max:255'],
            'requested_overtime_minutes' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'attachment_path' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'rejected_by' => ['required', 'string', 'max:255'],
            'rejected_at' => ['required', 'string', 'max:255'],
            'rejection_reason' => ['required', 'string', 'max:255'],
            'applied_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $attendanceCorrection = $this->model->findOrFail($id);
            DB::transaction(function () use ($attendanceCorrection, $validated) {
                $attendanceCorrection->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AttendanceCorrection berhasil diperbarui.',
                $attendanceCorrection
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AttendanceCorrection.',
                [
                    'exception' => $e,
                    'AttendanceCorrection_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AttendanceCorrection gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $attendanceCorrection = $this->model->findOrFail($id);

            DB::transaction(function () use ($attendanceCorrection) {
                $attendanceCorrection->delete();
            });

            return $this->successResponse(
                $request,
                'Data AttendanceCorrection berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $attendanceCorrection->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AttendanceCorrection.',
                [
                    'exception' => $e,
                    'AttendanceCorrection_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AttendanceCorrection gagal dihapus.'
            );
        }
    }
}
