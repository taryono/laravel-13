<?php

namespace App\Modules\EmployeeAttendanceLog\Controllers;

use App\DataTables\EmployeeAttendanceLogDataTable;
use App\Http\Controllers\MainController;
use Models\EmployeeAttendanceLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EmployeeAttendanceLogController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EmployeeAttendanceLog());
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
        EmployeeAttendanceLogDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /employee-attendance-log?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'EmployeeAttendanceLog::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $employeeAttendanceLog = new EmployeeAttendanceLog();
        $result = $this->getForm($employeeAttendanceLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmployeeAttendanceLog::create',
            compact('employeeAttendanceLog', 'forms', 'groupedForms')
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
            'device_id' => ['required', 'integer'],
            'log_type' => ['required', 'string', 'max:255'],
            'log_time' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'string', 'max:255'],
            'longitude' => ['required', 'string', 'max:255'],
            'location_name' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
            'device_identifier' => ['required', 'string', 'max:255'],
            'photo_path' => ['required', 'string', 'max:255'],
            'raw_data' => ['required', 'string', 'max:255'],
            'is_processed' => ['required', 'string', 'max:255'],
            'processed_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return EmployeeAttendanceLog::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmployeeAttendanceLog berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan EmployeeAttendanceLog.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeAttendanceLog gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $employeeAttendanceLog = $this->model->findOrFail($id);
        $result = $this->getForm($employeeAttendanceLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'EmployeeAttendanceLog::edit',
            compact('employeeAttendanceLog', 'forms', 'groupedForms')
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
            'device_id' => ['required', 'integer'],
            'log_type' => ['required', 'string', 'max:255'],
            'log_time' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'string', 'max:255'],
            'longitude' => ['required', 'string', 'max:255'],
            'location_name' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
            'device_identifier' => ['required', 'string', 'max:255'],
            'photo_path' => ['required', 'string', 'max:255'],
            'raw_data' => ['required', 'string', 'max:255'],
            'is_processed' => ['required', 'string', 'max:255'],
            'processed_at' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $employeeAttendanceLog = $this->model->findOrFail($id);
            DB::transaction(function () use ($employeeAttendanceLog, $validated) {
                $employeeAttendanceLog->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data EmployeeAttendanceLog berhasil diperbarui.',
                $employeeAttendanceLog
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui EmployeeAttendanceLog.',
                [
                    'exception' => $e,
                    'EmployeeAttendanceLog_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeAttendanceLog gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $employeeAttendanceLog = $this->model->findOrFail($id);

            DB::transaction(function () use ($employeeAttendanceLog) {
                $employeeAttendanceLog->delete();
            });

            return $this->successResponse(
                $request,
                'Data EmployeeAttendanceLog berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $employeeAttendanceLog->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus EmployeeAttendanceLog.',
                [
                    'exception' => $e,
                    'EmployeeAttendanceLog_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data EmployeeAttendanceLog gagal dihapus.'
            );
        }
    }
}
