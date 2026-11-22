<?php

namespace App\Modules\WorkflowStep\Controllers;

use App\DataTables\WorkflowStepDataTable;
use App\Http\Controllers\MainController;
use Models\WorkflowStep;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class WorkflowStepController extends MainController
{
    public function __construct()
    {
        parent::__construct(new WorkflowStep());
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
        WorkflowStepDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /workflow-step?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'WorkflowStep::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $workflowStep = new WorkflowStep();
        $result = $this->getForm($workflowStep);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WorkflowStep::create',
            compact('workflowStep', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'workflow_definition_id' => ['required', 'integer'],
            'step_order' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'approver_type' => ['required', 'string', 'max:255'],
            'min_amount' => ['required', 'string', 'max:255'],
            'max_amount' => ['required', 'string', 'max:255'],
            'action_type' => ['required', 'string', 'max:255'],
            'auto_notify' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return WorkflowStep::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data WorkflowStep berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan WorkflowStep.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkflowStep gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $workflowStep = $this->model->findOrFail($id);
        $result = $this->getForm($workflowStep);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WorkflowStep::edit',
            compact('workflowStep', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'workflow_definition_id' => ['required', 'integer'],
            'step_order' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'approver_type' => ['required', 'string', 'max:255'],
            'min_amount' => ['required', 'string', 'max:255'],
            'max_amount' => ['required', 'string', 'max:255'],
            'action_type' => ['required', 'string', 'max:255'],
            'auto_notify' => ['required', 'string', 'max:255'],
        ]);

        try {
            $workflowStep = $this->model->findOrFail($id);
            DB::transaction(function () use ($workflowStep, $validated) {
                $workflowStep->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data WorkflowStep berhasil diperbarui.',
                $workflowStep
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui WorkflowStep.',
                [
                    'exception' => $e,
                    'WorkflowStep_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkflowStep gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $workflowStep = $this->model->findOrFail($id);

            DB::transaction(function () use ($workflowStep) {
                $workflowStep->delete();
            });

            return $this->successResponse(
                $request,
                'Data WorkflowStep berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $workflowStep->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus WorkflowStep.',
                [
                    'exception' => $e,
                    'WorkflowStep_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkflowStep gagal dihapus.'
            );
        }
    }
}
