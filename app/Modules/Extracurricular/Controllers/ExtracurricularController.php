<?php

namespace App\Modules\Extracurricular\Controllers;

use App\DataTables\ExtracurricularDataTable;
use App\Http\Controllers\MainController;
use Models\Extracurricular;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ExtracurricularController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Extracurricular());
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
        ExtracurricularDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /extracurricular?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Extracurricular::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $extracurricular = new Extracurricular();
        $result = $this->getForm($extracurricular);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Extracurricular::create',
            compact('extracurricular', 'forms', 'groupedForms')
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
            'description' => ['required', 'string', 'max:255'],
            'teacher_id' => ['required', 'integer'],
            'day_of_week' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'max_students' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Extracurricular::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Extracurricular berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Extracurricular.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Extracurricular gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $extracurricular = $this->model->findOrFail($id);
        $result = $this->getForm($extracurricular);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Extracurricular::edit',
            compact('extracurricular', 'forms', 'groupedForms')
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
            'description' => ['required', 'string', 'max:255'],
            'teacher_id' => ['required', 'integer'],
            'day_of_week' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'string', 'max:255'],
            'end_time' => ['required', 'string', 'max:255'],
            'max_students' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $extracurricular = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $extracurricular,
                $validated
            ) {
                $extracurricular->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Extracurricular berhasil diperbarui.',
                $extracurricular
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui Extracurricular.',
                [
                    'exception' => $e,
                    'Extracurricular_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Extracurricular gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $extracurricular = $this->model->findOrFail($id);

            DB::transaction(function () use ($extracurricular) {
                $extracurricular->delete();
            });

            return $this->successResponse(
                $request,
                'Data Extracurricular berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $extracurricular->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Extracurricular.',
                [
                    'exception' => $e,
                    'Extracurricular_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Extracurricular gagal dihapus.'
            );
        }
    } 
}
