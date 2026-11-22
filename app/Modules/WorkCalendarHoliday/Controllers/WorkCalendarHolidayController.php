<?php

namespace App\Modules\WorkCalendarHoliday\Controllers;

use App\DataTables\WorkCalendarHolidayDataTable;
use App\Http\Controllers\MainController;
use Models\WorkCalendarHoliday;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class WorkCalendarHolidayController extends MainController
{
    public function __construct()
    {
        parent::__construct(new WorkCalendarHoliday());
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
        WorkCalendarHolidayDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /work-calendar-holiday?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'WorkCalendarHoliday::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $workCalendarHoliday = new WorkCalendarHoliday();
        $result = $this->getForm($workCalendarHoliday);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WorkCalendarHoliday::create',
            compact('workCalendarHoliday', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'work_calendar_id' => ['required', 'integer'],
            'holiday_id' => ['required', 'integer'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return WorkCalendarHoliday::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data WorkCalendarHoliday berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan WorkCalendarHoliday.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkCalendarHoliday gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $workCalendarHoliday = $this->model->findOrFail($id);
        $result = $this->getForm($workCalendarHoliday);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WorkCalendarHoliday::edit',
            compact('workCalendarHoliday', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'work_calendar_id' => ['required', 'integer'],
            'holiday_id' => ['required', 'integer'],
        ]);

        try {
            $workCalendarHoliday = $this->model->findOrFail($id);
            DB::transaction(function () use ($workCalendarHoliday, $validated) {
                $workCalendarHoliday->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data WorkCalendarHoliday berhasil diperbarui.',
                $workCalendarHoliday
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui WorkCalendarHoliday.',
                [
                    'exception' => $e,
                    'WorkCalendarHoliday_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkCalendarHoliday gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $workCalendarHoliday = $this->model->findOrFail($id);

            DB::transaction(function () use ($workCalendarHoliday) {
                $workCalendarHoliday->delete();
            });

            return $this->successResponse(
                $request,
                'Data WorkCalendarHoliday berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $workCalendarHoliday->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus WorkCalendarHoliday.',
                [
                    'exception' => $e,
                    'WorkCalendarHoliday_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkCalendarHoliday gagal dihapus.'
            );
        }
    }
}
