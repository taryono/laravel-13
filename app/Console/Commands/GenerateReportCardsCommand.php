<?php
// app/Console/Commands/GenerateReportCardsCommand.php

namespace App\Console\Commands;

use App\Jobs\GenerateReportCardJob;
use App\Services\ReportCard\ReportCardRenderer;
use Models\ReportPeriod;
use Models\Student;
use Illuminate\Console\Command;

// php artisan report:generate --period=1 --class=7
// php artisan report:generate --period=1 --sync
// php artisan report:generate --period=1 --template=4

class GenerateReportCardsCommand extends Command
{
    protected $signature = 'report:generate
        {--period= : ID periode raport}
        {--class=  : ID kelas (opsional)}
        {--template= : ID template (opsional)}
        {--sync : Jalankan sinkron tanpa queue}';

    protected $description = 'Generate raport massal';

    public function handle(): int
    {
        $periodId   = (int) $this->option('period');
        $classLevel = $this->option('class');
        $templateId = $this->option('template') ? (int) $this->option('template') : null;

        if (!$periodId) {
            $this->error('--period wajib diisi.');
            return self::FAILURE;
        }

        ReportPeriod::findOrFail($periodId);

        $query = Student::where('deleted_at', null);
        if ($classLevel) {
            $query->where('class_level_id', (int) $classLevel);
        }

        $students = $query->pluck('id');
        $bar = $this->output->createProgressBar($students->count());
        $bar->start();

        foreach ($students as $studentId) {
            if ($this->option('sync')) {
                (new GenerateReportCardJob($studentId, $periodId, $templateId))
                    ->handle(app(\App\Services\ReportCard\ReportCardRenderer::class));
            } else {
                GenerateReportCardJob::dispatch($studentId, $periodId, $templateId);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Selesai. Total: {$students->count()} siswa.");

        return self::SUCCESS;
    }
}
