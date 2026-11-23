<?php

namespace App\Modules\SoroganSchedule\Controllers;

use App\DataTables\SoroganScheduleDataTable;
use App\Http\Controllers\MainController;
use Models\SoroganSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class SoroganScheduleController extends MainController
{
    public function __construct()
    {
        parent::__construct(new SoroganSchedule());
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
        SoroganScheduleDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /sorogan-schedule?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'SoroganSchedule::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $soroganSchedule = new SoroganSchedule();
        $result = $this->getForm($soroganSchedule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SoroganSchedule::create',
            compact('soroganSchedule', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pesantren_activity_id' => ['required', 'integer'],
            'kitab_reference_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'day_of_week' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'room_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return SoroganSchedule::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data SoroganSchedule berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan SoroganSchedule.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SoroganSchedule gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $soroganSchedule = $this->model->findOrFail($id);
        $result = $this->getForm($soroganSchedule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'SoroganSchedule::edit',
            compact('soroganSchedule', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'pesantren_activity_id' => ['required', 'integer'],
            'kitab_reference_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'day_of_week' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'room_id' => ['required', 'integer'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $soroganSchedule = $this->model->findOrFail($id);
            DB::transaction(function () use ($soroganSchedule, $validated) {
                $soroganSchedule->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data SoroganSchedule berhasil diperbarui.',
                $soroganSchedule
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui SoroganSchedule.',
                [
                    'exception' => $e,
                    'SoroganSchedule_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SoroganSchedule gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $soroganSchedule = $this->model->findOrFail($id);

            DB::transaction(function () use ($soroganSchedule) {
                $soroganSchedule->delete();
            });

            return $this->successResponse(
                $request,
                'Data SoroganSchedule berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $soroganSchedule->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus SoroganSchedule.',
                [
                    'exception' => $e,
                    'SoroganSchedule_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data SoroganSchedule gagal dihapus.'
            );
        }
    }
}
