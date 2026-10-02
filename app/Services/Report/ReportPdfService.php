<?php

namespace App\Services\Report;

use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdf;

/**
 * Satu-satunya tempat yang tahu cara mengubah HTML rapor menjadi PDF.
 * Dipakai controller (cetak satu) maupun job queue (cetak massal).
 */
class ReportPdfService
{
    private const MM_TO_PT = 72 / 25.4; // 2.83465

    /**
     * @param array $page  config['page'] dari snapshot template:
     *                     size, orientation, width, height (mm, hanya untuk Custom)
     */
    public function make(string $html, array $page = []): DomPdf
    {
        $pdf = Pdf::loadHTML($html);

        $size = $page['size'] ?? 'A4';
        $orientation = $page['orientation'] ?? 'portrait';

        if ($size === 'Custom' || strtoupper($size) === 'F4') {
            $w = (float) ($page['width'] ?? (strtoupper($size) === 'F4' ? 215 : 210));
            $h = (float) ($page['height'] ?? (strtoupper($size) === 'F4' ? 330 : 297));

            if ($orientation === 'landscape') {
                [$w, $h] = [$h, $w];
            }

            $pdf->setPaper([0, 0, $w * self::MM_TO_PT, $h * self::MM_TO_PT]);
        } else {
            $pdf->setPaper($size, $orientation);
        }

        $pdf->setOption([
            'defaultFont' => 'DejaVu Sans', // ganti dengan font yang sudah di-install (Arab, dsb.)
            'isRemoteEnabled' => false,         // logo/tanda tangan sebaiknya path lokal atau data URI
            'isHtml5ParserEnabled' => true,
        ]);

        return $pdf;
    }
}