<?php

namespace App\Modules\ShiftRate\Controllers;

use App\DataTables\ShiftRateDataTable;
use App\Http\Controllers\MainController;
use Models\ShiftRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ShiftRateController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ShiftRate());
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
        ShiftRateDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /shift-rate?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ShiftRate::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $shiftRate = new ShiftRate();
        $result = $this->getForm($shiftRate);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ShiftRate::create',
            compact('shiftRate', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'day_type' => ['required', 'in:weekday,saturday,sunday,national_holiday,special_holiday'],
            'base_rate' => 'required|numeric|min:0',
            'overtime_rate_per_hour' => 'required|numeric|min:0',
            'overtime_multiplier' => 'required|numeric|min:1',
            'night_allowance' => 'nullable|numeric|min:0',
            'meal_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
        ], [
            'shift_id.required' => 'Shift wajib dipilih.',
            'day_type.required' => 'Jenis hari wajib dipilih.',
            'day_type.in' => 'Jenis hari tidak valid.',
            'base_rate.required' => 'Upah dasar wajib diisi.',
        ]);

        // Validasi unik kombinasi shift + day_type
        $exists = ShiftRate::where('shift_id', $validated['shift_id'])
            ->where('day_type', $validated['day_type'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'day_type' => 'Rate untuk shift dan jenis hari ini sudah ada. Silakan edit data yang sudah ada.'
            ])->withInput();
        }

        try {
            $model = DB::transaction(function () use ($validated) {
                return ShiftRate::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ShiftRate berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ShiftRate.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ShiftRate gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $shiftRate = $this->model->findOrFail($id);
        $result = $this->getForm($shiftRate);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ShiftRate::edit',
            compact('shiftRate', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {


        try {
            $shiftRate = $this->model->findOrFail($id);
            $validated = $request->validate([
                'shift_id' => 'required|exists:shifts,id',
                'day_type' => ['required', 'in:weekday,saturday,sunday,national_holiday,special_holiday'],
                'base_rate' => 'required|numeric|min:0',
                'overtime_rate_per_hour' => 'required|numeric|min:0',
                'overtime_multiplier' => 'required|numeric|min:1',
                'night_allowance' => 'nullable|numeric|min:0',
                'meal_allowance' => 'nullable|numeric|min:0',
                'transport_allowance' => 'nullable|numeric|min:0',
                'is_active' => 'required|boolean',
            ]);

            // Validasi unik (kecuali untuk record yang sedang diedit)
            $exists = ShiftRate::where('shift_id', $validated['shift_id'])
                ->where('day_type', $validated['day_type'])
                ->where('id', '!=', $shift_rate->id)
                ->exists();

            if ($exists) {
                return back()->withErrors([
                    'day_type' => 'Rate untuk kombinasi shift dan jenis hari ini sudah ada.'
                ])->withInput();
            }
            DB::transaction(function () use ($shiftRate, $validated) {
                $shiftRate->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ShiftRate berhasil diperbarui.',
                $shiftRate
            );

        } catch (Throwable $e) {

            // FIX: pakai $id mentah untuk logging, BUKAN
            // $shiftRate->getKey(), karena kalau findOrFail() di atas
            // yang melempar exception, variabel $shiftRate belum
            // ter-assign -- referensi ke ->getKey() di sini akan memicu
            // error baru di dalam blok catch.
            Log::error(
                'Gagal memperbarui ShiftRate.',
                [
                    'exception' => $e,
                    'ShiftRate_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ShiftRate gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $shiftRate = $this->model->findOrFail($id);

            DB::transaction(function () use ($shiftRate) {
                $shiftRate->delete();
            });

            return $this->successResponse(
                $request,
                'Data ShiftRate berhasil dihapus.'
            );

        } catch (Throwable $e) {

            // FIX: sama seperti update(), pakai $id mentah, bukan
            // $shiftRate->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ShiftRate.',
                [
                    'exception' => $e,
                    'ShiftRate_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ShiftRate gagal dihapus.'
            );
        }
    }
}
