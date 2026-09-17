<?php

namespace App\Modules\TestSchedule\Controllers;

use App\DataTables\TestScheduleDataTable;
use App\Http\Controllers\MainController;
use Models\TestSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TestScheduleController extends MainController
{
    public function __construct()
    {
        parent::__construct(new TestSchedule());
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
        TestScheduleDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /test-schedule?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'TestSchedule::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $testSchedule = new TestSchedule();
        $result = $this->getForm($testSchedule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TestSchedule::create',
            compact('testSchedule', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'admission_id' => ['required', 'integer'],
            'admission_selection_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'test_type' => ['required', 'string', 'max:255'],
            'test_method' => ['required', 'string', 'max:255'],
            'test_date' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'string', 'max:255'],
            'registration_start' => ['required', 'string', 'max:255'],
            'registration_end' => ['required', 'string', 'max:255'],
            'room_id' => ['required', 'integer'],
            'building_id' => ['required', 'integer'],
            'location_detail' => ['required', 'string', 'max:255'],
            'online_meeting_url' => ['required', 'string', 'max:255'],
            'online_meeting_id' => ['required', 'integer'],
            'online_password' => ['required', 'string', 'max:255'],
            'cbt_session_id' => ['required', 'integer'],
            'exam_package_id' => ['required', 'integer'],
            'quota' => ['required', 'string', 'max:255'],
            'booked_count' => ['required', 'string', 'max:255'],
            'min_participants' => ['required', 'string', 'max:255'],
            'supervisor_id' => ['required', 'integer'],
            'coordinator_id' => ['required', 'integer'],
            'proctor_ids' => ['required', 'string', 'max:255'],
            'allow_self_register' => ['required', 'string', 'max:255'],
            'allow_reschedule' => ['required', 'string', 'max:255'],
            'late_tolerance_min' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'passing_score' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'score_input_deadline' => ['required', 'string', 'max:255'],
            'result_announcement' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'attachments' => ['required', 'string', 'max:255'],
            'settings' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return TestSchedule::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data TestSchedule berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan TestSchedule.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TestSchedule gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $testSchedule = $this->model->findOrFail($id);
        $result = $this->getForm($testSchedule);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TestSchedule::edit',
            compact('testSchedule', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'admission_id' => ['required', 'integer'],
            'admission_selection_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'test_type' => ['required', 'string', 'max:255'],
            'test_method' => ['required', 'string', 'max:255'],
            'test_date' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'string', 'max:255'],
            'registration_start' => ['required', 'string', 'max:255'],
            'registration_end' => ['required', 'string', 'max:255'],
            'room_id' => ['required', 'integer'],
            'building_id' => ['required', 'integer'],
            'location_detail' => ['required', 'string', 'max:255'],
            'online_meeting_url' => ['required', 'string', 'max:255'],
            'online_meeting_id' => ['required', 'integer'],
            'online_password' => ['required', 'string', 'max:255'],
            'cbt_session_id' => ['required', 'integer'],
            'exam_package_id' => ['required', 'integer'],
            'quota' => ['required', 'string', 'max:255'],
            'booked_count' => ['required', 'string', 'max:255'],
            'min_participants' => ['required', 'string', 'max:255'],
            'supervisor_id' => ['required', 'integer'],
            'coordinator_id' => ['required', 'integer'],
            'proctor_ids' => ['required', 'string', 'max:255'],
            'allow_self_register' => ['required', 'string', 'max:255'],
            'allow_reschedule' => ['required', 'string', 'max:255'],
            'late_tolerance_min' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'passing_score' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'string', 'max:255'],
            'score_input_deadline' => ['required', 'string', 'max:255'],
            'result_announcement' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'attachments' => ['required', 'string', 'max:255'],
            'settings' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $testSchedule = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $testSchedule,
                $validated
            ) {
                $testSchedule->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data TestSchedule berhasil diperbarui.',
                $testSchedule
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui TestSchedule.',
                [
                    'exception' => $e,
                    'TestSchedule_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TestSchedule gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $testSchedule = $this->model->findOrFail($id);

            DB::transaction(function () use ($testSchedule) {
                $testSchedule->delete();
            });

            return $this->successResponse(
                $request,
                'Data TestSchedule berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $testSchedule->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus TestSchedule.',
                [
                    'exception' => $e,
                    'TestSchedule_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TestSchedule gagal dihapus.'
            );
        }
    } 
}
