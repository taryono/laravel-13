<?php

namespace App\Modules\CostCenter\Controllers;

use App\DataTables\CostCenterDataTable;
use App\Http\Controllers\MainController;
use Models\CostCenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CostCenterController extends MainController
{
    public function __construct()
    {
        parent::__construct(new CostCenter());
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
        CostCenterDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /cost-center?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'CostCenter::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $costCenter = new CostCenter();
        $result = $this->getForm($costCenter);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CostCenter::create',
            compact('costCenter', 'forms', 'groupedForms')
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
            'parent_id' => ['required', 'integer'],
            'department_id' => ['required', 'integer'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return CostCenter::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data CostCenter berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan CostCenter.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CostCenter gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $costCenter = $this->model->findOrFail($id);
        $result = $this->getForm($costCenter);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'CostCenter::edit',
            compact('costCenter', 'forms', 'groupedForms')
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
            'parent_id' => ['required', 'integer'],
            'department_id' => ['required', 'integer'],
        ]);

        try {
            $costCenter = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $costCenter,
                $validated
            ) {
                $costCenter->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data CostCenter berhasil diperbarui.',
                $costCenter
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui CostCenter.',
                [
                    'exception' => $e,
                    'CostCenter_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CostCenter gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $costCenter = $this->model->findOrFail($id);

            DB::transaction(function () use ($costCenter) {
                $costCenter->delete();
            });

            return $this->successResponse(
                $request,
                'Data CostCenter berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $costCenter->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus CostCenter.',
                [
                    'exception' => $e,
                    'CostCenter_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data CostCenter gagal dihapus.'
            );
        }
    } 
}
