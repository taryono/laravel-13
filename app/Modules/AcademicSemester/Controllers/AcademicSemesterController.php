<?php

namespace App\Modules\AcademicSemester\Controllers;

use App\DataTables\AcademicSemesterDataTable;
use App\Http\Controllers\MainController;
use Models\AcademicSemester;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class AcademicSemesterController extends MainController
{
    public function __construct()
    {
        parent::__construct(new AcademicSemester());
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
        AcademicSemesterDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /academic-semester?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'AcademicSemester::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $academicSemester = new AcademicSemester();
        $result = $this->getForm($academicSemester);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AcademicSemester::create',
            compact('academicSemester', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'krs_start_date' => ['required', 'string', 'max:255'],
            'krs_end_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'is_current' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AcademicSemester::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data AcademicSemester berhasil ditambahkan.',
                $model
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan AcademicSemester.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AcademicSemester gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $academic_semester = $this->model->findOrFail($id);
        return view(
            'AcademicSemester::edit',
            compact('academic_semester')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'krs_start_date' => ['required', 'string', 'max:255'],
            'krs_end_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'is_current' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $academicSemester = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $academicSemester,
                $validated
            ) {
                $academicSemester->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data AcademicSemester berhasil diperbarui.',
                $academicSemester
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui AcademicSemester.',
                [
                    'exception' => $e,
                    'AcademicSemester_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AcademicSemester gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $academicSemester = $this->model->findOrFail($id);

            DB::transaction(function () use ($academicSemester) {
                $academicSemester->delete();
            });

            return $this->successResponse(
                $request,
                'Data AcademicSemester berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $academicSemester->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus AcademicSemester.',
                [
                    'exception' => $e,
                    'AcademicSemester_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data AcademicSemester gagal dihapus.'
            );
        }
    }
}
