<?php

namespace App\Modules\ExtracurricularSchedule\Controllers;

use App\DataTables\ExtracurricularScheduleDataTable;
use App\Http\Controllers\MainController;
use Models\ExtracurricularSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ExtracurricularScheduleController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ExtracurricularSchedule());
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
        ExtracurricularScheduleDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /extracurricular-schedule?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ExtracurricularSchedule::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $extracurricularSchedule = new ExtracurricularSchedule();
        $result = $this->getForm($extracurricularSchedule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExtracurricularSchedule::create',
            compact('extracurricularSchedule', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'extracurricular_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'coach_id' => ['required', 'integer'],
            'room_id' => ['required', 'integer'],
            'building_id' => ['required', 'integer'],
            'schedule_type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'day_of_week' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'string', 'max:255'],
            'recurrence_type' => ['required', 'string', 'max:255'],
            'recurrence_until' => ['required', 'string', 'max:255'],
            'specific_date' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'string', 'max:255'],
            'registered' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'effective_from' => ['required', 'string', 'max:255'],
            'effective_until' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ExtracurricularSchedule::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExtracurricularSchedule berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ExtracurricularSchedule.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExtracurricularSchedule gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $extracurricularSchedule = $this->model->findOrFail($id);
        $result = $this->getForm($extracurricularSchedule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExtracurricularSchedule::edit',
            compact('extracurricularSchedule', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'extracurricular_id' => ['required', 'integer'],
            'organization_id' => ['required', 'integer'],
            'coach_id' => ['required', 'integer'],
            'room_id' => ['required', 'integer'],
            'building_id' => ['required', 'integer'],
            'schedule_type' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'day_of_week' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'string', 'max:255'],
            'recurrence_type' => ['required', 'string', 'max:255'],
            'recurrence_until' => ['required', 'string', 'max:255'],
            'specific_date' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'string', 'max:255'],
            'registered' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'is_mandatory' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'effective_from' => ['required', 'string', 'max:255'],
            'effective_until' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $extracurricularSchedule = $this->model->findOrFail($id);
            DB::transaction(function () use ($extracurricularSchedule, $validated) {
                $extracurricularSchedule->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExtracurricularSchedule berhasil diperbarui.',
                $extracurricularSchedule
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ExtracurricularSchedule.',
                [
                    'exception' => $e,
                    'ExtracurricularSchedule_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExtracurricularSchedule gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $extracurricularSchedule = $this->model->findOrFail($id);

            DB::transaction(function () use ($extracurricularSchedule) {
                $extracurricularSchedule->delete();
            });

            return $this->successResponse(
                $request,
                'Data ExtracurricularSchedule berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $extracurricularSchedule->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ExtracurricularSchedule.',
                [
                    'exception' => $e,
                    'ExtracurricularSchedule_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExtracurricularSchedule gagal dihapus.'
            );
        }
    }
}
