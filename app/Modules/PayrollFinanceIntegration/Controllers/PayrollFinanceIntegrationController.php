<?php

namespace App\Modules\PayrollFinanceIntegration\Controllers;

use App\DataTables\PayrollFinanceIntegratioDataTable;
use App\Http\Controllers\MainController;
use Models\PayrollFinanceIntegration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;
class PayrollFinanceIntegrationController extends MainController
{
    protected PayrollFinanceIntegrationService $integrationService;

    public function __construct(PayrollFinanceIntegrationService $integrationService)
    {
        parent::__construct(new PayrollIntegration());
        $this->integrationService = $integrationService;
    }

    /**
     * Display list of payroll integrations.
     */
    public function index(Request $request): View
    {
        $integrations = PayrollIntegration::with(['payrollRun', 'journalEntry'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('Integration::payroll.index', compact('integrations'));
    }

    /**
     * Show integration form.
     */
    public function create(): View
    {
        $payrollRuns = PayrollRun::where('status', 'completed')
            ->whereDoesntHave('integration')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Integration::payroll.create', compact('payrollRuns'));
    }

    /**
     * Sync payroll to finance.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payroll_run_id' => 'required|exists:payroll_runs,id',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $payrollRun = PayrollRun::with(['payrollDetails', 'payrollPeriod'])
                    ->findOrFail($validated['payroll_run_id']);

                $this->integrationService->syncToFinance($payrollRun);
            });

            return $this->successResponse(
                $request,
                'Payroll berhasil disinkronkan ke modul Finance.'
            );

        } catch (Throwable $e) {
            Log::error('Gagal sinkronisasi payroll ke finance.', [
                'exception' => $e,
                'payroll_run_id' => $validated['payroll_run_id'],
                'user_id' => request()->user()?->id,
            ]);

            return $this->errorResponse(
                $request,
                'Sinkronisasi gagal: ' . $e->getMessage()
            );
        }
    }

    /**
     * Show integration details.
     */
    public function show($id): View
    {
        $integration = $this->model->with([
            'payrollRun',
            'payrollRun.payrollDetails',
            'journalEntry',
            'journalEntry.details.account'
        ])->findOrFail($id);

        return view('Integration::payroll.show', compact('integration'));
    }

    /**
     * Sync to finance manually.
     */
    public function sync(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $integration = $this->model->findOrFail($id);

            if ($integration->status === 'synced') {
                return $this->errorResponse(
                    $request,
                    'Data sudah disinkronkan sebelumnya.'
                );
            }

            DB::transaction(function () use ($integration) {
                $payrollRun = PayrollRun::with(['payrollDetails', 'payrollPeriod'])
                    ->findOrFail($integration->payroll_run_id);

                // Jika sebelumnya failed, hapus jurnal yang sudah dibuat
                if ($integration->journal_entry_id) {
                    JournalEntry::find($integration->journal_entry_id)?->delete();
                }

                $this->integrationService->syncToFinance($payrollRun);
            });

            return $this->successResponse(
                $request,
                'Sinkronisasi berhasil.',
                $integration->fresh()
            );

        } catch (Throwable $e) {
            Log::error('Gagal sinkronisasi ulang.', [
                'exception' => $e,
                'integration_id' => $id,
                'user_id' => request()->user()?->id,
            ]);

            return $this->errorResponse(
                $request,
                'Sinkronisasi gagal: ' . $e->getMessage()
            );
        }
    }

    /**
     * Reverse integration (rollback).
     */
    public function reverse(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $integration = $this->model->findOrFail($id);

            if ($integration->status !== 'synced') {
                return $this->errorResponse(
                    $request,
                    'Hanya data yang sudah disinkronkan yang dapat dibatalkan.'
                );
            }

            DB::transaction(function () use ($integration) {
                $this->integrationService->reverseSync($integration);
            });

            return $this->successResponse(
                $request,
                'Sinkronisasi berhasil dibatalkan.'
            );

        } catch (Throwable $e) {
            Log::error('Gagal membatalkan sinkronisasi.', [
                'exception' => $e,
                'integration_id' => $id,
                'user_id' => request()->user()?->id,
            ]);

            return $this->errorResponse(
                $request,
                'Pembatalan gagal: ' . $e->getMessage()
            );
        }
    }

    /**
     * Get integration status.
     */
    public function status($id): JsonResponse
    {
        try {
            $integration = $this->model->with(['payrollRun', 'journalEntry'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'integration' => $integration,
                    'status_label' => $integration->getStatusLabel(),
                    'can_sync' => $integration->status !== 'synced',
                    'can_reverse' => $integration->status === 'synced',
                ]
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan status.'
            ], 500);
        }
    }
}