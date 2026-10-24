<?php

namespace App\Modules\IdentityType\Controllers;

use App\DataTables\IdentityTypeDataTable;
use App\Http\Controllers\MainController;
use Models\IdentityType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class IdentityTypeController extends MainController
{
    public function __construct()
    {
        parent::__construct(new IdentityType());
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
        IdentityTypeDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /identity-type?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'IdentityType::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $identityType = new IdentityType();
        $result = $this->getForm($identityType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'IdentityType::create',
            compact('identityType', 'forms', 'groupedForms')
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
            'max_length' => ['required', 'string', 'max:255'],
            'is_required' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return IdentityType::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data IdentityType berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan IdentityType.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data IdentityType gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $identityType = $this->model->findOrFail($id);
        $result = $this->getForm($identityType);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'IdentityType::edit',
            compact('identityType', 'forms', 'groupedForms')
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
            'max_length' => ['required', 'string', 'max:255'],
            'is_required' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $identityType = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $identityType,
                $validated
            ) {
                $identityType->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data IdentityType berhasil diperbarui.',
                $identityType
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui IdentityType.',
                [
                    'exception' => $e,
                    'IdentityType_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data IdentityType gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $identityType = $this->model->findOrFail($id);

            DB::transaction(function () use ($identityType) {
                $identityType->delete();
            });

            return $this->successResponse(
                $request,
                'Data IdentityType berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $identityType->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus IdentityType.',
                [
                    'exception' => $e,
                    'IdentityType_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data IdentityType gagal dihapus.'
            );
        }
    } 
}
