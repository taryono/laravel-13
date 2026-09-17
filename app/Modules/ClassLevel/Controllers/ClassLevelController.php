<?php

namespace App\Modules\ClassLevel\Controllers;

use App\DataTables\ClassLevelDataTable;
use App\Http\Controllers\MainController;
use Models\ClassLevel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ClassLevelController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ClassLevel());
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
        ClassLevelDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /class-level?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ClassLevel::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $classLevel = new ClassLevel();
        $result = $this->getForm($classLevel);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ClassLevel::create',
            compact('classLevel', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'level_order' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ClassLevel::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ClassLevel berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ClassLevel.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ClassLevel gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $classLevel = $this->model->findOrFail($id);
        $result = $this->getForm($classLevel);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ClassLevel::edit',
            compact('classLevel', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'level_order' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
            'deleted_at' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $classLevel = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $classLevel,
                $validated
            ) {
                $classLevel->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ClassLevel berhasil diperbarui.',
                $classLevel
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ClassLevel.',
                [
                    'exception' => $e,
                    'ClassLevel_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ClassLevel gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $classLevel = $this->model->findOrFail($id);

            DB::transaction(function () use ($classLevel) {
                $classLevel->delete();
            });

            return $this->successResponse(
                $request,
                'Data ClassLevel berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $classLevel->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ClassLevel.',
                [
                    'exception' => $e,
                    'ClassLevel_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ClassLevel gagal dihapus.'
            );
        }
    } 
}
