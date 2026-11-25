<?php

namespace App\Modules\Curriculum\Controllers;

use App\DataTables\CurriculumDataTable;
use App\Http\Controllers\MainController;
use Models\Curriculum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CurriculumController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Curriculum());
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
        CurriculumDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /curriculum?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Curriculum::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Curriculum::create', [
            'organizations' => \Models\Organization::pluck('name', 'id')->toArray(),
            'schools' => \Models\School::pluck('name', 'id')->toArray(),
            'classLevels' => \Models\ClassLevel::pluck('name', 'id')->toArray(),
            'academicYears' => \Models\AcademicYear::pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['nullable', 'integer'],
            'type' => ['nullable', 'in:national,international,local,hybrid'],
            'code' => ['required', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:100'],
            'start_year' => ['nullable', 'integer', 'min:1901', 'max:2155'],
            'end_year' => ['nullable', 'integer', 'min:1901', 'max:2155'],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Curriculum::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Curriculum berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Curriculum.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Curriculum gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $curriculum = $this->model->findOrFail($id);
        $curriculum->load('curriculum_assignments');

        return view('Curriculum::edit', [
            'curriculum' => $curriculum,
            'organizations' => \Models\Organization::pluck('name', 'id')->toArray(),
            'schools' => \Models\School::pluck('name', 'id')->toArray(),
            'classLevels' => \Models\ClassLevel::pluck('name', 'id')->toArray(),
            'academicYears' => \Models\AcademicYear::pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['nullable', 'integer'],
            'type' => ['nullable', 'in:national,international,local,hybrid'],
            'code' => ['required', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:100'],
            'start_year' => ['nullable', 'integer', 'min:1901', 'max:2155'],
            'end_year' => ['nullable', 'integer', 'min:1901', 'max:2155'],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);

        try {
            $curriculum = $this->model->findOrFail($id);
            DB::transaction(function () use ($curriculum, $validated) {
                $curriculum->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Curriculum berhasil diperbarui.',
                $curriculum
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui Curriculum.',
                [
                    'exception' => $e,
                    'Curriculum_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Curriculum gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $curriculum = $this->model->findOrFail($id);

            DB::transaction(function () use ($curriculum) {
                $curriculum->delete();
            });

            return $this->successResponse(
                $request,
                'Data Curriculum berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $curriculum->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Curriculum.',
                [
                    'exception' => $e,
                    'Curriculum_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Curriculum gagal dihapus.'
            );
        }
    }
}
