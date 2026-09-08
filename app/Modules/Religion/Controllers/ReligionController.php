<?php

namespace App\Modules\Religion\Controllers;

use App\DataTables\ReligionDataTable;
use App\Http\Controllers\MainController;
use Models\Religion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ReligionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Religion());
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
        ReligionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /religion?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Religion::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $religion = new Religion();
        $result = $this->getForm($religion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Religion::create',
            compact('religion', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Religion::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Religion berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Religion.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Religion gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $religion = $this->model->findOrFail($id);
        $result = $this->getForm($religion);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Religion::edit',
            compact('religion', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'created_at' => ['required', 'string', 'max:255'],
            'updated_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $religion = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $religion,
                $validated
            ) {
                $religion->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Religion berhasil diperbarui.',
                $religion
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui Religion.',
                [
                    'exception' => $e,
                    'Religion_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Religion gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $religion = $this->model->findOrFail($id);

            DB::transaction(function () use ($religion) {
                $religion->delete();
            });

            return $this->successResponse(
                $request,
                'Data Religion berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $religion->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Religion.',
                [
                    'exception' => $e,
                    'Religion_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Religion gagal dihapus.'
            );
        }
    } 
}
