<?php

namespace App\Modules\MaritalStatus\Controllers;

use App\DataTables\MaritalStatusDataTable;
use App\Http\Controllers\MainController;
use Models\MaritalStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class MaritalStatusController extends MainController
{
    public function __construct()
    {
        parent::__construct(new MaritalStatus());
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
        MaritalStatusDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /marital-status?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'MaritalStatus::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $maritalStatus = new MaritalStatus();
        $result = $this->getForm($maritalStatus);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'MaritalStatus::create',
            compact('maritalStatus', 'forms', 'groupedForms')
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
                return MaritalStatus::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data MaritalStatus berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan MaritalStatus.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MaritalStatus gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $maritalStatus = $this->model->findOrFail($id);
        $result = $this->getForm($maritalStatus);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'MaritalStatus::edit',
            compact('maritalStatus', 'forms', 'groupedForms')
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
            $maritalStatus = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $maritalStatus,
                $validated
            ) {
                $maritalStatus->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data MaritalStatus berhasil diperbarui.',
                $maritalStatus
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui MaritalStatus.',
                [
                    'exception' => $e,
                    'MaritalStatus_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MaritalStatus gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $maritalStatus = $this->model->findOrFail($id);

            DB::transaction(function () use ($maritalStatus) {
                $maritalStatus->delete();
            });

            return $this->successResponse(
                $request,
                'Data MaritalStatus berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $maritalStatus->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus MaritalStatus.',
                [
                    'exception' => $e,
                    'MaritalStatus_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data MaritalStatus gagal dihapus.'
            );
        }
    } 
}
