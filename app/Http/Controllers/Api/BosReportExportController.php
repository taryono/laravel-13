<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Models\BosReport;
use App\Services\Exports\BosSpjWordExporter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BosReportExportController extends Controller
{
    public function downloadWord(BosReport $bosReport, BosSpjWordExporter $exporter): BinaryFileResponse
    {
        $filePath = $exporter->export($bosReport);

        return response()
            ->download($filePath, basename($filePath))
            ->deleteFileAfterSend(true);
    }
}