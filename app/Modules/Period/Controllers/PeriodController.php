<?php

namespace App\Modules\Period\Controllers;

use App\DataTables\PeriodDataTable;
use App\Http\Controllers\MainController;
use Models\Period;
use Models\AcademicYear;
use Models\Organization;
use Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PeriodController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Period());
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
        PeriodDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /period?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Period::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('Period::create', [
            'Organization' => Organization::pluck('name', 'id'),
            'schools' => School::pluck('name', 'id'),
            'academicYears' => AcademicYear::pluck('name', 'id'),
        ]);
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'assessment_type' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'grading_start_date' => ['required', 'string', 'max:255'],
            'grading_end_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Period::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Period berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Period.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Period gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $period = $this->model->findOrFail($id);
        return view('Period::edit', [
            'period' => $period,
            'Organization' => Organization::pluck('name', 'id'),
            'schools' => School::pluck('name', 'id'),
            'academicYears' => AcademicYear::pluck('name', 'id'),
        ]);
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'assessment_type' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'grading_start_date' => ['required', 'string', 'max:255'],
            'grading_end_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $period = $this->model->findOrFail($id);
            DB::transaction(function () use ($period, $validated) {
                $period->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Period berhasil diperbarui.',
                $period
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Period.',
                [
                    'exception' => $e,
                    'Period_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Period gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $period = $this->model->findOrFail($id);

            DB::transaction(function () use ($period) {
                $period->delete();
            });

            return $this->successResponse(
                $request,
                'Data Period berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $period->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Period.',
                [
                    'exception' => $e,
                    'Period_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Period gagal dihapus.'
            );
        }
    }
}
