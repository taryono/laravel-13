<?php
// app/Console/Commands/RecalculateRankCommand.php

namespace App\Console\Commands;

use App\Services\ReportCard\ReportCardRankCalculator;
use Illuminate\Console\Command;

class RecalculateRankCommand extends Command
{
    protected $signature = 'report:recalculate
        {--period=   : ID periode raport (wajib)}
        {--class=    : ID kelas (opsional, kalau kosong semua kelas)}
        {--level=    : Daftar class_level_id dipisah koma untuk ranking level (opsional)}
        {--fresh     : Reset semua rank dulu sebelum dihitung ulang}';

    protected $description = 'Hitung ulang ranking raport per kelas & level';

    public function handle(ReportCardRankCalculator $calculator): int
    {
        $periodId = (int) $this->option('period');
        if (!$periodId) {
            $this->error('--period wajib diisi.');
            return self::FAILURE;
        }

        // Reset rank (opsional)
        if ($this->option('fresh')) {
            \Models\ReportCard::where('period_id', $periodId)
                ->update(['rank_in_class' => null, 'rank_in_level' => null]);
            $this->info('Rank direset.');
        }

        // 1. Per kelas
        if ($classId = $this->option('class')) {
            $updated = $calculator->calculateForClass((int) $classId, $periodId);
            $this->info("Kelas #{$classId}: {$updated} raport di-update.");
        } else {
            $result = $calculator->calculateForPeriod($periodId);
            $this->info("Total kelas: {$result['classes']} raport di-update.");
        }

        // 2. Per level (opsional)
        if ($levelOption = $this->option('level')) {
            $classIds = array_map('intval', explode(',', $levelOption));
            $updated = $calculator->calculateForLevel($classIds, $periodId);
            $this->info("Level [" . implode(',', $classIds) . "]: {$updated} raport di-update.");
        }

        return self::SUCCESS;
    }
}
// # Recalculate 1 kelas
// php artisan report:recalculate --period=1 --class=7

// # Recalculate semua kelas di periode 1
// php artisan report:recalculate --period=1

// # Recalculate + reset dulu
// php artisan report:recalculate --period=1 --fresh

// # Recalculate ranking level SMP (kelas 7,8,9)
// php artisan report:recalculate --period=1 --level=7,8,9