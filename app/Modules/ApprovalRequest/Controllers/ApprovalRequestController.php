<?php

namespace App\Modules\ApprovalRequest\Controllers;

use App\DataTables\ApprovalRequestDataTable;
use App\Http\Controllers\MainController;
use Models\ApprovalRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApprovalRequestController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ApprovalRequest());
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
        ApprovalRequestDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /approval-request?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ApprovalRequest::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $approvalRequest = new ApprovalRequest();
        $result = $this->getForm($approvalRequest);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApprovalRequest::create',
            compact('approvalRequest', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'workflow_definition_id' => ['required', 'integer'],
            'current_step_id' => ['required', 'integer'],
            'requestable_type' => ['required', 'string', 'max:255'],
            'requestable_id' => ['required', 'integer'],
            'document_number' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'requester_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'submitted_at' => ['required', 'string', 'max:255'],
            'completed_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ApprovalRequest::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApprovalRequest berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ApprovalRequest.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApprovalRequest gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $approvalRequest = $this->model->findOrFail($id);
        $result = $this->getForm($approvalRequest);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApprovalRequest::edit',
            compact('approvalRequest', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'integer'],
            'workflow_definition_id' => ['required', 'integer'],
            'current_step_id' => ['required', 'integer'],
            'requestable_type' => ['required', 'string', 'max:255'],
            'requestable_id' => ['required', 'integer'],
            'document_number' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'requester_id' => ['required', 'integer'],
            'status' => ['required', 'string', 'max:255'],
            'submitted_at' => ['required', 'string', 'max:255'],
            'completed_at' => ['required', 'string', 'max:255'],
        ]);

        try {
            $approvalRequest = $this->model->findOrFail($id);
            DB::transaction(function () use ($approvalRequest, $validated) {
                $approvalRequest->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApprovalRequest berhasil diperbarui.',
                $approvalRequest
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui ApprovalRequest.',
                [
                    'exception' => $e,
                    'ApprovalRequest_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApprovalRequest gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $approvalRequest = $this->model->findOrFail($id);

            DB::transaction(function () use ($approvalRequest) {
                $approvalRequest->delete();
            });

            return $this->successResponse(
                $request,
                'Data ApprovalRequest berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $approvalRequest->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ApprovalRequest.',
                [
                    'exception' => $e,
                    'ApprovalRequest_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApprovalRequest gagal dihapus.'
            );
        }
    }
}
