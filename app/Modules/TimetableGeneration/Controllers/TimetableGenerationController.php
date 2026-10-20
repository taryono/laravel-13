<?php

namespace App\Modules\TimetableGeneration\Controllers;

use App\DataTables\TimetableGenerationDataTable;
use App\Http\Controllers\MainController;
use Models\TimetableGeneration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TimetableGenerationController extends MainController
{
    public function __construct()
    {
        parent::__construct(new TimetableGeneration());
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
        TimetableGenerationDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /timetable-generation?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'TimetableGeneration::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $timetableGeneration = new TimetableGeneration();
        $result = $this->getForm($timetableGeneration);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TimetableGeneration::create',
            compact('timetableGeneration', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'semester' => ['required', 'string', 'max:255'],
            'generation_date' => ['required', 'string', 'max:255'],
            'algorithm_version' => ['required', 'string', 'max:255'],
            'total_conflicts' => ['required', 'string', 'max:255'],
            'total_assignments' => ['required', 'string', 'max:255'],
            'total_teachers' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'generation_data' => ['required', 'string', 'max:255'],
            'generated_by' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return TimetableGeneration::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data TimetableGeneration berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan TimetableGeneration.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TimetableGeneration gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $timetableGeneration = $this->model->findOrFail($id);
        $result = $this->getForm($timetableGeneration);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TimetableGeneration::edit',
            compact('timetableGeneration', 'forms', 'groupedForms')
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
            'semester' => ['required', 'string', 'max:255'],
            'generation_date' => ['required', 'string', 'max:255'],
            'algorithm_version' => ['required', 'string', 'max:255'],
            'total_conflicts' => ['required', 'string', 'max:255'],
            'total_assignments' => ['required', 'string', 'max:255'],
            'total_teachers' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'generation_data' => ['required', 'string', 'max:255'],
            'generated_by' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $timetableGeneration = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $timetableGeneration,
                $validated
            ) {
                $timetableGeneration->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data TimetableGeneration berhasil diperbarui.',
                $timetableGeneration
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui TimetableGeneration.',
                [
                    'exception' => $e,
                    'TimetableGeneration_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TimetableGeneration gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $timetableGeneration = $this->model->findOrFail($id);

            DB::transaction(function () use ($timetableGeneration) {
                $timetableGeneration->delete();
            });

            return $this->successResponse(
                $request,
                'Data TimetableGeneration berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $timetableGeneration->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus TimetableGeneration.',
                [
                    'exception' => $e,
                    'TimetableGeneration_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TimetableGeneration gagal dihapus.'
            );
        }
    } 
}
