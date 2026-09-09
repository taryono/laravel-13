<?php

namespace App\Modules\AcademicYear\Controllers;

use App\DataTables\AcademicYearDataTable;
use App\Http\Controllers\MainController;
use Models\AcademicYear;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AcademicYearController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AcademicYear());
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
        AcademicYearDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /academic-year?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AcademicYear::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $academicYear = new AcademicYear();
        $result = $this->getForm($academicYear);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AcademicYear::create',
            compact('academicYear', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'school_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'semester_start_date' => ['required', 'string', 'max:255'],
            'semester_end_date' => ['required', 'string', 'max:255'],
            'is_registration_open' => ['required', 'string', 'max:255'],
            'registration_start_date' => ['required', 'string', 'max:255'],
            'registration_end_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'is_current' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AcademicYear::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AcademicYear berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AcademicYear.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AcademicYear gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $academicYear = $this->model->findOrFail($id);
        $result = $this->getForm($academicYear);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AcademicYear::edit',
            compact('academicYear', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'school_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'semester_start_date' => ['required', 'string', 'max:255'],
            'semester_end_date' => ['required', 'string', 'max:255'],
            'is_registration_open' => ['required', 'string', 'max:255'],
            'registration_start_date' => ['required', 'string', 'max:255'],
            'registration_end_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'is_current' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $academicYear = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $academicYear,
                $validated
            ) {
                $academicYear->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AcademicYear berhasil diperbarui.',
                $academicYear
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui AcademicYear.',
                [
                    'exception' => $e,
                    'AcademicYear_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AcademicYear gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $academicYear = $this->model->findOrFail($id);

            DB::transaction(function () use ($academicYear) {
                $academicYear->delete();
            });

            return $this->successResponse(
                $request,
                'Data AcademicYear berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $academicYear->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AcademicYear.',
                [
                    'exception' => $e,
                    'AcademicYear_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AcademicYear gagal dihapus.'
            );
        }
    } 
}
