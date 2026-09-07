<?php

namespace App\Modules\Shift\Controllers;

use App\DataTables\ShiftDataTable;
use App\Http\Controllers\MainController;
use Models\Shift;
use Models\ShiftRate;
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
        $params = [
            'action' => 'shift.store',
            'method' => 'POST',
            'shift' => new Shift(),
        ];

        return view('Shift::create', $params);
    }

    /**
     * Store a newly created shift.
     */
    public function store(Request $request)
    {
        $validated = $this->validateShift($request);

        try {
            $shift = DB::transaction(function () use ($validated) {
                // Deteksi otomatis melewati tengah malam
                $validated['crosses_midnight'] = $this->detectCrossMidnight(
                    $validated['start_time'],
                    $validated['end_time']
                );

                // Buat Shift
                $shift = Shift::create($validated);

                // Simpan semua tarif per jenis hari
                $this->saveRates($shift, $validated['rates'] ?? []);

                return $shift;
            });

            return $this->successResponse($request, 'Data shift berhasil ditambahkan.', $shift);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan shift.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data shift gagal ditambahkan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan Form Ubah Shift
     */
    public function edit($id): View
    {
        $shift = $this->model->findOrFail($id);
        $shift->load('shiftRates'); // muat tarif sekaligus
        $params = [
            'action' => ['shift.update', $shift->id],
            'method' => 'PUT',
            'shift'  => $shift,
        ];
        return view('Shift::edit', $params); // atau pakai file create.blade.php yang sama
    }

    /**
     * Perbarui Shift & Tarifnya
     */
    public function update(Request $request, $id)
    {
        $shift = $this->model->findOrFail($id);
        $validated = $this->validateShift($request);

        try {
            DB::transaction(function () use ($validated, $shift) {
                // Deteksi otomatis melewati tengah malam
                $validated['crosses_midnight'] = $this->detectCrossMidnight(
                    $validated['start_time'],
                    $validated['end_time']
                );

                // Perbarui Shift
                $shift->update($validated);

                // Hapus tarif lama & simpan yang baru
                $shift->shiftRates()->delete();
                $this->saveRates($shift, $validated['rates'] ?? []);
            });

            return $this->successResponse($request, 'Data shift berhasil diperbarui.', $shift);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui shift.', ['exception' => $e, 'data' => $validated]);
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
            if ($shift->employee_shifts()->exists()) {
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
    public function show($id): View
    {
        $shift = $this->model->with(['employee_shifts.employee'])->findOrFail($id);
        
        return view('Shift::show', compact('shift'));
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
    /**
     * Validasi Input Shift + Tarif
     */
    protected function validateShift(Request $request): array
    {
        return $request->validate([
            'code'                  => 'required|string|max:50|unique:shifts,code,' . $request->route('shift')?->id,
            'name'                  => 'required|string|max:100',
            'start_time'            => 'required|date_format:H:i',
            'end_time'              => 'required|date_format:H:i',
            'grace_period_start'    => 'nullable|integer|min:0',
            'grace_period_end'      => 'nullable|integer|min:0',
            'break_start'           => 'nullable|date_format:H:i',
            'break_end'             => 'nullable|date_format:H:i',
            'break_duration'        => 'nullable|integer|min:0',
            'overtime_min_threshold'=> 'nullable|integer|min:0',
            'night_shift_start'     => 'nullable|date_format:H:i',
            'is_active'             => 'nullable|boolean',

            // Validasi tarif per hari
            'rates'                             => 'required|array',
            'rates.*.day_type'                  => 'required|in:weekday,saturday,sunday,national_holiday,special_holiday',
            'rates.*.base_rate_per_shift'       => 'required|numeric|min:0',
            'rates.*.overtime_multiplier'       => 'required|numeric|min:0',
            'rates.*.overtime_rate_per_hour'    => 'required|numeric|min:0',
            'rates.*.night_allowance_per_shift' => 'required|numeric|min:0',
            'rates.*.meal_allowance_per_shift'  => 'required|numeric|min:0',
            'rates.*.transport_allowance_per_shift' => 'required|numeric|min:0',
        ]);
    }

    /**
     * Deteksi apakah shift melewati tengah malam
     */
    protected function detectCrossMidnight(string $start, string $end): bool
    {
        return strtotime($end) < strtotime($start);
    }

    /**
     * Simpan Tarif Shift
     */
    protected function saveRates(Shift $shift, array $rates): void
    {
        foreach ($rates as $rate) {
            ShiftRate::create([
                'shift_id'                 => $shift->id,
                'day_type'                 => $rate['day_type'],
                'base_rate_per_shift'      => $rate['base_rate_per_shift'],
                'overtime_multiplier'      => $rate['overtime_multiplier'],
                'overtime_rate_per_hour'   => $rate['overtime_rate_per_hour'],
                'night_allowance_per_shift'=> $rate['night_allowance_per_shift'],
                'meal_allowance_per_shift' => $rate['meal_allowance_per_shift'],
                'transport_allowance_per_shift' => $rate['transport_allowance_per_shift'],
                'is_active'                => true,
            ]);
        }
    }
}