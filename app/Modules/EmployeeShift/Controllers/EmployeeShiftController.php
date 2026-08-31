<?php

namespace App\Modules\EmployeeShift\Controllers;

use App\DataTables\EmployeeShiftDataTable;
use App\Http\Controllers\MainController;
use Models\EmployeeShift;
use Models\Employee;
use Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class EmployeeShiftController extends MainController
{
    public function __construct()
    {
        parent::__construct(new EmployeeShift());
    }

    /**
     * Display a listing of employee shifts.
     */
    public function index(Request $request, EmployeeShiftDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Shift::employee-shift.index');
    }

    /**
     * Show the form for creating a new employee shift.
     */
    public function create(): View
    {
        $employeeShift = new EmployeeShift();
        $employees = Employee::where('is_active', true)->orderBy('full_name')->get();
        $shifts = Shift::where('is_active', true)->orderBy('code')->get();
        $days = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 
            4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
        ];

        return view('Shift::employee-shift.edit', compact('employeeShift', 'employees', 'shifts', 'days'));
    }

    /**
     * Store a newly created employee shift.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_id' => 'required|exists:shifts,id',
            'effective_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:effective_date',
            'day_of_week' => 'nullable|integer|min:1|max:7',
            'is_rotational' => 'boolean',
            'rotation_group' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        // Validasi duplikasi
        $exists = EmployeeShift::where('employee_id', $validated['employee_id'])
            ->where('effective_date', $validated['effective_date'])
            ->where('shift_id', $validated['shift_id'])
            ->exists();

        if ($exists) {
            return $this->errorResponse($request, 'Jadwal shift sudah ada untuk tanggal tersebut.');
        }

        try {
            $employeeShift = DB::transaction(function () use ($validated) {
                return EmployeeShift::create($validated);
            });

            return $this->successResponse($request, 'Jadwal shift karyawan berhasil ditambahkan.', $employeeShift);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan jadwal shift.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Jadwal shift karyawan gagal ditambahkan.');
        }
    }

    /**
     * Show the form for editing the specified employee shift.
     */
    public function edit($id): View
    {
        $employeeShift = $this->model->findOrFail($id);
        $employees = Employee::where('is_active', true)->orderBy('full_name')->get();
        $shifts = Shift::where('is_active', true)->orderBy('code')->get();
        $days = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 
            4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
        ];

        return view('Shift::employee-shift.edit', compact('employeeShift', 'employees', 'shifts', 'days'));
    }

    /**
     * Update the specified employee shift.
     */
    public function update(Request $request, $id)
    {
        $employeeShift = $this->model->findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'shift_id' => 'required|exists:shifts,id',
            'effective_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:effective_date',
            'day_of_week' => 'nullable|integer|min:1|max:7',
            'is_rotational' => 'boolean',
            'rotation_group' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($employeeShift, $validated) {
                $employeeShift->update($validated);
            });

            return $this->successResponse($request, 'Jadwal shift karyawan berhasil diperbarui.', $employeeShift);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui jadwal shift.', ['exception' => $e, 'shift_id' => $id]);
            return $this->errorResponse($request, 'Jadwal shift karyawan gagal diperbarui.');
        }
    }

    /**
     * Remove the specified employee shift.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $employeeShift = $this->model->findOrFail($id);

            DB::transaction(function () use ($employeeShift) {
                $employeeShift->delete();
            });

            return $this->successResponse($request, 'Jadwal shift karyawan berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus jadwal shift.', ['exception' => $e, 'shift_id' => $id]);
            return $this->errorResponse($request, 'Jadwal shift karyawan gagal dihapus.');
        }
    }

    /**
     * Get employee's shift for a specific date (API)
     */
    public function getShiftForDate(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
        ]);

        $shift = $this->getEmployeeShift($request->employee_id, $request->date);

        return response()->json([
            'success' => true,
            'data' => $shift ? [
                'id' => $shift->id,
                'code' => $shift->code,
                'name' => $shift->name,
                'start_time' => $shift->start_time,
                'end_time' => $shift->end_time,
            ] : null,
        ]);
    }

    /**
     * Bulk assign shift to multiple employees
     */
    public function bulkAssign(Request $request)
    {
        $validated = $request->validate([
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'exists:employees,id',
            'shift_id' => 'required|exists:shifts,id',
            'effective_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:effective_date',
            'day_of_week' => 'nullable|integer|min:1|max:7',
        ]);

        try {
            $count = 0;
            DB::transaction(function () use ($validated, &$count) {
                foreach ($validated['employee_ids'] as $employeeId) {
                    $data = [
                        'employee_id' => $employeeId,
                        'shift_id' => $validated['shift_id'],
                        'effective_date' => $validated['effective_date'],
                        'end_date' => $validated['end_date'] ?? null,
                        'day_of_week' => $validated['day_of_week'] ?? null,
                    ];

                    // Cek duplikasi, update jika ada
                    EmployeeShift::updateOrCreate(
                        [
                            'employee_id' => $employeeId,
                            'effective_date' => $validated['effective_date'],
                        ],
                        $data
                    );
                    $count++;
                }
            });

            return $this->successResponse($request, "Berhasil mengassign shift ke {$count} karyawan.");
        } catch (Throwable $e) {
            Log::error('Gagal bulk assign shift.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Gagal mengassign shift secara massal.');
        }
    }

    /**
     * Helper: Get employee shift for specific date
     */
    private function getEmployeeShift($employeeId, $date)
    {
        $dateObj = new \DateTime($date);
        $dayOfWeek = $dateObj->format('N'); // 1-7

        // Cari jadwal shift yang berlaku untuk tanggal tersebut
        $employeeShift = EmployeeShift::where('employee_id', $employeeId)
            ->where('effective_date', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $date);
            })
            ->where(function ($query) use ($dayOfWeek) {
                $query->whereNull('day_of_week')
                    ->orWhere('day_of_week', $dayOfWeek);
            })
            ->orderBy('effective_date', 'desc')
            ->first();

        if ($employeeShift) {
            return Shift::find($employeeShift->shift_id);
        }

        return null;
    }
}