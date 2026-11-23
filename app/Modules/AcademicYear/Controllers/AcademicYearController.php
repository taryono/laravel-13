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
use Illuminate\Support\Facades\Auth;
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
     *_Normal page request
     *_Yajra DataTables AJAX request
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
        $academic_year = new AcademicYear();
        $schools = \Models\School::with('organization')->pluck('name', 'id')->toArray();
        return view(
            'AcademicYear::create',
            compact('academic_year', 'schools')
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
                'Data Tahun Ajaran berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Tahun Ajaran.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Tahun Ajaran gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $academic_year = $this->model->findOrFail($id);
        $result = $this->getForm($academic_year);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'AcademicYear::edit',
            compact('academic_year', 'forms', 'groupedForms')
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
            $academic_year = $this->model->findOrFail($id);
            DB::transaction(function () use ($academic_year, $validated) {
                $academic_year->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Tahun Ajaran berhasil diperbarui.',
                $academic_year
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Tahun Ajaran.',
                [
                    'exception' => $e,
                    'academic_year_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Tahun Ajaran gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $academic_year = $this->model->findOrFail($id);

            DB::transaction(function () use ($academic_year) {
                $academic_year->delete();
            });

            return $this->successResponse(
                $request,
                'Data Tahun Ajaran berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $academic_year->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Tahun Ajaran.',
                [
                    'exception' => $e,
                    'academic_year_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Tahun Ajaran gagal dihapus.'
            );
        }
    }

    public function updateRegistrationOpen(Request $request, $academic_year, $is_registration_open): JsonResponse
    {
        try {
            $academic_year = $this->model->where('academic_year', $academic_year)->first();
            $academic_year->update(['is_registration_open' => $is_registration_open]);

            return $this->successResponse(
                $request,
                'Data Tahun Ajaran berhasil diperbarui.',
                $academic_year
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Tahun Ajaran.',
                [
                    'exception' => $e,
                    'academic_year_id' => $academic_year,
                    'data' => $request->all(),
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Tahun Ajaran gagal diperbarui.'
            );
        }
    }

    public function updateActive(Request $request, $academic_year, $is_active): JsonResponse
    {
        try {
            $academic_year = $this->model->where('academic_year', $academic_year)->first();
            $academic_year->update(['is_active' => $is_active]);

            return $this->successResponse(
                $request,
                'Data Tahun Ajaran berhasil diperbarui.',
                $academic_year
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Tahun Ajaran.',
                [
                    'exception' => $e,
                    'academic_year_id' => $academic_year,
                    'data' => $request->all(),
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Tahun Ajaran gagal diperbarui.'
            );
        }
    }

    public function updateCurrent(Request $request, $academic_year, $is_current): JsonResponse
    {
        try {
            $academic_year = $this->model->where('academic_year', $academic_year)->first();
            $academic_year->update(['is_current' => $is_current]);

            return $this->successResponse(
                $request,
                'Data Tahun Ajaran berhasil diperbarui.',
                $academic_year
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Tahun Ajaran.',
                [
                    'exception' => $e,
                    'academic_year_id' => $academic_year,
                    'data' => $request->all(),
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Tahun Ajaran gagal diperbarui.'
            );
        }
    }
}
