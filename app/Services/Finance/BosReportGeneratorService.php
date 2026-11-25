<?php

namespace App\Services\Finance;

use Models\BosAllocation;
use Models\BosPeriod;
use Models\BosRealization;
use Models\BosReceipt;
use Models\BosReport;
use Models\BosReportItem;
use Carbon\Carbon;
use DomainException;
use Illuminate\Support\Facades\DB;
use Throwable;

class BosReportGeneratorService
{
    /**
     * Menghasilkan atau memperbarui draf laporan pertanggungjawaban (SPJ) BOS
     * berdasarkan rentang tanggal tertentu dan membekukan angkanya ke
     * bos_reports, bos_report_items, serta kolom snapshot JSON (report_data & item_data).
     *
     * @param array<string, mixed> $params
     * @param int $userId
     * @return BosReport
     * @throws Throwable
     */
    public function generate(array $params, int $userId): BosReport
    {
        return DB::transaction(function () use ($params, $userId) {
            $bosPeriod = BosPeriod::with('school')
                ->lockForUpdate()
                ->findOrFail($params['bos_period_id']);

            $startDate = Carbon::parse($params['start_date'])->toDateString();
            $endDate = Carbon::parse($params['end_date'])->toDateString();

            // 1. Cek apakah laporan dengan kode & periode yang sama sudah ada
            $existingReport = BosReport::where('school_id', $bosPeriod->school_id)
                ->where('bos_period_id', $bosPeriod->id)
                ->where('report_code', $params['report_code'])
                ->lockForUpdate()
                ->first();

            if ($existingReport && in_array(strtoupper($existingReport->status), ['SUBMITTED', 'VERIFIED'], true)) {
                throw new DomainException(
                    "Laporan '{$existingReport->report_name}' sudah berstatus {$existingReport->status} dan terkunci. Ubah ke DRAFT terlebih dahulu jika ingin meregenerasi."
                );
            }

            // 2. Hitung Total Penerimaan Kas BOS pada rentang tanggal laporan
            $receipts = BosReceipt::where('bos_period_id', $bosPeriod->id)
                ->whereBetween('receipt_date', [$startDate, $endDate])
                ->where('is_active', true)
                ->orderBy('receipt_date')
                ->get();

            $periodReceivedAmount = (float) $receipts->sum('amount');

            // Saldo awal periode sebelum start_date (penerimaan sebelumnya - realisasi sebelumnya)
            $previousReceipts = (float) BosReceipt::where('bos_period_id', $bosPeriod->id)
                ->where('receipt_date', '<', $startDate)
                ->where('is_active', true)
                ->sum('amount');

            $previousRealizations = (float) BosRealization::where('bos_period_id', $bosPeriod->id)
                ->where('realization_date', '<', $startDate)
                ->where('status', '!=', 'VOID')
                ->where('is_active', true)
                ->sum(DB::raw('amount + tax_amount'));

            $openingBalance = $previousReceipts - $previousRealizations;

            // 3. Ambil seluruh alokasi komponen BOS pada bos_periods terkait
            $allocations = BosAllocation::with('component')
                ->where('bos_period_id', $bosPeriod->id)
                ->where('is_active', true)
                ->get()
                ->keyBy('bos_component_id');

            // 4. Ambil seluruh realisasi BOS (non-VOID) pada rentang tanggal laporan beserta relasi RKAS & COA
            $realizations = BosRealization::with([
                'usagePlan.component',
                'rkasRealization.item.component',
                'rkasRealization.source',
                'vendor',
            ])
                ->where('bos_period_id', $bosPeriod->id)
                ->whereBetween('realization_date', [$startDate, $endDate])
                ->where('status', '!=', 'VOID')
                ->where('is_active', true)
                ->orderBy('realization_date')
                ->orderBy('id')
                ->get();

            // 5. Kelompokkan realisasi per bos_component_id
            $groupedByComponent = [];
            $bkuLedgerRows = []; // Untuk snapshot Buku Kas Umum (BKU)
            $runningBalance = $openingBalance;

            // Masukkan mutasi penerimaan ke snapshot BKU
            foreach ($receipts as $receipt) {
                $runningBalance += (float) $receipt->amount;
                $bkuLedgerRows[] = [
                    'date' => $receipt->receipt_date,
                    'ref_number' => $receipt->receipt_number,
                    'type' => 'RECEIPT',
                    'description' => "Penerimaan Dana BOS ({$receipt->bank_name} - {$receipt->transfer_reference})",
                    'debit' => (float) $receipt->amount,
                    'credit' => 0.00,
                    'balance' => round($runningBalance, 2),
                ];
            }

            // Kelompokkan dan susun baris pengeluaran
            foreach ($realizations as $realization) {
                $grossAmount = (float) $realization->amount + (float) $realization->tax_amount;
                $component = $realization->usagePlan?->component;
                $componentId = $component?->id ?? 0; // 0 untuk transaksi yang belum ter-mapping ke bos_usage_plans

                if (!isset($groupedByComponent[$componentId])) {
                    $groupedByComponent[$componentId] = [
                        'bos_component_id' => $component?->id,
                        'component_code' => $component?->code ?? 'UNMAPPED',
                        'component_name' => $component?->name ?? 'Belanja Tanpa Mapping Komponen BOS',
                        'rkas_source_id' => $realization->rkasRealization?->rkas_source_id,
                        'account_id' => $realization->rkasRealization?->item?->component?->account_id,
                        'realization_sum' => 0.00,
                        'tax_sum' => 0.00,
                        'transactions' => [],
                    ];
                }

                $groupedByComponent[$componentId]['realization_sum'] += $grossAmount;
                $groupedByComponent[$componentId]['tax_sum'] += (float) $realization->tax_amount;
                $groupedByComponent[$componentId]['transactions'][] = [
                    'bos_realization_id' => $realization->id,
                    'rkas_realization_id' => $realization->rkas_realization_id,
                    'journal_entry_id' => $realization->journal_entry_id,
                    'date' => $realization->realization_date,
                    'receipt_number' => $realization->receipt_number,
                    'description' => $realization->description,
                    'vendor_name' => $realization->vendor?->name,
                    'net_amount' => (float) $realization->amount,
                    'tax_amount' => (float) $realization->tax_amount,
                    'total_amount' => $grossAmount,
                ];

                $runningBalance -= $grossAmount;
                $bkuLedgerRows[] = [
                    'date' => $realization->realization_date,
                    'ref_number' => $realization->receipt_number,
                    'type' => 'EXPENSE',
                    'component_code' => $component?->code,
                    'description' => $realization->description,
                    'debit' => 0.00,
                    'credit' => $grossAmount,
                    'balance' => round($runningBalance, 2),
                ];
            }

            // 6. Gabungkan seluruh komponen dari bos_allocations + komponen yang memiliki realisasi
            $allComponentIds = collect(array_keys($allocations->toArray()))
                ->merge(array_keys($groupedByComponent))
                ->unique()
                ->values();

            $totalAllocation = 0.00;
            $totalRealization = 0.00;
            $reportItemsData = [];
            $k7SummaryRows = [];

            foreach ($allComponentIds as $compId) {
                $allocation = $allocations->get($compId);
                $realizationData = $groupedByComponent[$compId] ?? null;

                // Skip jika alokasi 0 dan realisasi 0
                if (!$allocation && !$realizationData) {
                    continue;
                }

                $allocAmount = (float) ($allocation?->allocation_amount ?? 0);
                $realizedAmount = (float) ($realizationData['realization_sum'] ?? 0);
                $remaining = $allocAmount - $realizedAmount;
                $percentage = $allocAmount > 0
                    ? round(($realizedAmount / $allocAmount) * 100, 2)
                    : 0.00;

                $compCode = $allocation?->component?->code ?? $realizationData['component_code'];
                $compName = $allocation?->component?->name ?? $realizationData['component_name'];

                $totalAllocation += $allocAmount;
                $totalRealization += $realizedAmount;

                $itemPayload = [
                    'bos_component_id' => $compId === 0 ? null : $compId,
                    'rkas_source_id' => $realizationData['rkas_source_id'] ?? null,
                    'account_id' => $realizationData['account_id'] ?? null,
                    'description' => "{$compCode} - {$compName}",
                    'allocation_amount' => $allocAmount,
                    'received_amount' => 0.00, // Penerimaan BOS masuk di level periode, bukan per komponen
                    'realization_amount' => $realizedAmount,
                    'remaining_amount' => $remaining,
                    'percentage' => $percentage,
                    'item_data' => [
                        'component_code' => $compCode,
                        'component_name' => $compName,
                        'max_percentage' => $allocation?->component?->max_percentage,
                        'min_percentage' => $allocation?->component?->min_percentage,
                        'tax_total' => $realizationData['tax_sum'] ?? 0.00,
                        'transaction_count' => count($realizationData['transactions'] ?? []),
                        'transactions' => $realizationData['transactions'] ?? [],
                    ],
                ];

                $reportItemsData[] = $itemPayload;

                // Ringkasan format K7 untuk disimpan di header JSON
                $k7SummaryRows[] = [
                    'component_code' => $compCode,
                    'component_name' => $compName,
                    'allocation_amount' => $allocAmount,
                    'realization_amount' => $realizedAmount,
                    'remaining_amount' => $remaining,
                    'absorption_percent' => $percentage,
                ];
            }

            $totalRemainingCash = $openingBalance + $periodReceivedAmount - $totalRealization;
            $overallPercentage = $totalAllocation > 0
                ? round(($totalRealization / $totalAllocation) * 100, 2)
                : 0.00;

            // 7. Susun Snapshot JSON Utama (bos_reports.report_data)
            $reportSnapshot = [
                'generated_at' => now()->toIso8601String(),
                'generated_by' => $userId,
                'school_id' => $bosPeriod->school_id,
                'bos_period' => [
                    'id' => $bosPeriod->id,
                    'code' => $bosPeriod->code,
                    'name' => $bosPeriod->name,
                    'authority' => $bosPeriod->authority,
                    'regulation_version' => $bosPeriod->regulation_version,
                ],
                'cash_summary' => [
                    'opening_balance' => round($openingBalance, 2),
                    'total_received' => round($periodReceivedAmount, 2),
                    'total_used' => round($totalRealization, 2),
                    'closing_balance' => round($totalRemainingCash, 2),
                ],
                'k7_rekapitulasi' => $k7SummaryRows,
                'bku_ledger' => $bkuLedgerRows,
            ];

            // 8. Simpan atau perbarui header bos_reports
            $report = BosReport::updateOrCreate(
                [
                    'school_id' => $bosPeriod->school_id,
                    'bos_period_id' => $bosPeriod->id,
                    'report_code' => $params['report_code'],
                ],
                [
                    'fiscal_period_id' => $params['fiscal_period_id'] ?? $bosPeriod->fiscal_period_id,
                    'report_name' => $params['report_name'] ?? "SPJ BOS - {$bosPeriod->name}",
                    'report_type' => $params['report_type'] ?? 'PERIODIC',
                    'period_number' => $params['period_number'] ?? $bosPeriod->period_number,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'total_allocation' => $totalAllocation,
                    'total_received' => $periodReceivedAmount,
                    'total_used' => $totalRealization,
                    'total_remaining' => $totalRemainingCash,
                    'percentage' => $overallPercentage,
                    'report_data' => $reportSnapshot,
                    'status' => 'DRAFT',
                    'is_active' => true,
                    'created_by' => $existingReport?->created_by ?? $userId,
                    'updated_by' => $userId,
                ]
            );

            // 9. Reset dan isi ulang detail bos_report_items
            BosReportItem::where('bos_report_id', $report->id)->delete();

            foreach ($reportItemsData as $itemRow) {
                BosReportItem::create(array_merge($itemRow, [
                    'bos_report_id' => $report->id,
                    'is_active' => true,
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]));
            }

            return $report->load('items');
        });
    }

    /**
     * Mengubah status laporan dari DRAFT menjadi SUBMITTED atau VERIFIED
     * sehingga snapshot terkunci dari perubahan ulang.
     */
    public function verify(int $bosReportId, int $verifierUserId): BosReport
    {
        return DB::transaction(function () use ($bosReportId, $verifierUserId) {
            $report = BosReport::lockForUpdate()->findOrFail($bosReportId);

            if ($report->status === 'VERIFIED') {
                throw new DomainException('Laporan BOS ini sudah berstatus VERIFIED.');
            }

            $report->update([
                'status' => 'VERIFIED',
                'verified_at' => now(),
                'verified_by' => $verifierUserId,
                'updated_by' => $verifierUserId,
            ]);

            return $report;
        });
    }
}