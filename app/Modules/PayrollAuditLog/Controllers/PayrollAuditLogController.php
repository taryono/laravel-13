<?php

namespace App\Modules\PayrollAuditLog\Controllers;

use App\DataTables\PayrollAuditLogDataTable;
use App\Http\Controllers\MainController;
use Models\PayrollAuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
class PayrollAuditLogController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PayrollAuditLog());
    }

    public function index(Request $request, PayrollAuditLogDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('System::payroll_audit_log.index');
    }

    public function show($id): View
    {
        $log = $this->model->findOrFail($id);
        return view('System::payroll_audit_log.show', compact('log'));
    }

    public function export(Request $request)
    {
        try {
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $module = $request->get('module');

            $query = PayrollAuditLog::query();

            if ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            }

            if ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            }

            if ($module) {
                $query->where('module', $module);
            }

            $logs = $query->orderBy('created_at', 'desc')->get();

            // Implementasi export (Excel/PDF)
            // return Excel::download(new AuditLogExport($logs), 'audit_logs.xlsx');

            return response()->json([
                'success' => true,
                'message' => 'Ekspor audit log berhasil.',
                'data' => $logs
            ]);

        } catch (Throwable $e) {
            Log::error('Gagal ekspor audit log.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal ekspor audit log.'], 500);
        }
    }
}
