<?php

namespace App\Modules\EmployeeAttendance\Controllers;

use App\DataTables\EmployeeAttendanceDataTable;
use App\Http\Controllers\MainController;
use Models\EmployeeAttendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EmployeeAttendanceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EmployeeAttendance());
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
        EmployeeAttendanceDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /employee-attendance?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'EmployeeAttendance::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $employeeAttendance = new EmployeeAttendance();
        $result = $this->getForm($employeeAttendance);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmployeeAttendance::create',
            compact('employeeAttendance', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'employee_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'work_schedule_id' => ['required', 'integer'],
            'shift_id' => ['required', 'integer'],
            'attendance_date' => ['required', 'string', 'max:255'],
            'check_in_time' => ['required', 'string', 'max:255'],
            'check_out_time' => ['required', 'string', 'max:255'],
            'scheduled_check_in' => ['required', 'string', 'max:255'],
            'scheduled_check_out' => ['required', 'string', 'max:255'],
            'work_duration_minutes' => ['required', 'string', 'max:255'],
            'late_minutes' => ['required', 'string', 'max:255'],
            'early_leave_minutes' => ['required', 'string', 'max:255'],
            'overtime_minutes' => ['required', 'string', 'max:255'],
            'break_duration_minutes' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'attendance_method' => ['required', 'string', 'max:255'],
            'check_in_location' => ['required', 'string', 'max:255'],
            'check_out_location' => ['required', 'string', 'max:255'],
            'check_in_latitude' => ['required', 'string', 'max:255'],
            'check_in_longitude' => ['required', 'string', 'max:255'],
            'check_out_latitude' => ['required', 'string', 'max:255'],
            'check_out_longitude' => ['required', 'string', 'max:255'],
            'check_in_device_id' => ['required', 'integer'],
            'check_out_device_id' => ['required', 'integer'],
            'check_in_ip' => ['required', 'string', 'max:255'],
            'check_out_ip' => ['required', 'string', 'max:255'],
            'leave_request_id' => ['required', 'integer'],
            'permission_request_id' => ['required', 'integer'],
            'is_corrected' => ['required', 'string', 'max:255'],
            'correction_id' => ['required', 'integer'],
            'notes' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return EmployeeAttendance::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmployeeAttendance berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan EmployeeAttendance.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeAttendance gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $employeeAttendance = $this->model->findOrFail($id);
        $result = $this->getForm($employeeAttendance);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmployeeAttendance::edit',
            compact('employeeAttendance', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'employee_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'work_schedule_id' => ['required', 'integer'],
            'shift_id' => ['required', 'integer'],
            'attendance_date' => ['required', 'string', 'max:255'],
            'check_in_time' => ['required', 'string', 'max:255'],
            'check_out_time' => ['required', 'string', 'max:255'],
            'scheduled_check_in' => ['required', 'string', 'max:255'],
            'scheduled_check_out' => ['required', 'string', 'max:255'],
            'work_duration_minutes' => ['required', 'string', 'max:255'],
            'late_minutes' => ['required', 'string', 'max:255'],
            'early_leave_minutes' => ['required', 'string', 'max:255'],
            'overtime_minutes' => ['required', 'string', 'max:255'],
            'break_duration_minutes' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'attendance_method' => ['required', 'string', 'max:255'],
            'check_in_location' => ['required', 'string', 'max:255'],
            'check_out_location' => ['required', 'string', 'max:255'],
            'check_in_latitude' => ['required', 'string', 'max:255'],
            'check_in_longitude' => ['required', 'string', 'max:255'],
            'check_out_latitude' => ['required', 'string', 'max:255'],
            'check_out_longitude' => ['required', 'string', 'max:255'],
            'check_in_device_id' => ['required', 'integer'],
            'check_out_device_id' => ['required', 'integer'],
            'check_in_ip' => ['required', 'string', 'max:255'],
            'check_out_ip' => ['required', 'string', 'max:255'],
            'leave_request_id' => ['required', 'integer'],
            'permission_request_id' => ['required', 'integer'],
            'is_corrected' => ['required', 'string', 'max:255'],
            'correction_id' => ['required', 'integer'],
            'notes' => ['required', 'string', 'max:255'],
            'approved_by' => ['required', 'string', 'max:255'],
            'approved_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $employeeAttendance = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $employeeAttendance,
                $validated
            ) {
                $employeeAttendance->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmployeeAttendance berhasil diperbarui.',
                $employeeAttendance
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui EmployeeAttendance.',
                [
                    'exception' => $e,
                    'EmployeeAttendance_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeAttendance gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $employeeAttendance = $this->model->findOrFail($id);

            DB::transaction(function () use ($employeeAttendance) {
                $employeeAttendance->delete();
            });

            return $this->successResponse(
                $request,
                'Data EmployeeAttendance berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $employeeAttendance->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus EmployeeAttendance.',
                [
                    'exception' => $e,
                    'EmployeeAttendance_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeAttendance gagal dihapus.'
            );
        }
    } 
}
