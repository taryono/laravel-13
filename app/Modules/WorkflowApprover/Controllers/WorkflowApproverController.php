<?php

namespace App\Modules\WorkflowApprover\Controllers;

use App\DataTables\WorkflowApproverDataTable;
use App\Http\Controllers\MainController;
use Models\WorkflowApprover;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class WorkflowApproverController extends MainController
{
    public function __construct()
    {
        parent::__construct(new WorkflowApprover());
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
        WorkflowApproverDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /workflow-approver?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'WorkflowApprover::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $workflowApprover = new WorkflowApprover();
        $result = $this->getForm($workflowApprover);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WorkflowApprover::create',
            compact('workflowApprover', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'workflow_step_id' => ['required', 'integer'],
            'role_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'is_mandatory' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return WorkflowApprover::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data WorkflowApprover berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan WorkflowApprover.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkflowApprover gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $workflowApprover = $this->model->findOrFail($id);
        $result = $this->getForm($workflowApprover);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WorkflowApprover::edit',
            compact('workflowApprover', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'workflow_step_id' => ['required', 'integer'],
            'role_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'is_mandatory' => ['required', 'string', 'max:255'],
        ]);

        try {
            $workflowApprover = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $workflowApprover,
                $validated
            ) {
                $workflowApprover->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data WorkflowApprover berhasil diperbarui.',
                $workflowApprover
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui WorkflowApprover.',
                [
                    'exception' => $e,
                    'WorkflowApprover_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkflowApprover gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $workflowApprover = $this->model->findOrFail($id);

            DB::transaction(function () use ($workflowApprover) {
                $workflowApprover->delete();
            });

            return $this->successResponse(
                $request,
                'Data WorkflowApprover berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $workflowApprover->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus WorkflowApprover.',
                [
                    'exception' => $e,
                    'WorkflowApprover_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkflowApprover gagal dihapus.'
            );
        }
    } 
}
