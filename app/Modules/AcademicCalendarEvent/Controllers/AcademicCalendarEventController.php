<?php

namespace App\Modules\AcademicCalendarEvent\Controllers;

use App\DataTables\AcademicCalendarEventDataTable;
use App\Http\Controllers\MainController;
use Models\AcademicCalendarEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AcademicCalendarEventController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AcademicCalendarEvent());
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
        AcademicCalendarEventDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /academic-calendar-event?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AcademicCalendarEvent::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $organizationId = auth()->user()->organization_id; // sesuaikan logikamu
        $schoolId = auth()->user()->school_id;
        $academicYearId = session('academic_year_id'); // sesuaikan

        return view('AcademicCalendarEvent::create', compact(
            'organizationId',
            'schoolId',
            'academicYearId'
        ));
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'event_type' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'is_holiday' => ['required', 'string', 'max:255'],
            'is_all_day' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AcademicCalendarEvent::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AcademicCalendarEvent berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AcademicCalendarEvent.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AcademicCalendarEvent gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $academicCalendarEvent = $this->model->findOrFail($id);
        $organizationId = auth()->user()->organization_id;
        $schoolId = auth()->user()->school_id;
        $academicYearId = session('academic_year_id');

        return view(
            'AcademicCalendarEvent::edit',
            compact('academicCalendarEvent', 'organizationId', 'schoolId', 'academicYearId')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'event_type' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'is_holiday' => ['required', 'string', 'max:255'],
            'is_all_day' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:255'],
            'attachment_url' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $academicCalendarEvent = $this->model->findOrFail($id);
            DB::transaction(function () use ($academicCalendarEvent, $validated) {
                $academicCalendarEvent->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AcademicCalendarEvent berhasil diperbarui.',
                $academicCalendarEvent
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AcademicCalendarEvent.',
                [
                    'exception' => $e,
                    'AcademicCalendarEvent_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AcademicCalendarEvent gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $academicCalendarEvent = $this->model->findOrFail($id);

            DB::transaction(function () use ($academicCalendarEvent) {
                $academicCalendarEvent->delete();
            });

            return $this->successResponse(
                $request,
                'Data AcademicCalendarEvent berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $academicCalendarEvent->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AcademicCalendarEvent.',
                [
                    'exception' => $e,
                    'AcademicCalendarEvent_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AcademicCalendarEvent gagal dihapus.'
            );
        }
    }
}
