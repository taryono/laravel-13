<?php

namespace App\Modules\TimetableConstraint\Controllers;

use App\DataTables\TimetableConstraintDataTable;
use App\Http\Controllers\MainController;
use Models\TimetableConstraint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TimetableConstraintController extends MainController
{
    public function __construct()
    {
        parent::__construct(new TimetableConstraint());
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
        TimetableConstraintDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /timetable-constraint?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'TimetableConstraint::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $timetableConstraint = new TimetableConstraint();
        $result = $this->getForm($timetableConstraint);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TimetableConstraint::create',
            compact('timetableConstraint', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'day_of_week' => ['required', 'string', 'max:255'],
            'time_slot_start' => ['required', 'string', 'max:255'],
            'time_slot_end' => ['required', 'string', 'max:255'],
            'constraint_type' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return TimetableConstraint::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data TimetableConstraint berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan TimetableConstraint.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TimetableConstraint gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $timetableConstraint = $this->model->findOrFail($id);
        $result = $this->getForm($timetableConstraint);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TimetableConstraint::edit',
            compact('timetableConstraint', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'teacher_id' => ['required', 'integer'],
            'day_of_week' => ['required', 'string', 'max:255'],
            'time_slot_start' => ['required', 'string', 'max:255'],
            'time_slot_end' => ['required', 'string', 'max:255'],
            'constraint_type' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $timetableConstraint = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $timetableConstraint,
                $validated
            ) {
                $timetableConstraint->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data TimetableConstraint berhasil diperbarui.',
                $timetableConstraint
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui TimetableConstraint.',
                [
                    'exception' => $e,
                    'TimetableConstraint_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TimetableConstraint gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $timetableConstraint = $this->model->findOrFail($id);

            DB::transaction(function () use ($timetableConstraint) {
                $timetableConstraint->delete();
            });

            return $this->successResponse(
                $request,
                'Data TimetableConstraint berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $timetableConstraint->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus TimetableConstraint.',
                [
                    'exception' => $e,
                    'TimetableConstraint_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TimetableConstraint gagal dihapus.'
            );
        }
    } 
}
