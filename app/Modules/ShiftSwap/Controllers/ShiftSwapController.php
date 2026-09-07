<?php

namespace App\Modules\ShiftSwap\Controllers;

use App\DataTables\ShiftSwapDataTable;
use App\Http\Controllers\MainController;
use Models\ShiftSwap;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ShiftSwapController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ShiftSwap());
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
        ShiftSwapDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /shift-swap?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ShiftSwap::index'
        );
    }

    public function create(): View
    {
        $params = [
            'action' => route('shift_swap.store'),
            'method' => 'POST',
            'swap'   => new ShiftSwap(),
            'employees' => Employee::orderBy('name')->get(['id','name']),
            'shifts'    => Shift::where('is_active', 1)->get(),
        ];
        return view('ShiftSwap::create', $params);
    }

    public function store(Request $request)
    {
        $validated = $this->validateSwap($request);

        // Validasi: Tidak boleh tukar dengan diri sendiri
        if ($validated['employee_id_a'] === $validated['employee_id_b']) {
            return $this->errorResponse($request, 'Karyawan tidak boleh bertukar dengan diri sendiri.');
        }

        try {
            $swap = DB::transaction(function () use ($validated) {
                return ShiftSwap::create($validated);
            });
            return $this->successResponse($request, 'Data Pertukaran shift berhasil ditambahkan.', $swap);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan Pertukaran shift.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data Pertukaran shift gagal ditambahkan: ' . $e->getMessage());
        }
    }

    public function edit(ShiftSwap $shiftSwap): View
    {
        $params = [
            'action' => route('shift_swap.update', $shiftSwap),
            'method' => 'PUT',
            'swap'   => $shiftSwap,
            'employees' => Employee::orderBy('name')->get(['id','name']),
            'shifts'    => Shift::where('is_active', 1)->get(),
        ];
        return view('ShiftSwap::edit', $params);
    }

    public function update(Request $request, ShiftSwap $shiftSwap)
    {
        $validated = $this->validateSwap($request);

        if ($validated['employee_id_a'] === $validated['employee_id_b']) {
            return $this->errorResponse($request, 'Karyawan tidak boleh bertukar dengan diri sendiri.');
        }

        try {
            DB::transaction(function () use ($validated, $shiftSwap) {
                $shiftSwap->update($validated);
            });
             return $this->successResponse($request, 'Data Pertukaran shift berhasil update.', $shiftSwap);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan Pertukaran shift.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data Pertukaran shift gagal update: ' . $e->getMessage());
        }
    }

    protected function validateSwap(Request $request): array
    {
        return $request->validate([
            'date'              => 'required|date',
            'employee_id_a'     => 'required|exists:employees,id',
            'original_shift_a'  => 'required|exists:shifts,id',
            'target_shift_a'    => 'required|exists:shifts,id|different:original_shift_a',
            'employee_id_b'     => 'required|exists:employees,id|different:employee_id_a',
            'original_shift_b'  => 'required|exists:shifts,id',
            'target_shift_b'    => 'required|exists:shifts,id|different:original_shift_b',
            'status'            => 'required|in:pending,approved,rejected,cancelled',
            'notes'             => 'nullable|string',
        ], [
            'target_shift_a.different' => 'Shift tujuan A harus berbeda dari shift asli.',
            'employee_id_b.different'  => 'Karyawan B harus berbeda dari Karyawan A.',
        ]);
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $shiftSwap = $this->model->findOrFail($id);

            DB::transaction(function () use ($shiftSwap) {
                $shiftSwap->delete();
            });

            return $this->successResponse(
                $request,
                'Data ShiftSwap berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $shiftSwap->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ShiftSwap.',
                [
                    'exception' => $e,
                    'ShiftSwap_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ShiftSwap gagal dihapus.'
            );
        }
    } 
}
