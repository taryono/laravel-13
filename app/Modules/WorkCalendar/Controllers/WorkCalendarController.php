<?php

namespace App\Modules\WorkCalendar\Controllers;

use App\DataTables\WorkCalendarDataTable;
use App\Http\Controllers\MainController;
use Models\WorkCalendar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class WorkCalendarController extends MainController
{
    public function __construct()
    {
        parent::__construct(new WorkCalendar());
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
        WorkCalendarDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /work-calendar?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'WorkCalendar::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $workCalendar = new WorkCalendar();
        $result = $this->getForm($workCalendar);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WorkCalendar::create',
            compact('workCalendar', 'forms', 'groupedForms')
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
            'work_days' => ['required', 'string', 'max:255'],
            'work_start_time' => ['required', 'string', 'max:255'],
            'work_end_time' => ['required', 'string', 'max:255'],
            'is_default' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return WorkCalendar::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data WorkCalendar berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan WorkCalendar.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkCalendar gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $workCalendar = $this->model->findOrFail($id);
        $result = $this->getForm($workCalendar);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WorkCalendar::edit',
            compact('workCalendar', 'forms', 'groupedForms')
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
            'work_days' => ['required', 'string', 'max:255'],
            'work_start_time' => ['required', 'string', 'max:255'],
            'work_end_time' => ['required', 'string', 'max:255'],
            'is_default' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $workCalendar = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $workCalendar,
                $validated
            ) {
                $workCalendar->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data WorkCalendar berhasil diperbarui.',
                $workCalendar
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui WorkCalendar.',
                [
                    'exception' => $e,
                    'WorkCalendar_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkCalendar gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $workCalendar = $this->model->findOrFail($id);

            DB::transaction(function () use ($workCalendar) {
                $workCalendar->delete();
            });

            return $this->successResponse(
                $request,
                'Data WorkCalendar berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $workCalendar->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus WorkCalendar.',
                [
                    'exception' => $e,
                    'WorkCalendar_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkCalendar gagal dihapus.'
            );
        }
    } 
}
