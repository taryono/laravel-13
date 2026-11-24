<?php

namespace App\Modules\Parents\Controllers;

use App\DataTables\ParentsDataTable;
use App\Http\Controllers\MainController;
use Models\Parents;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ParentsController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Parents());
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
        ParentsDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /parents?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'Parents::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $parents = new Parents();
        $result = $this->getForm($parents);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Parents::create',
            compact('parents', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'organization_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return Parents::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data Parents berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan Parents.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Parents gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $parents = $this->model->findOrFail($id);
        $result = $this->getForm($parents);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'Parents::edit',
            compact('parents', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'organization_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:255'],
        ]);

        try {
            $parents = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $parents,
                $validated
            ) {
                $parents->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data Parents berhasil diperbarui.',
                $parents
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui Parents.',
                [
                    'exception' => $e,
                    'Parents_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Parents gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $parents = $this->model->findOrFail($id);

            DB::transaction(function () use ($parents) {
                $parents->delete();
            });

            return $this->successResponse(
                $request,
                'Data Parents berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $parents->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus Parents.',
                [
                    'exception' => $e,
                    'Parents_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data Parents gagal dihapus.'
            );
        }
    } 
}
