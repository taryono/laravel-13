<?php

namespace App\Modules\TeachingLoad\Controllers;

use App\DataTables\TeachingLoadDataTable;
use App\Http\Controllers\MainController;
use Models\TeachingLoad;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TeachingLoadController extends MainController
{
    public function __construct()
    {
        parent::__construct(new TeachingLoad());
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
        TeachingLoadDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /teaching-load?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'TeachingLoad::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $teachingLoad = new TeachingLoad();
        $result = $this->getForm($teachingLoad);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TeachingLoad::create',
            compact('teachingLoad', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'semester' => ['required', 'string', 'max:255'],
            'total_hours' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return TeachingLoad::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data TeachingLoad berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan TeachingLoad.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TeachingLoad gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $teachingLoad = $this->model->findOrFail($id);
        $result = $this->getForm($teachingLoad);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'TeachingLoad::edit',
            compact('teachingLoad', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'teacher_id' => ['required', 'integer'],
            'classroom_id' => ['required', 'integer'],
            'subject_id' => ['required', 'integer'],
            'academic_year_id' => ['required', 'integer'],
            'semester' => ['required', 'string', 'max:255'],
            'total_hours' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
        ]);

        try {
            $teachingLoad = $this->model->findOrFail($id);
            DB::transaction(function () use ($teachingLoad, $validated) {
                $teachingLoad->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data TeachingLoad berhasil diperbarui.',
                $teachingLoad
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui TeachingLoad.',
                [
                    'exception' => $e,
                    'TeachingLoad_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TeachingLoad gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $teachingLoad = $this->model->findOrFail($id);

            DB::transaction(function () use ($teachingLoad) {
                $teachingLoad->delete();
            });

            return $this->successResponse(
                $request,
                'Data TeachingLoad berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $teachingLoad->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus TeachingLoad.',
                [
                    'exception' => $e,
                    'TeachingLoad_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data TeachingLoad gagal dihapus.'
            );
        }
    }
}
