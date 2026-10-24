<?php

namespace App\Modules\MasteryTracking\Controllers;

use App\DataTables\MasteryTrackingDataTable;
use App\Http\Controllers\MainController;
use Models\MasteryTracking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class MasteryTrackingController extends MainController
{
    public function __construct()
    {
        parent::__construct(new MasteryTracking());
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
        MasteryTrackingDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /mastery-tracking?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'MasteryTracking::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $masteryTracking = new MasteryTracking();
        $result = $this->getForm($masteryTracking);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'MasteryTracking::create',
            compact('masteryTracking', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'curriculum_objective_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'mastered_at' => ['required', 'string', 'max:255'],
            'observation_notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return MasteryTracking::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data MasteryTracking berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan MasteryTracking.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MasteryTracking gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $masteryTracking = $this->model->findOrFail($id);
        $result = $this->getForm($masteryTracking);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'MasteryTracking::edit',
            compact('masteryTracking', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'curriculum_objective_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'mastered_at' => ['required', 'string', 'max:255'],
            'observation_notes' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $masteryTracking = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $masteryTracking,
                $validated
            ) {
                $masteryTracking->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data MasteryTracking berhasil diperbarui.',
                $masteryTracking
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui MasteryTracking.',
                [
                    'exception' => $e,
                    'MasteryTracking_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MasteryTracking gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $masteryTracking = $this->model->findOrFail($id);

            DB::transaction(function () use ($masteryTracking) {
                $masteryTracking->delete();
            });

            return $this->successResponse(
                $request,
                'Data MasteryTracking berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $masteryTracking->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus MasteryTracking.',
                [
                    'exception' => $e,
                    'MasteryTracking_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MasteryTracking gagal dihapus.'
            );
        }
    } 
}
