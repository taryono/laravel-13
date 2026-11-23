<?php

namespace App\Modules\AdmissionTestSchedule\Controllers;

use App\DataTables\AdmissionTestScheduleDataTable;
use App\Http\Controllers\MainController;
use Models\AdmissionTestSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AdmissionTestScheduleController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AdmissionTestSchedule());
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
        AdmissionTestScheduleDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /admission-test-schedule?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AdmissionTestSchedule::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $admissionTestSchedule = new AdmissionTestSchedule();
        $result = $this->getForm($admissionTestSchedule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionTestSchedule::create',
            compact('admissionTestSchedule', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'test_date' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'string', 'max:255'],
            'registered' => ['required', 'string', 'max:255'],
            'attended' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'online_meeting_url' => ['required', 'string', 'max:255'],
            'instructions' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_online' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AdmissionTestSchedule::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionTestSchedule berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AdmissionTestSchedule.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionTestSchedule gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $admissionTestSchedule = $this->model->findOrFail($id);
        $result = $this->getForm($admissionTestSchedule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AdmissionTestSchedule::edit',
            compact('admissionTestSchedule', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'test_date' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'string', 'max:255'],
            'quota' => ['required', 'string', 'max:255'],
            'registered' => ['required', 'string', 'max:255'],
            'attended' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'online_meeting_url' => ['required', 'string', 'max:255'],
            'instructions' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_online' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $admissionTestSchedule = $this->model->findOrFail($id);
            DB::transaction(function () use ($admissionTestSchedule, $validated) {
                $admissionTestSchedule->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AdmissionTestSchedule berhasil diperbarui.',
                $admissionTestSchedule
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AdmissionTestSchedule.',
                [
                    'exception' => $e,
                    'AdmissionTestSchedule_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionTestSchedule gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $admissionTestSchedule = $this->model->findOrFail($id);

            DB::transaction(function () use ($admissionTestSchedule) {
                $admissionTestSchedule->delete();
            });

            return $this->successResponse(
                $request,
                'Data AdmissionTestSchedule berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $admissionTestSchedule->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AdmissionTestSchedule.',
                [
                    'exception' => $e,
                    'AdmissionTestSchedule_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AdmissionTestSchedule gagal dihapus.'
            );
        }
    }
}
