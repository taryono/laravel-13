<?php

namespace App\Modules\ScheduledJob\Controllers;

use App\DataTables\ScheduledJobDataTable;
use App\Http\Controllers\MainController;
use Models\ScheduledJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ScheduledJobController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ScheduledJob());
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
        ScheduledJobDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /scheduled-job?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ScheduledJob::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $scheduledJob = new ScheduledJob();
        $result = $this->getForm($scheduledJob);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ScheduledJob::create',
            compact('scheduledJob', 'forms', 'groupedForms')
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
            'command' => ['required', 'string', 'max:255'],
            'schedule' => ['required', 'string', 'max:255'],
            'last_run' => ['required', 'string', 'max:255'],
            'next_run' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'last_error' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ScheduledJob::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ScheduledJob berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ScheduledJob.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ScheduledJob gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $scheduledJob = $this->model->findOrFail($id);
        $result = $this->getForm($scheduledJob);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ScheduledJob::edit',
            compact('scheduledJob', 'forms', 'groupedForms')
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
            'command' => ['required', 'string', 'max:255'],
            'schedule' => ['required', 'string', 'max:255'],
            'last_run' => ['required', 'string', 'max:255'],
            'next_run' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'last_error' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $scheduledJob = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $scheduledJob,
                $validated
            ) {
                $scheduledJob->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ScheduledJob berhasil diperbarui.',
                $scheduledJob
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ScheduledJob.',
                [
                    'exception' => $e,
                    'ScheduledJob_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ScheduledJob gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $scheduledJob = $this->model->findOrFail($id);

            DB::transaction(function () use ($scheduledJob) {
                $scheduledJob->delete();
            });

            return $this->successResponse(
                $request,
                'Data ScheduledJob berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $scheduledJob->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ScheduledJob.',
                [
                    'exception' => $e,
                    'ScheduledJob_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ScheduledJob gagal dihapus.'
            );
        }
    } 
}
