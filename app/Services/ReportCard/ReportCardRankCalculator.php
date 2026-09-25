<?php
// app/Services/ReportCard/ReportCardRankCalculator.php

namespace App\Services\ReportCard;

use App\Models\ReportCard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportCardRankCalculator
{
    /**
     * Hitung ranking untuk 1 kelas di 1 periode.
     *
     * Aturan ranking:
     *   - Rank 1 = average_score tertinggi
     *   - Tie-break 1: total_score tertinggi
     *   - Tie-break 2: student_id lebih kecil (konsisten)
     *
     * @return int Jumlah report_cards yang di-update
     */
    public function calculateForClass(int $classLevelId, int $periodId): int
    {
        // 1. Ambil semua report_cards di kelas & periode, urutkan
        $reportCards = ReportCard::query()
            ->where('class_level_id', $classLevelId)
            ->where('period_id', $periodId)
            ->where('deleted_at', null)
            ->where('is_active', 1)
            ->orderByDesc('average_score')
            ->orderByDesc('total_score')
            ->orderBy('student_id')
            ->get(['id', 'student_id', 'average_score', 'total_score']);

        if ($reportCards->isEmpty()) {
            return 0;
        }

        // 2. Hitung rank dengan handling tie
        $ranked = $this->assignRanks($reportCards);

        // 3. Update massal
        $updated = 0;
        DB::transaction(function () use ($ranked, &$updated) {
            foreach ($ranked as $row) {
                $affected = ReportCard::where('id', $row['id'])
                    ->update(['rank_in_class' => $row['rank']]);

                $updated += $affected;
            }
        });

        Log::info('Rank in class calculated', [
            'class_level_id' => $classLevelId,
            'period_id'      => $periodId,
            'total_updated'  => $updated,
        ]);

        return $updated;
    }

    /**
     * Hitung ranking lintas kelas dalam 1 level/angkatan.
     * Contoh: rank dari seluruh siswa kelas 7 (7A, 7B, 7C, ...).
     *
     * @param  int  $academicYearId
     * @param  int  $periodId
     * @param  array $classLevelIds  Daftar class_level_id yang termasuk 1 level
     * @return int
     */
    public function calculateForLevel(array $classLevelIds, int $periodId): int
    {
        $reportCards = ReportCard::query()
            ->whereIn('class_level_id', $classLevelIds)
            ->where('period_id', $periodId)
            ->where('deleted_at', null)
            ->where('is_active', 1)
            ->orderByDesc('average_score')
            ->orderByDesc('total_score')
            ->orderBy('student_id')
            ->get(['id', 'student_id', 'average_score', 'total_score']);

        if ($reportCards->isEmpty()) {
            return 0;
        }

        $ranked = $this->assignRanks($reportCards);

        $updated = 0;
        DB::transaction(function () use ($ranked, &$updated) {
            foreach ($ranked as $row) {
                $affected = ReportCard::where('id', $row['id'])
                    ->update(['rank_in_level' => $row['rank']]);

                $updated += $affected;
            }
        });

        return $updated;
    }

    /**
     * Hitung ranking untuk 1 periode penuh:
     *   - rank_in_class per kelas
     *   - rank_in_level per angkatan (jika class_level_ids diberikan)
     *
     * @param  int   $periodId
     * @param  array $levelGroups  Contoh: [ [7,8,9], [10,11,12] ] untuk SMP & SMA
     * @return array{classes:int, levels:int}
     */
    public function calculateForPeriod(int $periodId, array $levelGroups = []): array
    {
        // 1. Ambil semua kelas yang punya report_cards di periode ini
        $classIds = ReportCard::query()
            ->where('period_id', $periodId)
            ->where('deleted_at', null)
            ->where('is_active', 1)
            ->distinct()
            ->pluck('class_level_id')
            ->toArray();

        $classUpdated = 0;
        foreach ($classIds as $classId) {
            $classUpdated += $this->calculateForClass($classId, $periodId);
        }

        // 2. Ranking level (opsional)
        $levelUpdated = 0;
        foreach ($levelGroups as $group) {
            $levelUpdated += $this->calculateForLevel($group, $periodId);
        }

        return [
            'classes' => $classUpdated,
            'levels'  => $levelUpdated,
        ];
    }

    // ============================================================
    // HELPER: Assign ranks dengan tie handling
    // ============================================================
    /**
     * Assign rank ke collection report_cards.
     *
     * Menggunakan metode "competition ranking" dengan tie-break:
     *   - Nilai sama → rank sama
     *   - Rank berikutnya melompat
     *
     * Contoh:
     *   average: 90, 90, 85, 80
     *   rank:     1,  1,  3,  4
     *
     * @param  \Illuminate\Support\Collection  $reportCards
     * @return array<int, array{id:int, rank:int}>
     */
    protected function assignRanks($reportCards): array
    {
        $result = [];
        $prevAverage = null;
        $prevTotal   = null;
        $currentRank = 0;
        $counter     = 0;

        foreach ($reportCards as $rc) {
            $counter++;

            $isTie = $prevAverage !== null
                && (float) $rc->average_score === (float) $prevAverage
                && (float) $rc->total_score   === (float) $prevTotal;

            if (!$isTie) {
                $currentRank = $counter;
            }

            $result[] = [
                'id'   => $rc->id,
                'rank' => $currentRank,
            ];

            $prevAverage = $rc->average_score;
            $prevTotal   = $rc->total_score;
        }

        return $result;
    }
}

// 3. Cara Pakai
// 3.1 Hitung Ranking 1 Kelas
$calculator = app(\App\Services\ReportCard\ReportCardRankCalculator::class);

$calculator->calculateForClass(
    classLevelId: 7,   // kelas 7
    periodId: 1        // semester 1
);

// 3.2 Hitung Ranking 1 Level (Angkatan)

// Contoh untuk SMP (kelas 7, 8, 9)
$levelGroups = [
    [7, 8, 9],
];

$calculator->calculateForLevel($levelGroups, periodId: 1);

// 3.3 Hitung Semua untuk 1 Periode (Kelas + Level)

$levelGroups = [
    [7, 8, 9],    // SMP
    [10, 11, 12], // SMA
];

$result = $calculator->calculateForPeriod(
    periodId: 1,
    levelGroups: $levelGroups
);

echo "Kelas: " . $result['classes'] . " report_cards\n";
echo "Level: " . $result['levels'] . " report_cards\n";
