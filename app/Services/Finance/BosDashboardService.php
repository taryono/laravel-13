<?php

namespace App\Services\Finance;

use Models\BosAllocation;
use Models\BosPeriod;
use Models\BosRealization;
use Models\BosReceipt;
use Models\BosReport;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BosDashboardService
{
    /**
     * Mengumpulkan seluruh data dashboard BOS untuk 1 periode aktif sekolah.
     *
     * @param int $bosPeriodId
     * @param array<string, mixed> $filters ['category' => 'ALL|SIPLAH|HONOR|SARPRAS|UTILITIES', 'search' => '...', 'per_page' => 5]
     * @return array<string, mixed>
     */
    public function getDashboardData(int $bosPeriodId, array $filters = []): array
    {
        $period = BosPeriod::with(['school', 'academicYear', 'fiscalPeriod'])
            ->findOrFail($bosPeriodId);

        // 1. Informasi Rekening Giro BOS (dari penerimaan terbaru pada periode ini)
        $latestReceipt = BosReceipt::with('bankAccount')
            ->where('bos_period_id', $period->id)
            ->where('is_active', true)
            ->latest('receipt_date')
            ->first();

        // 2. Agregasi Statistik Transaksi (Total, SIPLah, Honor Non-ASN, Sarpras & ATK, Utilitas)
        $txStats = BosRealization::query()
            ->leftJoin('bos_usage_plans', 'bos_realizations.bos_usage_plan_id', '=', 'bos_usage_plans.id')
            ->leftJoin('bos_components', 'bos_usage_plans.bos_component_id', '=', 'bos_components.id')
            ->where('bos_realizations.bos_period_id', $period->id)
            ->where('bos_realizations.status', '!=', 'VOID')
            ->where('bos_realizations.is_active', true)
            ->selectRaw('
                COUNT(bos_realizations.id) as total_count,
                COALESCE(SUM(bos_realizations.amount + bos_realizations.tax_amount), 0) as total_gross,
                SUM(CASE WHEN bos_realizations.is_siplah = 1 THEN 1 ELSE 0 END) as siplah_count,
                COALESCE(SUM(CASE WHEN bos_realizations.is_siplah = 1 THEN (bos_realizations.amount + bos_realizations.tax_amount) ELSE 0 END), 0) as siplah_gross,
                SUM(CASE WHEN bos_components.component_type = "HONORARIUM" THEN 1 ELSE 0 END) as honor_count,
                SUM(CASE WHEN bos_components.component_type IN ("SARPRAS", "PERPUSTAKAAN", "ATK") THEN 1 ELSE 0 END) as sarpras_count,
                SUM(CASE WHEN bos_components.component_type = "UTILITIES" THEN 1 ELSE 0 END) as utilities_count
            ')
            ->first();

        $totalGross = (float) ($txStats->total_gross ?? 0);
        $siplahGross = (float) ($txStats->siplah_gross ?? 0);
        $siplahRatio = $totalGross > 0 ? round(($siplahGross / $totalGross) * 100, 1) : 0.0;

        // 3. Kartu KPI Utama (4 Kartu Atas)
        $totalAllocation = (float) $period->total_allocation;
        $totalUsed = (float) $period->total_used;
        $totalRemaining = (float) $period->total_remaining;
        $absorptionPct = $totalAllocation > 0 ? round(($totalUsed / $totalAllocation) * 100, 1) : 0.0;

        $kpiCards = [
            'realization' => [
                'amount' => $totalUsed,
                'absorption_percent' => $absorptionPct,
                'target_percent' => 50.0, // Syarat minimum salur tahap berikutnya
                'remaining_allocation' => max(0, $totalAllocation - $totalUsed),
            ],
            'allocation' => [
                'total_amount' => $totalAllocation,
                'student_count' => $period->student_count ?? 0,
                'unit_cost_per_student' => (float) ($period->unit_cost_per_student ?? 0),
                'dapodik_cutoff_date' => $period->dapodik_cutoff_date?->toDateString(),
            ],
            'bank_cash' => [
                'balance' => $totalRemaining,
                'bank_name' => $latestReceipt?->bankAccount?->bank_name ?? $latestReceipt?->bank_name ?? '-',
                'branch_name' => $latestReceipt?->bankAccount?->branch_name ?? 'Cabang Utama',
                'account_number' => $latestReceipt?->bankAccount?->account_number ?? '-',
            ],
            'silpa' => [
                'amount' => (float) ($period->carry_over_amount ?? 0),
                'audit_status' => $period->audit_status ?? 'WTP / Clear',
            ],
        ];

        // 4. Widget Kanan: Alokasi Standar Pendidikan (SNP) / Komponen BOS
        $snpProgress = $this->getSnpAllocationProgress($period);

        // 5. Tabel Kiri: Daftar Transaksi Belanja Terakhir (Paginated & Filterable)
        $transactions = $this->getPaginatedTransactions($period->id, $filters);

        // 6. Laporan SPJ Terbaru & Status Kepatuhan / Penanggung Jawab
        $latestReport = BosReport::with(['submittedByUser', 'verifiedByUser'])
            ->where('bos_period_id', $period->id)
            ->where('is_active', true)
            ->latest('id')
            ->first();

        return [
            'period' => $period,
            'kpi_cards' => $kpiCards,
            'siplah_banner' => [
                'compliance_percent' => $siplahRatio,
                'rating_grade' => $siplahRatio >= 90 ? 'Tingkat Patuh A' : ($siplahRatio >= 75 ? 'Tingkat Patuh B' : 'Perlu Evaluasi'),
                'regulation_note' => "Sesuai Permendikbudristek ({$period->regulation_version}) tentang Juknis Pengelolaan Dana BOS.",
            ],
            'transaction_tabs' => [
                'all' => (int) ($txStats->total_count ?? 0),
                'siplah' => (int) ($txStats->siplah_count ?? 0),
                'honor' => (int) ($txStats->honor_count ?? 0),
                'sarpras' => (int) ($txStats->sarpras_count ?? 0),
                'utilities' => (int) ($txStats->utilities_count ?? 0),
            ],
            'transactions' => $transactions,
            'snp_progress' => $snpProgress,
            'compliance_box' => [
                'arkas_sync_status' => $period->arkas_sync_status ?? 'Tersinkron',
                'arkas_synced_at' => $period->arkas_synced_at?->toIso8601String(),
                'dinas_verification_status' => $period->dinas_verification_status ?? 'Lolos / Sah',
                'tte_document_number' => $latestReport?->tte_document_number,
                'tte_signed_at' => $latestReport?->tte_signed_at?->toIso8601String(),
                'signatories' => $latestReport?->report_data['signatories'] ?? [
                    'principal' => ['name' => $period->school?->principal_name ?? '-', 'nip' => $period->school?->principal_nip ?? '-'],
                    'treasurer' => ['name' => $period->school?->bos_treasurer_name ?? '-', 'nip' => $period->school?->bos_treasurer_nip ?? '-'],
                ],
            ],
            'deadline_notice' => [
                'minimum_absorption_percent' => 50,
                'report_deadline_date' => $period->end_date->toDateString(),
            ],
        ];
    }

    /**
     * Menghitung progres realisasi per komponen BOS / SNP beserta peringatan batas persentase (misal Max 50% Honor).
     */
    protected function getSnpAllocationProgress(BosPeriod $period): array
    {
        $allocations = BosAllocation::with('component')
            ->where('bos_period_id', $period->id)
            ->where('is_active', true)
            ->get();

        // Agregasi realisasi per bos_component_id
        $realizedByComponent = BosRealization::query()
            ->join('bos_usage_plans', 'bos_realizations.bos_usage_plan_id', '=', 'bos_usage_plans.id')
            ->where('bos_realizations.bos_period_id', $period->id)
            ->where('bos_realizations.status', '!=', 'VOID')
            ->where('bos_realizations.is_active', true)
            ->groupBy('bos_usage_plans.bos_component_id')
            ->selectRaw('bos_usage_plans.bos_component_id, SUM(bos_realizations.amount + bos_realizations.tax_amount) as used_amount')
            ->pluck('used_amount', 'bos_component_id');

        $totalPeriodAllocation = (float) $period->total_allocation;

        $items = $allocations->map(function (BosAllocation $alloc) use ($realizedByComponent, $totalPeriodAllocation) {
            $component = $alloc->component;
            $planned = (float) $alloc->allocation_amount;
            $used = (float) ($realizedByComponent[$alloc->bos_component_id] ?? 0);
            $remaining = max(0, $planned - $used);

            $realizationPct = $planned > 0 ? round(($used / $planned) * 100, 1) : 0.0;
            $shareOfTotal = $totalPeriodAllocation > 0 ? round(($used / $totalPeriodAllocation) * 100, 1) : 0.0;

            return [
                'component_id' => $component->id,
                'code' => $component->code,
                'name' => $component->name,
                'component_type' => $component->component_type,
                'planned_amount' => $planned,
                'used_amount' => $used,
                'remaining_amount' => $remaining,
                'realization_percent' => $realizationPct,
                'share_of_total_pct' => $shareOfTotal,
                'max_percentage' => $component->max_percentage !== null ? (float) $component->max_percentage : null,
                'is_over_limit' => $component->max_percentage !== null && $shareOfTotal > (float) $component->max_percentage,
            ];
        })->sortBy('order_index')->values();

        return [
            'total_planned' => (float) $allocations->sum('allocation_amount'),
            'items' => $items,
        ];
    }

    /**
     * Mengambil daftar transaksi realisasi BOS dengan filter tab dan pencarian.
     */
    protected function getPaginatedTransactions(int $bosPeriodId, array $filters): LengthAwarePaginator
    {
        $category = strtoupper((string) ($filters['category'] ?? 'ALL'));
        $search = trim((string) ($filters['search'] ?? ''));
        $perPage = (int) ($filters['per_page'] ?? 5);

        return BosRealization::with([
            'vendor',
            'usagePlan.component',
            'rkasRealization.item.subActivity.activity.program.standard',
        ])
            ->where('bos_period_id', $bosPeriodId)
            ->where('status', '!=', 'VOID')
            ->where('is_active', true)
            ->when($category !== 'ALL', function ($query) use ($category) {
                match ($category) {
                    'SIPLAH' => $query->where('is_siplah', true),
                    'HONOR' => $query->whereHas('usagePlan.component', fn($q) => $q->where('component_type', 'HONORARIUM')),
                    'SARPRAS' => $query->whereHas('usagePlan.component', fn($q) => $q->whereIn('component_type', ['SARPRAS', 'PERPUSTAKAAN', 'ATK'])),
                    'UTILITIES' => $query->whereHas('usagePlan.component', fn($q) => $q->where('component_type', 'UTILITIES')),
                    default => null,
                };
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('description', 'like', "%{$search}%")
                        ->orWhere('receipt_number', 'like', "%{$search}%")
                        ->orWhereHas('vendor', fn($v) => $v->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('realization_date')
            ->orderByDesc('id')
            ->paginate($perPage);
    }
}