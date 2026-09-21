<?php

namespace App\Modules\ExtracurricularAttendance\Controllers;

use App\DataTables\ExtracurricularAttendanceDataTable;
use App\Http\Controllers\MainController;
use Models\ExtracurricularAttendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ExtracurricularAttendanceController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ExtracurricularAttendance());
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
        ExtracurricularAttendanceDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /extracurricular-attendance?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ExtracurricularAttendance::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $extracurricularAttendance = new ExtracurricularAttendance();
        $result = $this->getForm($extracurricularAttendance);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExtracurricularAttendance::create',
            compact('extracurricularAttendance', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'student_extracurricular_id' => ['required', 'integer'],
            'meeting_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'recorded_by' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ExtracurricularAttendance::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExtracurricularAttendance berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ExtracurricularAttendance.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExtracurricularAttendance gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $extracurricularAttendance = $this->model->findOrFail($id);
        $result = $this->getForm($extracurricularAttendance);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ExtracurricularAttendance::edit',
            compact('extracurricularAttendance', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'student_extracurricular_id' => ['required', 'integer'],
            'meeting_date' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:255'],
            'recorded_by' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $extracurricularAttendance = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $extracurricularAttendance,
                $validated
            ) {
                $extracurricularAttendance->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ExtracurricularAttendance berhasil diperbarui.',
                $extracurricularAttendance
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ExtracurricularAttendance.',
                [
                    'exception' => $e,
                    'ExtracurricularAttendance_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExtracurricularAttendance gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $extracurricularAttendance = $this->model->findOrFail($id);

            DB::transaction(function () use ($extracurricularAttendance) {
                $extracurricularAttendance->delete();
            });

            return $this->successResponse(
                $request,
                'Data ExtracurricularAttendance berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $extracurricularAttendance->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ExtracurricularAttendance.',
                [
                    'exception' => $e,
                    'ExtracurricularAttendance_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ExtracurricularAttendance gagal dihapus.'
            );
        }
    } 
}
