<?php

namespace App\Modules\DepreciationLog\Controllers;

use App\DataTables\DepreciationLogDataTable;
use App\Http\Controllers\MainController;
use Models\DepreciationLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Models\FixedAsset;
use Models\ChartOfAccount;
use App\Services\JournalService;
use Throwable;

class DepreciationLogController extends MainController
{
    protected JournalService $journalService;

    public function __construct(JournalService $journalService)
    {
        parent::__construct(new DepreciationLog());
        $this->journalService = $journalService;
    }

    public function index(Request $request, DepreciationLogDataTable $dataTable)
    {
        if ($request->filled('draw')) {
            return $dataTable->ajax();
        }

        return $dataTable->render('Finance::depreciation_log.index');
    }

    public function create(): View
    {
        $log = new DepreciationLog();
        $assets = FixedAsset::where('status', 'active')->pluck('asset_name', 'id');

        $result = $this->getForm($log);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::depreciation_log.edit', compact('log', 'forms', 'groupedForms', 'assets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fixed_asset_id' => 'required|exists:fixed_assets,id',
            'depreciation_date' => 'required|date',
            'period' => 'required|date_format:Y-m',
            'depreciation_amount' => 'required|numeric|min:0',
            'accumulated_depreciation' => 'required|numeric|min:0',
            'book_value' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            $log = DB::transaction(function () use ($validated) {
                $log = DepreciationLog::create($validated);

                // Update asset book value
                $asset = $log->fixedAsset;
                $asset->accumulated_depreciation = $validated['accumulated_depreciation'];
                $asset->book_value = $validated['book_value'];
                $asset->current_period_depreciation = $validated['depreciation_amount'];
                $asset->save();

                // Create journal entry
                $this->createDepreciationJournal($log);

                return $log;
            });

            return $this->successResponse($request, 'Data penyusutan berhasil ditambahkan.', $log);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan penyusutan.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Data penyusutan gagal ditambahkan.');
        }
    }

    private function createDepreciationJournal(DepreciationLog $log): void
    {
        $asset = $log->fixedAsset;

        $journalItems = [];

        // Debit: Beban Penyusutan
        $journalItems[] = [
            'chart_of_account_id' => $asset->depreciation_chart_of_account_id,
            'debit' => $log->depreciation_amount,
            'credit' => 0,
            'description' => 'Penyusutan ' . $asset->asset_name,
        ];

        // Credit: Akumulasi Penyusutan
        $journalItems[] = [
            'chart_of_account_id' => $asset->chart_of_account_id,
            'debit' => 0,
            'credit' => $log->depreciation_amount,
            'description' => 'Akumulasi penyusutan ' . $asset->asset_name,
        ];

        $journal = $this->journalService->createJournal([
            'journal_date' => $log->depreciation_date,
            'description' => 'Penyusutan Aset ' . $asset->asset_name . '_' . $log->period,
            'reference_number' => 'DEP-' . $asset->asset_code,
            'items' => $journalItems,
            'notes' => 'Penyusutan aset #' . $asset->asset_code,
        ], $log->depreciation_amount, $log->depreciation_amount);

        $log->update(['journal_entry_id' => $journal->id]);
    }

    public function edit($id): View
    {
        $log = $this->model->findOrFail($id);
        $assets = FixedAsset::where('status', 'active')->pluck('asset_name', 'id');

        $result = $this->getForm($log);
        $forms = $result['forms'];
        $groupedForms = $result['grouped'];

        return view('Finance::depreciation_log.edit', compact('log', 'forms', 'groupedForms', 'assets'));
    }

    public function update(Request $request, $id)
    {
        $log = $this->model->findOrFail($id);

        $validated = $request->validate([
            'fixed_asset_id' => 'required|exists:fixed_assets,id',
            'depreciation_date' => 'required|date',
            'period' => 'required|date_format:Y-m',
            'depreciation_amount' => 'required|numeric|min:0',
            'accumulated_depreciation' => 'required|numeric|min:0',
            'book_value' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($log, $validated) {
                // Reverse old journal
                if ($log->journal_entry_id) {
                    $this->journalService->voidJournal($log->journalEntry);
                }

                $log->update($validated);

                // Update asset
                $asset = $log->fixedAsset;
                $asset->accumulated_depreciation = $validated['accumulated_depreciation'];
                $asset->book_value = $validated['book_value'];
                $asset->save();

                // Create new journal
                $this->createDepreciationJournal($log);
            });

            return $this->successResponse($request, 'Data penyusutan berhasil diperbarui.', $log);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui penyusutan.', ['exception' => $e, 'log_id' => $id]);
            return $this->errorResponse($request, 'Data penyusutan gagal diperbarui.');
        }
    }

    public function destroy(Request $request, $id): JsonResponse|RedirectResponse
    {
        try {
            $log = $this->model->findOrFail($id);

            DB::transaction(function () use ($log) {
                if ($log->journal_entry_id) {
                    $this->journalService->voidJournal($log->journalEntry);
                }
                $log->delete();
            });

            return $this->successResponse($request, 'Data penyusutan berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal menghapus penyusutan.', ['exception' => $e, 'log_id' => $id]);
            return $this->errorResponse($request, 'Data penyusutan gagal dihapus.');
        }
    }

    public function getByAsset($assetId): JsonResponse
    {
        try {
            $logs = DepreciationLog::where('fixed_asset_id', $assetId)
                ->orderBy('period', 'desc')
                ->get();

            return response()->json(['success' => true, 'data' => $logs]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil log penyusutan.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }

    public function getSummary($assetId): JsonResponse
    {
        try {
            $asset = FixedAsset::with('depreciationLogs')->findOrFail($assetId);

            $summary = [
                'asset_name' => $asset->asset_name,
                'purchase_price' => $asset->purchase_price,
                'salvage_value' => $asset->salvage_value,
                'useful_life' => $asset->useful_life,
                'total_depreciation' => $asset->accumulated_depreciation,
                'current_book_value' => $asset->book_value,
                'depreciation_method' => $asset->depreciation_method,
                'logs_count' => $asset->depreciationLogs->count(),
                'last_depreciation' => $asset->depreciationLogs->last(),
            ];

            return response()->json(['success' => true, 'data' => $summary]);
        } catch (Throwable $e) {
            Log::error('Gagal mengambil summary penyusutan.', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data.'], 500);
        }
    }

    public function bulkStore(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'asset_ids' => 'required|array',
            'asset_ids.*' => 'exists:fixed_assets,id',
            'period' => 'required|date_format:Y-m',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                foreach ($validated['asset_ids'] as $assetId) {
                    $asset = FixedAsset::findOrFail($assetId);

                    // Calculate depreciation
                    $depreciationAmount = $this->calculateDepreciationAmount($asset);

                    if ($depreciationAmount > 0 && $asset->book_value > $asset->salvage_value) {
                        $newBookValue = $asset->book_value_
                        $depreciationAmount;
                        $newAccumulated = $asset->accumulated_depreciation + $depreciationAmount;

                        // Create depreciation log
                        $log = DepreciationLog::create([
                            'fixed_asset_id' => $assetId,
                            'depreciation_date' => now()->format('Y-m-d'),
                            'period' => $validated['period'],
                            'depreciation_amount' => $depreciationAmount,
                            'accumulated_depreciation' => $newAccumulated,
                            'book_value' => $newBookValue,
                            'notes' => 'Bulk depreciation for period ' . $validated['period'],
                        ]);

                        // Update asset
                        $asset->accumulated_depreciation = $newAccumulated;
                        $asset->book_value = $newBookValue;
                        $asset->current_period_depreciation = $depreciationAmount;
                        $asset->save();

                        // Create journal
                        $this->createDepreciationJournal($log);
                    }
                }
            });

            return $this->successResponse($request, 'Penyusutan massal berhasil dilakukan.');
        } catch (Throwable $e) {
            Log::error('Gagal melakukan penyusutan massal.', ['exception' => $e, 'data' => $validated]);
            return $this->errorResponse($request, 'Gagal melakukan penyusutan massal.');
        }
    }
}
