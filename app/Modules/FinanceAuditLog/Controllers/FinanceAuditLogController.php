<?php

namespace App\Modules\FinanceAuditLog\Controllers;

use App\DataTables\FinanceAuditLogDataTable;
use App\Http\Controllers\MainController;
use Models\FinanceAuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class FinanceAuditLogController extends MainController
{
    public function __construct()
    {
        parent::__construct(new FinanceAuditLog());
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
        FinanceAuditLogDataTable $dataTable
    ) {
        /*
         * Yajra DataTables request contains "draw".
         *
         * Example:
         * /finance-audit-log?draw=1&start=0&length=10
         */
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render(
            'FinanceAuditLog::index'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $financeAuditLog = new FinanceAuditLog();
        $result = $this->getForm($financeAuditLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FinanceAuditLog::create',
            compact('financeAuditLog', 'forms', 'groupedForms')
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
            'action' => ['required', 'string', 'max:255'],
            'table_name' => ['required', 'string', 'max:255'],
            'record_id' => ['required', 'integer'],
            'old_data' => ['required', 'string', 'max:255'],
            'new_data' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
        ]);

        try {
            $model = DB::transaction(function () use ($validated) {
                return FinanceAuditLog::create($validated);
            });

            return $this->successResponse(
                $request,
                'Data FinanceAuditLog berhasil ditambahkan.',
                $model
            );

        } catch (Throwable $e) {

            Log::error(
                'Gagal menambahkan FinanceAuditLog.',
                [
                    'exception' => $e,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FinanceAuditLog gagal ditambahkan.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $financeAuditLog = $this->model->findOrFail($id);
        $result = $this->getForm($financeAuditLog);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view(
            'FinanceAuditLog::edit',
            compact('financeAuditLog', 'forms', 'groupedForms')
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
            'action' => ['required', 'string', 'max:255'],
            'table_name' => ['required', 'string', 'max:255'],
            'record_id' => ['required', 'integer'],
            'old_data' => ['required', 'string', 'max:255'],
            'new_data' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'string', 'max:255'],
            'user_agent' => ['required', 'string', 'max:255'],
        ]);

        try {
            $financeAuditLog = $this->model->findOrFail($id);
            DB::transaction(function () use ($financeAuditLog, $validated) {
                $financeAuditLog->update($validated);
            });

            return $this->successResponse(
                $request,
                'Data FinanceAuditLog berhasil diperbarui.',
                $financeAuditLog
            );

        } catch (Throwable $e) {
            Log::error(
                'Gagal memperbarui FinanceAuditLog.',
                [
                    'exception' => $e,
                    'FinanceAuditLog_id' => $id,
                    'data' => $validated,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FinanceAuditLog gagal diperbarui.'
            );
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $financeAuditLog = $this->model->findOrFail($id);

            DB::transaction(function () use ($financeAuditLog) {
                $financeAuditLog->delete();
            });

            return $this->successResponse(
                $request,
                'Data FinanceAuditLog berhasil dihapus.'
            );

        } catch (Throwable $e) {
            // $financeAuditLog->getKey() yang bisa undefined.
            Log::error(
                'Gagal menghapus FinanceAuditLog.',
                [
                    'exception' => $e,
                    'FinanceAuditLog_id' => $id,
                    'user_id' => request()->user()?->id,
                ]
            );

            return $this->errorResponse(
                $request,
                'Data FinanceAuditLog gagal dihapus.'
            );
        }
    }
}
