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
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Models\School;
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
        $academic_semester = new AcademicSemester();
        $schools = School::active()->pluck('name', 'id')->prepend('-- Pilih Unit --', '')->toArray();
        $academic_years = \Models\AcademicYear::pluck('name', 'id')->prepend('-- Pilih Tahun Akademik --', '')->toArray();
        return view(
            'AcademicSemester::create',
            compact('academic_semester', 'schools', 'academic_years')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $this->validated($request);

        try {
            $model = DB::transaction(function () use ($validated) {
                return AcademicSemester::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Semester berhasil ditambahkan.',
                $model
            );
        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Semester .',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Semester gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $academic_semester = $this->model->findOrFail($id);
        $schools = School::active()->pluck('name', 'id')->prepend('-- Pilih Unit --', '')->toArray();
        $academic_years = \Models\AcademicYear::pluck('name', 'id')->prepend('-- Pilih Tahun Akademik --', '')->toArray();
        return view(
            'AcademicSemester::edit',
            compact('academic_semester', 'schools', 'academic_years')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $this->validated($request, $id);

        try {
            $academicSemester = $this->model->findOrFail($id);
            DB::transaction(function () use ($academicSemester, $validated) {
                $academicSemester->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Semester berhasil diperbarui.',
                $academicSemester
            );
        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Semester .',
                [
                    'exception' => $e,
                    'academic_emester_id' => $id,
                    'data' => $validated,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Semester gagal diperbarui.'
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
                'Data Semester berhasil dihapus.'
            );
        } catch (Throwable $e) {
            // $academicSemester->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Semester .',
                [
                    'exception' => $e,
                    'academic_emester_id' => $id,
                    'user_id' => Auth::id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Semester gagal dihapus.'
            );
        }
    }

    private function validated($request, $id = null)
    {
        $validated = $request->validate([
            'school_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('academic_semesters', 'code')
                    ->where(function ($query) use ($request, $id) {
                        $query->where('school_id', $request->school_id)
                            ->where('academic_year_id', $request->academic_year_id);

                        // Jika sedang update, abaikan record saat ini
                        if ($id) {
                            $query->where('id', '!=', $id);
                        }

                        return $query;
                    })
            ],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'krs_start_date' => ['required', 'string', 'max:255'],
            'krs_end_date' => ['required', 'string', 'max:255'],
        ]);

        return $validated;
    }
}
