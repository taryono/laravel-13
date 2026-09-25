<?php
// app/Services/ReportCard/PdfGenerator.php

namespace App\Services\ReportCard;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfGenerator
{
    public function __construct(
        protected TemplateParser $parser,
    ) {}

    /**
     * Render HTML dari Blade, lalu konversi ke PDF.
     * Return path file PDF yang tersimpan.
     */
    public function generate(array $templateData, array $reportData, string $filename): string
    {
        $template = $this->parser->parse($templateData);

        $html = view('report_cards.render', [
            'template' => $template,
            'data'     => $reportData,
        ])->render();

        $pdf = Pdf::loadHTML($html)
            ->setPaper(
                $template['meta']['page']['size']        ?? 'A4',
                $template['meta']['page']['orientation'] ?? 'portrait'
            )
            ->setOptions([
                'isRemoteEnabled'  => true,
                'isHtml5ParserEnabled' => true,
                'defaultFont'      => $template['style']['font_family'] ?? 'DejaVu Sans',
                'dpi'              => 150,
            ]);

        $dir = storage_path('app/public/report_cards');
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $path = $dir . '/' . $filename;
        $pdf->save($path);

        return 'report_cards/' . $filename;
    }

    /**
     * Return PDF sebagai download response.
     */
    public function stream(array $templateData, array $reportData, string $filename)
    {
        $template = $this->parser->parse($templateData);

        $html = view('report_cards.render', [
            'template' => $template,
            'data'     => $reportData,
        ])->render();

        return Pdf::loadHTML($html)
            ->setPaper(
                $template['meta']['page']['size']        ?? 'A4',
                $template['meta']['page']['orientation'] ?? 'portrait'
            )
            ->download($filename);
    }
}
