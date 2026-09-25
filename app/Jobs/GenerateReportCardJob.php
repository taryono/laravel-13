<?php
// app/Jobs/GenerateReportCardJob.php

namespace App\Jobs;

use App\Services\ReportCard\ReportCardRenderer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateReportCardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;
    public int $tries   = 3;

    public function __construct(
        public int $studentId,
        public int $periodId,
        public ?int $templateId = null,
    ) {}

    public function handle(ReportCardRenderer $renderer): void
    {
        $renderer->generate($this->studentId, $this->periodId, $this->templateId);
    }
}
