<?php

namespace App\Modules\WorkSchedule\Controllers;

use App\DataTables\WorkScheduleDataTable;
use App\Http\Controllers\MainController;
use Models\WorkSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class WorkScheduleController extends MainController
{
    public function __construct()
    {
        parent::__construct(new WorkSchedule());
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
        WorkScheduleDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /work-schedule?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'WorkSchedule::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $workSchedule = new WorkSchedule();
        $result = $this->getForm($workSchedule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WorkSchedule::create',
            compact('workSchedule', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'day_type' => ['required', 'string', 'max:255'],
            'check_in_time' => ['required', 'string', 'max:255'],
            'check_out_time' => ['required', 'string', 'max:255'],
            'break_start_time' => ['required', 'string', 'max:255'],
            'break_end_time' => ['required', 'string', 'max:255'],
            'late_tolerance_minutes' => ['required', 'string', 'max:255'],
            'early_leave_tolerance_minutes' => ['required', 'string', 'max:255'],
            'overtime_threshold_minutes' => ['required', 'string', 'max:255'],
            'is_cross_day' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'effective_from' => ['required', 'string', 'max:255'],
            'effective_until' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return WorkSchedule::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data WorkSchedule berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan WorkSchedule.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkSchedule gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $workSchedule = $this->model->findOrFail($id);
        $result = $this->getForm($workSchedule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WorkSchedule::edit',
            compact('workSchedule', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'day_type' => ['required', 'string', 'max:255'],
            'check_in_time' => ['required', 'string', 'max:255'],
            'check_out_time' => ['required', 'string', 'max:255'],
            'break_start_time' => ['required', 'string', 'max:255'],
            'break_end_time' => ['required', 'string', 'max:255'],
            'late_tolerance_minutes' => ['required', 'string', 'max:255'],
            'early_leave_tolerance_minutes' => ['required', 'string', 'max:255'],
            'overtime_threshold_minutes' => ['required', 'string', 'max:255'],
            'is_cross_day' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'effective_from' => ['required', 'string', 'max:255'],
            'effective_until' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $workSchedule = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $workSchedule,
                $validated
            ) {
                $workSchedule->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data WorkSchedule berhasil diperbarui.',
                $workSchedule
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui WorkSchedule.',
                [
                    'exception' => $e,
                    'WorkSchedule_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkSchedule gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $workSchedule = $this->model->findOrFail($id);

            DB::transaction(function () use ($workSchedule) {
                $workSchedule->delete();
            });

            return $this->successResponse(
                $request,
                'Data WorkSchedule berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $workSchedule->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus WorkSchedule.',
                [
                    'exception' => $e,
                    'WorkSchedule_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkSchedule gagal dihapus.'
            );
        }
    } 
}
