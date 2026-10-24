<?php

namespace App\Modules\ApprovalHistory\Controllers;

use App\DataTables\ApprovalHistoryDataTable;
use App\Http\Controllers\MainController;
use Models\ApprovalHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ApprovalHistoryController extends MainController
{
    public function __construct()
    {
        parent::__construct(new ApprovalHistory());
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
        ApprovalHistoryDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /approval-history?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'ApprovalHistory::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $approvalHistory = new ApprovalHistory();
        $result = $this->getForm($approvalHistory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApprovalHistory::create',
            compact('approvalHistory', 'forms', 'groupedForms')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
                            'approval_request_id' => ['required', 'integer'],
            'workflow_step_id' => ['required', 'integer'],
            'approver_id' => ['required', 'integer'],
            'action' => ['required', 'string', 'max:255'],
            'comments' => ['required', 'string', 'max:255'],
            'signature_url' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return ApprovalHistory::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApprovalHistory berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan ApprovalHistory.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApprovalHistory gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $approvalHistory = $this->model->findOrFail($id);
        $result = $this->getForm($approvalHistory);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'ApprovalHistory::edit',
            compact('approvalHistory', 'forms', 'groupedForms')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
                        'approval_request_id' => ['required', 'integer'],
            'workflow_step_id' => ['required', 'integer'],
            'approver_id' => ['required', 'integer'],
            'action' => ['required', 'string', 'max:255'],
            'comments' => ['required', 'string', 'max:255'],
            'signature_url' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
        ]);

        try {
            $approvalHistory = $this->model->findOrFail($id);
            DB::transaction(function () use (
                $approvalHistory,
                $validated
            ) {
                $approvalHistory->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data ApprovalHistory berhasil diperbarui.',
                $approvalHistory
            );

        } catch (Throwable $e) { 
            Log::error(
                'Gagal memperbarui ApprovalHistory.',
                [
                    'exception' => $e,
                    'ApprovalHistory_id' => $id,
                    'data' => $validated,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApprovalHistory gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $approvalHistory = $this->model->findOrFail($id);

            DB::transaction(function () use ($approvalHistory) {
                $approvalHistory->delete();
            });

            return $this->successResponse(
                $request,
                'Data ApprovalHistory berhasil dihapus.'
            );

        } catch (Throwable $e) { 
            // $approvalHistory->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus ApprovalHistory.',
                [
                    'exception' => $e,
                    'ApprovalHistory_id' => $id,
                    'user_id' => auth()->id(),
                ]
            );

            return $this->errorResponse(
                $request,
                'Data ApprovalHistory gagal dihapus.'
            );
        }
    } 
}
