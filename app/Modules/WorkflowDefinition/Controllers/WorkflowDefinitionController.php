<?php

namespace App\Modules\WorkflowDefinition\Controllers;

use App\DataTables\WorkflowDefinitionDataTable;
use App\Http\Controllers\MainController;
use Models\WorkflowDefinition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class WorkflowDefinitionController extends MainController
{
    public function __construct()
    {
        parent::__construct(new WorkflowDefinition());
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
        WorkflowDefinitionDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /workflow-definition?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'WorkflowDefinition::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $workflowDefinition = new WorkflowDefinition();
        $result = $this->getForm($workflowDefinition);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WorkflowDefinition::create',
            compact('workflowDefinition', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'module' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return WorkflowDefinition::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data WorkflowDefinition berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan WorkflowDefinition.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkflowDefinition gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $workflowDefinition = $this->model->findOrFail($id);
        $result = $this->getForm($workflowDefinition);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'WorkflowDefinition::edit',
            compact('workflowDefinition', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'school_id' => ['required', 'integer'],
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'module' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'string', 'max:255'],
            'created_by' => ['required', 'string', 'max:255'],
            'updated_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            $workflowDefinition = $this->model->findOrFail($id);
            DB::transaction(function () use ($workflowDefinition, $validated) {
                $workflowDefinition->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data WorkflowDefinition berhasil diperbarui.',
                $workflowDefinition
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui WorkflowDefinition.',
                [
                    'exception' => $e,
                    'WorkflowDefinition_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkflowDefinition gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $workflowDefinition = $this->model->findOrFail($id);

            DB::transaction(function () use ($workflowDefinition) {
                $workflowDefinition->delete();
            });

            return $this->successResponse(
                $request,
                'Data WorkflowDefinition berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $workflowDefinition->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus WorkflowDefinition.',
                [
                    'exception' => $e,
                    'WorkflowDefinition_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data WorkflowDefinition gagal dihapus.'
            );
        }
    }
}
