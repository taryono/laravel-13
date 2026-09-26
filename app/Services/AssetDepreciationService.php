<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Models\Asset;
use Models\AssetDepreciationLog;
use Throwable;

class AssetDepreciationService
{
    /**
     * Hitung nilai penyusutan untuk 1 periode tertentu (tanpa simpan).
     *
     * @return array{
     *     depreciation_amount: float,
     *     opening_book_value: float,
     *     accumulated_depreciation: float,
     *     closing_book_value: float
     * }
     */
    public function calculateForPeriod(Asset $asset, int $year, int $month): array
    {
        $openingBookValue  = (float) $asset->book_value;
        $accumulated       = (float) $asset->accumulated_depreciation;
        $depreciableAmount = (float) $asset->purchase_price - (float) $asset->salvage_value;

        // Bulan penyusutan ke-berapa (relatif sejak purchase_date)
        $monthIndex = $this->getMonthIndex($asset, $year, $month);

        $depreciation = $this->calculateByMethod($asset, $monthIndex, $depreciableAmount, $openingBookValue);

        // Batasi agar tidak melewati salvage value
        $remainingDepreciable = max(0, $openingBookValue - (float) $asset->salvage_value);
        $depreciation = min($depreciation, $remainingDepreciable);

        $newAccumulated = $accumulated + $depreciation;
        $closingBookValue = (float) $asset->purchase_price - $newAccumulated;

        return [
            'depreciation_amount'      => round($depreciation, 2),
            'opening_book_value'       => round($openingBookValue, 2),
            'accumulated_depreciation' => round($newAccumulated, 2),
            'closing_book_value'       => round($closingBookValue, 2),
        ];
    }

    /**
     * Generate & simpan log penyusutan untuk 1 bulan.
     * Idempotent: kalau sudah ada, tidak akan dobel.
     */
    public function postMonthly(Asset $asset, int $year, int $month, ?int $userId = null): ?AssetDepreciationLog
    {
        // Skip kalau aset sudah di-dispose
        if ($asset->isDisposed()) {
            return null;
        }

        // Skip kalau sudah ada log untuk periode ini
        $exists = AssetDepreciationLog::where('asset_id', $asset->id)
            ->where('period_year', $year)
            ->where('period_month', $month)
            ->exists();

        if ($exists) {
            return null;
        }

        $calc = $this->calculateForPeriod($asset, $year, $month);

        if ($calc['depreciation_amount'] <= 0) {
            return null;
        }

        try {
            return DB::transaction(function () use ($asset, $calc, $year, $month, $userId) {
                $log = AssetDepreciationLog::create([
                    'asset_id'                 => $asset->id,
                    'period_year'              => $year,
                    'period_month'             => $month,
                    'period_date'              => Carbon::create($year, $month, 1)->endOfMonth()->toDateString(),
                    'opening_book_value'       => $calc['opening_book_value'],
                    'depreciation_amount'      => $calc['depreciation_amount'],
                    'accumulated_depreciation' => $calc['accumulated_depreciation'],
                    'closing_book_value'       => $calc['closing_book_value'],
                    'depreciation_method'      => $asset->depreciation_method,
                    'purchase_price'           => $asset->purchase_price,
                    'salvage_value'            => $asset->salvage_value,
                    'useful_life_months'       => $asset->useful_life_months,
                    'is_posted'                => false,
                    'created_by'               => $userId,
                    'updated_by'               => $userId,
                ]);

                // Update accumulated di master asset
                $asset->update([
                    'accumulated_depreciation' => $calc['accumulated_depreciation'],
                    'updated_by'               => $userId,
                ]);

                return $log;
            });
        } catch (Throwable $e) {
            Log::error('Gagal posting penyusutan aset.', [
                'exception' => $e,
                'asset_id'  => $asset->id,
                'period'    => "{$year}-{$month}",
            ]);

            return null;
        }
    }

    /**
     * Batch posting untuk semua aset aktif pada periode tertentu.
     *
     * @return array{processed:int, skipped:int, failed:int}
     */
    public function postMonthlyBatch(int $year, int $month, ?int $userId = null): array
    {
        $stats = ['processed' => 0, 'skipped' => 0, 'failed' => 0];

        Asset::query()
            ->active()
            ->notDisposed()
            ->whereDate('purchase_date', '<=', Carbon::create($year, $month, 1)->endOfMonth())
            ->chunkById(100, function ($assets) use ($year, $month, $userId, &$stats) {
                foreach ($assets as $asset) {
                    $log = $this->postMonthly($asset, $year, $month, $userId);
                    $log ? $stats['processed']++ : $stats['skipped']++;
                }
            });

        return $stats;
    }

    /**
     * Hitung bulan ke-N sejak purchase_date (1-based).
     */
    private function getMonthIndex(Asset $asset, int $year, int $month): int
    {
        $purchase = Carbon::parse($asset->purchase_date)->startOfMonth();
        $target   = Carbon::create($year, $month, 1)->startOfMonth();

        return max(1, $purchase->diffInMonths($target) + 1);
    }

    /**
     * Perhitungan sesuai metode penyusutan.
     */
    private function calculateByMethod(
        Asset $asset,
        int $monthIndex,
        float $depreciableAmount,
        float $openingBookValue
    ): float {
        $totalMonths = max(1, (int) $asset->useful_life_months);

        // Sudah lewat masa manfaat
        if ($monthIndex > $totalMonths) {
            return 0;
        }

        return match ($asset->depreciation_method) {
            'straight_line'     => $this->straightLine($depreciableAmount, $totalMonths),
            'declining_balance' => $this->decliningBalance($asset, $openingBookValue, $totalMonths),
            'sum_of_years'      => $this->sumOfYears($depreciableAmount, $monthIndex, $totalMonths),
            'double_declining'  => $this->doubleDeclining($openingBookValue, $totalMonths),
            default             => $this->straightLine($depreciableAmount, $totalMonths),
        };
    }

    private function straightLine(float $depreciableAmount, int $totalMonths): float
    {
        return $depreciableAmount / $totalMonths;
    }

    private function decliningBalance(Asset $asset, float $openingBookValue, int $totalMonths): float
    {
        $purchase = (float) $asset->purchase_price;
        $salvage  = (float) $asset->salvage_value;
        $years    = $totalMonths / 12;

        if ($purchase <= 0 || $salvage <= 0 || $years <= 0) {
            return 0;
        }

        // Rate tahunan dari rumus declining balance
        $annualRate = 1 - pow($salvage / $purchase, 1 / $years);

        return ($openingBookValue * $annualRate) / 12;
    }

    private function sumOfYears(float $depreciableAmount, int $monthIndex, int $totalMonths): float
    {
        $totalYears = (int) ceil($totalMonths / 12);
        $sumYears   = ($totalYears * ($totalYears + 1)) / 2;

        $currentYear = (int) ceil($monthIndex / 12);
        $remainingYears = $totalYears - $currentYear + 1;

        // Beban tahunan dibagi 12
        return ($depreciableAmount * ($remainingYears / $sumYears)) / 12;
    }

    private function doubleDeclining(float $openingBookValue, int $totalMonths): float
    {
        $years = max(1, $totalMonths / 12);
        $rate  = 2 / $years; // double dari straight-line

        return ($openingBookValue * $rate) / 12;
    }
}
