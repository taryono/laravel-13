<?php

namespace App\Modules\PayrollDetail\Controllers;

use App\DataTables\PayrollDetailDataTable;
use App\Http\Controllers\MainController;
use Models\PayrollDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Models\PayrollRun; 
use Throwable;

class PayrollDetailController extends MainController
{
    public function __construct()
    {
        parent::__construct(new PayrollDetail());
    }

    public function index(Request $request, PayrollDetailDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Payroll::payroll_detail.index');
    }

    public function show($id): View
    {
        $detail = $this->model->with(['employee', 'payrollRun'])->findOrFail($id);
        return view('Payroll::payroll_detail.show', compact('detail'));
    }

    public function export($id, Request $request)
    {
        try {
            $detail = $this->model->findOrFail($id);
            $format = $request->get('format', 'pdf');

            // Implementasi export sesuai kebutuhan
            // return $this->exportService->export($detail, $format);

            return $this->successResponse($request, 'Ekspor detail payroll berhasil.');
        } catch (Throwable $e) {
            Log::error('Gagal ekspor detail payroll.', ['exception' => $e, 'detail_id' => $id]);
            return $this->errorResponse($request, 'Gagal mengekspor detail payroll.');
        }
    }

    public function getByPayrollRun($payrollRunId): JsonResponse
    {
        try {
            $details = PayrollDetail::with('employee')
                ->where('payroll_run_id', $payrollRunId)
                ->get();

            return response()->json(['success' => true, 'data' => $details]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil detail payroll.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }

    public function getSummary($payrollRunId): JsonResponse
    {
        try {
            $summary = PayrollDetail::where('payroll_run_id', $payrollRunId)
                ->select([
                    DB::raw('COUNT(*) as total_employees'),
                    DB::raw('SUM(gross_salary) as total_gross'),
                    DB::raw('SUM(net_salary) as total_net'),
                    DB::raw('SUM(total_deductions) as total_deductions'),
                    DB::raw('SUM(total_overtime) as total_overtime'),
                    DB::raw('SUM(total_loan_payment) as total_loans'),
                    DB::raw('AVG(net_salary) as average_salary'),
                ])
                ->first();

            return response()->json(['success' => true, 'data' => $summary]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil summary payroll.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }

    public function getByEmployee($employeeId, Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 12);
            $details = PayrollDetail::with('payrollRun.payrollPeriod')
                ->where('employee_id', $employeeId)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            return response()->json(['success' => true, 'data' => $details]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil detail payroll karyawan.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }
}