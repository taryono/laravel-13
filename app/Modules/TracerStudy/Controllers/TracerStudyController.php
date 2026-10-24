<?php

namespace App\Modules\TracerStudy\Controllers;

use App\DataTables\TracerStudyDataTable;
use App\Http\Controllers\MainController;
use Models\TracerStudy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TracerStudyController extends MainController
{
    public function __construct()
    {
        parent::__construct(new TracerStudy());
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
        TracerStudyDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /tracer-study?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'TracerStudy::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tracerStudy = new TracerStudy();
        $result = $this->getForm($tracerStudy);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TracerStudy::create',
            compact('tracerStudy', 'forms', 'groupedForms')
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'target_graduation_year' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return TracerStudy::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data TracerStudy berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan TracerStudy.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TracerStudy gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $tracerStudy = $this->model->findOrFail($id);
        $result = $this->getForm($tracerStudy);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TracerStudy::edit',
            compact('tracerStudy', 'forms', 'groupedForms')
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'target_graduation_year' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'string', 'max:255'],
            'end_date' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $tracerStudy = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $tracerStudy,
                $validated
            ) {
                $tracerStudy->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data TracerStudy berhasil diperbarui.',
                $tracerStudy
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui TracerStudy.',
                [
                    'exception' => $e,
                    'TracerStudy_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TracerStudy gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $tracerStudy = $this->model->findOrFail($id);

            DB::transaction(function () use ($tracerStudy) {
                $tracerStudy->delete();
            });

            return $this->successResponse(
                $request,
                'Data TracerStudy berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $tracerStudy->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus TracerStudy.',
                [
                    'exception' => $e,
                    'TracerStudy_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TracerStudy gagal dihapus.'
            );
        }
    } 
}
