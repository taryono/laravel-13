<?php

namespace App\Modules\DifficultyLevel\Controllers;

use App\DataTables\DifficultyLevelDataTable;
use App\Http\Controllers\MainController;
use Models\DifficultyLevel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class DifficultyLevelController extends MainController
{
    public function __construct()
    {
        parent::__construct(new DifficultyLevel());
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
        DifficultyLevelDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /difficulty-level?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'DifficultyLevel::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $difficultyLevel = new DifficultyLevel();
        $result = $this->getForm($difficultyLevel);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DifficultyLevel::create',
            compact('difficultyLevel', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return DifficultyLevel::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data DifficultyLevel berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan DifficultyLevel.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DifficultyLevel gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $difficultyLevel = $this->model->findOrFail($id);
        $result = $this->getForm($difficultyLevel);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'DifficultyLevel::edit',
            compact('difficultyLevel', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        try {
            $difficultyLevel = $this->model->findOrFail($id);
            DB::transaction(function () use ($difficultyLevel, $validated) {
                $difficultyLevel->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data DifficultyLevel berhasil diperbarui.',
                $difficultyLevel
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui DifficultyLevel.',
                [
                    'exception' => $e,
                    'DifficultyLevel_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DifficultyLevel gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $difficultyLevel = $this->model->findOrFail($id);

            DB::transaction(function () use ($difficultyLevel) {
                $difficultyLevel->delete();
            });

            return $this->successResponse(
                $request,
                'Data DifficultyLevel berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $difficultyLevel->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus DifficultyLevel.',
                [
                    'exception' => $e,
                    'DifficultyLevel_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data DifficultyLevel gagal dihapus.'
            );
        }
    }
}
