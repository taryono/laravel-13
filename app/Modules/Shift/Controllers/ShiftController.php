<?php

namespace App\Modules\Shift\Controllers;

use App\DataTables\ShiftDataTable;
use App\Http\Controllers\MainController;
use App\Models\Shift;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ShiftController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Shift());
    }

    /**
     * Display a listing of shifts.
     */
    public function index(Request $request, ShiftDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Shift::index');
    }

    /**
     * Show the form for creating a new shift.
     */
    public function create(): View
    {
        $shift = new Shift();
        $result = $this->getForm($shift);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Shift::edit', compact('shift', 'forms', 'groupedForms'));
    }

    /**
     * Store a newly created shift.
     */
    public function store(Request $request)
    {
        $validated = $this->validateShift($request);

        try {
            $shift = DB::transaction(function () use ($validated) {
                $validated['is_active'] = $validated['is_active'] ?? true;
                $validated['night_shift'] = $validated['night_shift'] ?? false;
                return Shift::create($validated);
            });

            return $this->successResponse($request, 'Data shift berhasil ditambahkan.', $shift);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan shift.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data shift gagal ditambahkan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified shift.
     */
    public function edit($id): View
    {
        $shift = $this->model->findOrFail($id);
        $result = $this->getForm($shift);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Shift::edit', compact('shift', 'forms', 'groupedForms'));
    }

    /**
     * Update the specified shift.
     */
    public function update(Request $request, $id)
    {
        $shift = $this->model->findOrFail($id);
        $validated = $this->validateShift($request, $id);

        try {
            DB::transaction(function () use ($shift, $validated) {
                $shift->update($validated);
            });

            return $this->successResponse($request, 'Data shift berhasil diperbarui.', $shift);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui shift.', ['exception' => $e, 'shift_id' => $id]);
            return $this->errorResponse($request, 'Data shift gagal diperbarui: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified shift.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $shift = $this->model->findOrFail($id);

            // Cek apakah shift sedang digunakan
            if ($shift->employeeShifts()->exists()) {
                return $this->errorResponse($request, 'Shift tidak dapat dihapus karena masih digunakan oleh karyawan.');
            }

            DB::transaction(function () use ($shift) {
                $shift->delete();
            });

            return $this->successResponse($request, 'Data shift berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus shift.', ['exception' => $e, 'shift_id' => $id]);
            return $this->errorResponse($request, 'Data shift gagal dihapus: ' . $e->getMessage());
        }
    }

    /**
     * Show shift details (employees using this shift)
     */
    public function detail($id): View
    {
        $shift = $this->model->with(['employeeShifts.employee'])->findOrFail($id);
        
        return view('Shift::detail', compact('shift'));
    }

    /**
     * Get shift options for dropdown (AJAX)
     */
    public function options(Request $request): JsonResponse
    {
        $search = $request->get('q');
        $shifts = Shift::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('code', 'LIKE', "%{$search}%");
        })
        ->where('is_active', true)
        ->limit(20)
        ->get(['id', 'code', 'name']);

        return response()->json($shifts);
    }

    /**
     * Validate shift data
     */
    private function validateShift(Request $request, $id = null): array
    {
        $rules = [
            'code' => 'required|string|max:50|unique:shifts,code' . ($id ? ',' . $id : ''),
            'name' => 'required|string|max:100',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|different:start_time',
            'grace_period_start' => 'nullable|integer|min:0|max:120',
            'grace_period_end' => 'nullable|integer|min:0|max:120',
            'break_start' => 'nullable|date_format:H:i',
            'break_end' => 'nullable|date_format:H:i|after:break_start',
            'break_duration' => 'nullable|integer|min:0',
            'overtime_threshold' => 'nullable|integer|min:0',
            'overtime_rate' => 'nullable|numeric|min:0|max:10',
            'night_shift' => 'boolean',
            'night_shift_start' => 'nullable|date_format:H:i',
            'is_active' => 'boolean',
        ];

        $validated = $request->validate($rules);

        // Jika night_shift aktif, night_shift_start wajib diisi
        if (($request->boolean('night_shift') || $validated['night_shift']) && empty($validated['night_shift_start'])) {
            $validated['night_shift_start'] = '22:00'; // Default
        }

        return $validated;
    }
}