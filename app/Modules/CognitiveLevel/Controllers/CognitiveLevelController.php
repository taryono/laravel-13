<?php

namespace App\Modules\CognitiveLevel\Controllers;

use App\DataTables\CognitiveLevelDataTable;
use App\Http\Controllers\MainController;
use Models\CognitiveLevel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CognitiveLevelController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CognitiveLevel());
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
        CognitiveLevelDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cognitive-level?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CognitiveLevel::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cognitiveLevel = new CognitiveLevel();
        $result = $this->getForm($cognitiveLevel);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CognitiveLevel::create',
            compact('cognitiveLevel', 'forms', 'groupedForms')
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
                return CognitiveLevel::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CognitiveLevel berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CognitiveLevel.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CognitiveLevel gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cognitiveLevel = $this->model->findOrFail($id);
        $result = $this->getForm($cognitiveLevel);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CognitiveLevel::edit',
            compact('cognitiveLevel', 'forms', 'groupedForms')
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
            $cognitiveLevel = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $cognitiveLevel,
                $validated
            ) {
                $cognitiveLevel->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CognitiveLevel berhasil diperbarui.',
                $cognitiveLevel
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui CognitiveLevel.',
                [
                    'exception' => $e,
                    'CognitiveLevel_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CognitiveLevel gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $cognitiveLevel = $this->model->findOrFail($id);

            DB::transaction(function () use ($cognitiveLevel) {
                $cognitiveLevel->delete();
            });

            return $this->successResponse(
                $request,
                'Data CognitiveLevel berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $cognitiveLevel->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CognitiveLevel.',
                [
                    'exception' => $e,
                    'CognitiveLevel_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CognitiveLevel gagal dihapus.'
            );
        }
    } 
}
