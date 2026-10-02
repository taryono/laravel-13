<?php
// app/Services/ReportCompilerService.php

namespace App\Services;

use App\Models\ReportTemplate;

class ReportCompilerService
{
    /**
     * Delimiter untuk Handlebars.
     * Pakai [[ ]] supaya tidak konflik dengan Blade {{ }}.
     */
    protected array $delimiters = ['[[', ']]'];

    /**
     * Compile JSON configuration menjadi HTML + CSS.
     */
    public function compile(array $config, ?ReportTemplate $template = null): array
    {
        $html = $this->buildHtml($config);
        $css = $this->buildCss($config, $template);

        return [
            'html' => $html,
            'css' => $css,
            'full' => $this->wrapDocument($html, $css),
        ];
    }

    // =========================================================
    // HTML BUILDER
    // =========================================================

    protected function buildHtml(array $config): string
    {
        $sections = collect($config['sections'] ?? [])
            ->sortBy('order')
            ->map(fn($s) => $this->renderSection($s))
            ->implode("\n");

        return '<div class="report-container">' . $sections . '</div>';
    }

    /**
     * Render satu section — FLAT STRUCTURE.
     * Setiap section = 1 elemen visual.
     */
    protected function renderSection(array $section): string
    {
        return match ($section['type'] ?? 'text') {
            'text' => $this->renderText($section),
            'image' => $this->renderImageSection($section),
            'keyvalue' => $this->renderKeyValueSection($section),
            'identity' => $this->renderKeyValueSection($section),  // alias backward-compat
            'table' => $this->renderTable($section),
            'text-block' => $this->renderTextBlock($section),
            'signature' => $this->renderSignature($section),
            'divider' => '<hr class="section-divider">',
            'pagebreak' => '<div class="page-break"></div>',
            // Backward compat — struktur lama dengan elements[]
            'header' => $this->renderElementContainer($section, 'section-header'),
            'footer' => $this->renderElementContainer($section, 'section-footer'),
            default => $this->renderText($section),
        };
    }

    // =========================================================
    // SECTION RENDERERS (FLAT)
    // =========================================================

    /**
     * Render text section — ambil dari 'content' langsung.
     */
    protected function renderText(array $s): string
    {
        // Kalau ada elements[] (struktur lama), render via container
        if (!empty($s['elements'])) {
            return $this->renderElementContainer($s, 'section-text');
        }

        $style = $this->inlineStyle($s['style'] ?? []);
        $content = $s['content'] ?? '';

        if ($content === '') {
            return '<div class="el-text" style="' . $style . '"></div>';
        }

        return sprintf('<div class="el-text" style="%s">%s</div>', $style, $content);
    }

    /**
     * Render image section — dari assetUrl/width/height langsung.
     */
    protected function renderImageSection(array $s): string
    {
        $src = $s['assetUrl'] ?? ($s['src'] ?? '');
        $width = $s['width'] ?? 80;
        $height = $s['height'] ?? 80;
        $align = $s['style']['textAlign'] ?? 'center';
        $mb = $s['style']['marginBottom'] ?? 10;

        if ($src === '') {
            return '<div class="el-image" style="text-align:' . e($align) . ';"></div>';
        }

        return sprintf(
            '<div class="el-image" style="text-align:%s;margin-bottom:%dpx;"><img src="%s" class="logo" style="width:%dpx;height:%dpx;object-fit:contain;display:inline-block;"></div>',
            e($align),
            (int) $mb,
            e($src),
            (int) $width,
            (int) $height
        );
    }

    /**
     * Render keyvalue section (identity) — flat structure.
     */
    protected function renderKeyValueSection(array $s): string
    {
        // Support struktur lama: keyvalue sebagai element di dalam elements[]
        $fields = $s['fields'] ?? [];
        if (empty($fields) && !empty($s['elements'])) {
            foreach ($s['elements'] as $el) {
                if (($el['type'] ?? '') === 'keyvalue' && !empty($el['fields'])) {
                    $fields = $el['fields'];
                    break;
                }
            }
        }

        $rows = '';
        foreach ($fields as $field) {
            $rows .= sprintf(
                '<tr><td class="label">%s</td><td class="separator">:</td><td class="value">[[ %s ]]</td></tr>',
                e($field['label'] ?? ''),
                e($field['binding'] ?? '')
            );
        }

        if ($rows === '') {
            return '<div class="section-identity"></div>';
        }

        return '<div class="section-identity"><table>' . $rows . '</table></div>';
    }

    /**
     * Render container dari elements[] — untuk backward compat.
     */
    protected function renderElementContainer(array $s, string $wrapperClass): string
    {
        $elements = collect($s['elements'] ?? [])
            ->map(fn($el) => $this->renderElement($el))
            ->implode("\n");

        return '<div class="' . $wrapperClass . '">' . $elements . '</div>';
    }

    /**
     * Render element (untuk struktur lama).
     */
    protected function renderElement(array $el): string
    {
        return match ($el['type'] ?? 'text') {
            'image' => $this->renderImageElement($el),
            'text' => $this->renderTextElement($el),
            'keyvalue' => $this->renderKeyValueElement($el),
            default => '',
        };
    }

    protected function renderImageElement(array $el): string
    {
        $src = $el['assetUrl'] ?? '';
        $width = $el['width'] ?? 80;
        $height = $el['height'] ?? 80;
        $align = $el['style']['textAlign'] ?? 'center';
        $mb = $el['style']['marginBottom'] ?? 10;

        if ($src === '')
            return '';

        return sprintf(
            '<div style="text-align:%s;margin-bottom:%dpx;"><img src="%s" class="logo" style="width:%dpx;height:%dpx;object-fit:contain;display:inline-block;"></div>',
            e($align),
            (int) $mb,
            e($src),
            (int) $width,
            (int) $height
        );
    }

    protected function renderTextElement(array $el): string
    {
        $style = $this->inlineStyle($el['style'] ?? []);
        $content = $el['content'] ?? '';

        return sprintf('<div class="el-text" style="%s">%s</div>', $style, $content);
    }

    protected function renderKeyValueElement(array $el): string
    {
        $rows = '';
        foreach ($el['fields'] ?? [] as $field) {
            $rows .= sprintf(
                '<tr><td class="label">%s</td><td class="separator">:</td><td class="value">[[ %s ]]</td></tr>',
                e($field['label'] ?? ''),
                e($field['binding'] ?? '')
            );
        }

        return '<div class="section-identity"><table>' . $rows . '</table></div>';
    }

    /**
     * Render table section.
     */
    protected function renderTable(array $s): string
    {
        $columns = $s['columns'] ?? [];
        $dataSource = $s['dataSource'] ?? 'data';

        // Headers
        $headers = collect($columns)->map(function ($col) {
            $width = isset($col['width']) ? ' style="width:' . e($col['width']) . '"' : '';
            return sprintf('<th%s>%s</th>', $width, e($col['header'] ?? ''));
        })->implode('');

        // Body rows (Handlebars each)
        $bodyCols = collect($columns)->map(function ($col) {
            $align = $col['align'] ?? 'left';
            $field = $col['field'] ?? '';

            if ($field === 'index') {
                $placeholder = '[[ @index ]]';
            } elseif ($field === '') {
                $placeholder = '';
            } else {
                $placeholder = '[[ ' . $field . ' ]]';
            }

            return sprintf('<td class="%s">%s</td>', e($align), $placeholder);
        })->implode("\n");

        $body = "[[#each {$dataSource}]]\n<tr>\n{$bodyCols}\n</tr>\n[[/each]]";

        // Title
        $title = !empty($s['title'])
            ? '<div class="section-title">' . e($s['title']) . '</div>'
            : '';

        // Footer
        $footer = '';
        if (!empty($s['showFooter'])) {
            $footerField = $s['footerField'] ?? 'average_score';
            $footerLabel = $s['footerLabel'] ?? 'Average';
            $colCount = count($columns);

            if ($colCount > 0) {
                $targetIdx = $s['footerTargetIndex'] ?? null;
                if ($targetIdx === null || $targetIdx >= $colCount) {
                    $found = collect($columns)->search(function ($col) {
                        return in_array($col['field'] ?? '', ['numeric_score', 'final_score', 'score']);
                    });
                    $targetIdx = ($found !== false) ? (int)$found : max(0, $colCount - 2);
                } else {
                    $targetIdx = (int)$targetIdx;
                }

                $beforeSpan = $targetIdx;
                $afterSpan  = $colCount - $targetIdx - 1;

                $labelTd = '';
                if ($beforeSpan > 0) {
                    $colspanAttr = $beforeSpan > 1 ? sprintf(' colspan="%d"', $beforeSpan) : '';
                    $labelTd = sprintf('<td%s class="right">%s</td>', $colspanAttr, e($footerLabel));
                }

                $valueTd = sprintf('<td class="center">[[ %s ]]</td>', e($footerField));

                $afterTd = '';
                if ($afterSpan > 0) {
                    $colspanAttr = $afterSpan > 1 ? sprintf(' colspan="%d"', $afterSpan) : '';
                    $afterTd = sprintf('<td%s></td>', $colspanAttr);
                }

                $footer = sprintf('<tfoot><tr>%s%s%s</tr></tfoot>', $labelTd, $valueTd, $afterTd);
            }
        }

        return <<<HTML
        <div class="section-table">
            {$title}
            <table>
                <thead><tr>{$headers}</tr></thead>
                <tbody>{$body}</tbody>
                {$footer}
            </table>
        </div>
        HTML;
    }

    protected function renderTextBlock(array $s): string
    {
        $title = !empty($s['title']) ? '<div class="section-title">' . e($s['title']) . '</div>' : '';
        $binding = $s['binding'] ?? 'content';

        return <<<HTML
        <div class="section-note">
            {$title}
            <div class="note-box">[[ {$binding} ]]</div>
        </div>
        HTML;
    }

    protected function renderSignature(array $s): string
    {
        $columns = collect($s['columns'] ?? [])->map(function ($col) {
            $label = e($col['label'] ?? '');
            $role = $col['role'] ?? 'official';
            $showImg = !empty($col['showImage']);
            $showNip = !empty($col['showEmployeeNumber']);

            $img = $showImg
                ? '<img src="[[ signatures.' . $role . '.image ]]" class="signature-image">'
                : '';
            $nip = $showNip
                ? '<div class="signature-number">NIP: [[ signatures.' . $role . '.employee_number ]]</div>'
                : '';

            return <<<HTML
            <div class="signature-col">
                <div class="signature-label">{$label},</div>
                {$img}
                <div class="signature-name">[[ signatures.{$role}.name ]]</div>
                {$nip}
            </div>
HTML;
        })->implode("\n");

        return '<div class="section-signature">' . $columns . '</div>';
    }

    // =========================================================
    // INLINE STYLE
    // =========================================================

    protected function inlineStyle(array $style): string
    {
        $map = [
            'fontSize' => 'font-size',
            'fontFamily' => 'font-family',
            'fontWeight' => 'font-weight',
            'fontStyle' => 'font-style',
            'textDecoration' => 'text-decoration',
            'color' => 'color',
            'textAlign' => 'text-align',
            'textTransform' => 'text-transform',
            'marginTop' => 'margin-top',
            'marginBottom' => 'margin-bottom',
            'padding' => 'padding',
        ];

        $out = [];
        foreach ($style as $key => $value) {
            if (!isset($map[$key]))
                continue;
            if ($value === null || $value === '')
                continue;

            $val = $value;
            if ($key === 'fontSize' && is_numeric($value)) {
                $val = $value . 'pt';
            } elseif (in_array($key, ['marginTop', 'marginBottom', 'padding']) && is_numeric($value)) {
                $val = $value . 'px';
            }

            $out[] = "{$map[$key]}:{$val}";
        }

        return implode(';', $out);
    }

    // =========================================================
    // CSS BUILDER
    // =========================================================

    protected function buildCss(array $config, ?ReportTemplate $template): string
    {
        $page = $config['page'] ?? [];
        $size = $page['size'] ?? ($template->paper_size ?? 'A4');
        $orient = $page['orientation'] ?? ($template->orientation ?? 'portrait');

        // Dimensi aktual
        if ($size === 'Custom') {
            $width = $page['width'] ?? $template?->custom_paper_width ?? 210;
            $height = $page['height'] ?? $template?->custom_paper_height ?? 297;
            $sizeCss = "{$width}mm {$height}mm";
        } else {
            $preset = config("report.paper_sizes.{$size}");
            if ($preset && isset($preset['width'], $preset['height'])) {
                $sizeCss = "{$preset['width']}mm {$preset['height']}mm";
            } else {
                $sizeCss = $size;
            }
        }

        $margin = $page['margin'] ?? [
            'top' => $template?->margin_top ?? 2,
            'right' => $template?->margin_right ?? 2,
            'bottom' => $template?->margin_bottom ?? 2,
            'left' => $template?->margin_left ?? 2.5,
        ];

        $global = $config['globalStyle'] ?? [];

        $marginCss = sprintf(
            '%scm %scm %scm %scm',
            $margin['top'] ?? 2,
            $margin['right'] ?? 2,
            $margin['bottom'] ?? 2,
            $margin['left'] ?? 2.5
        );

        return <<<CSS
            @page {
                size: {$sizeCss} {$orient};
                margin: {$marginCss};
            }

            * { box-sizing: border-box; margin: 0; padding: 0; }

            body {
                font-family: "{$this->esc($global['fontFamily'] ?? 'Times New Roman')}", serif;
                font-size: {$this->esc($global['fontSize'] ?? 12)}pt;
                line-height: {$this->esc($global['lineHeight'] ?? 1.5)};
                color: {$this->esc($global['color'] ?? '#000')};
                background: #fff;
            }

            .report-container { width: 100%; }

            /* HEADER */
            .section-header { text-align: center; margin-bottom: 20px; }
            .section-header .logo { display: block; margin: 0 auto 10px; object-fit: contain; }
            .section-header .title { font-size: 16pt; font-weight: bold; text-transform: uppercase; }
            .section-header .subtitle { font-size: 13pt; font-style: italic; margin-top: 4px; }

            /* IDENTITY */
            .section-identity { margin: 20px 0; }
            .section-identity table { width: 100%; border-collapse: collapse; font-size: 12pt; }
            .section-identity td { padding: 4px 8px; vertical-align: top; }
            .section-identity td.label { width: 25%; }
            .section-identity td.separator { width: 2%; }
            .section-identity td.value { width: 73%; font-weight: bold; }

            /* TABLE */
            .section-table { margin-bottom: 20px; page-break-inside: avoid; }
            .section-table .section-title { font-size: 13pt; font-weight: bold; margin-bottom: 8px; }
            .section-table table { width: 100%; border-collapse: collapse; font-size: 11pt; }
            .section-table th,
            .section-table td { border: 1px solid #000; padding: 6px 8px; }
            .section-table th { background: #e0e0e0; text-align: center; }
            .section-table td.center { text-align: center; }
            .section-table td.right  { text-align: right; }
            .section-table td.left   { text-align: left; }
            .section-table tfoot td { background: #f5f5f5; font-weight: bold; }

            /* NOTE */
            .section-note { margin: 20px 0; }
            .section-note .section-title { font-weight: bold; margin-bottom: 6px; }
            .section-note .note-box {
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
                font-style: italic;
                min-height: 60px;
            }

            /* SIGNATURE */
            .section-signature {
                margin-top: 30px;
                display: flex;
                justify-content: space-between;
                page-break-inside: avoid;
            }
            .signature-col { text-align: center; width: 45%; }
            .signature-col .signature-label { margin-bottom: 60px; }
            .signature-col .signature-image {
                max-width: 120px;
                height: auto;
                margin: -50px auto 4px;
                display: block;
            }
            .signature-col .signature-name { font-weight: bold; text-decoration: underline; }
            .signature-col .signature-number { font-size: 10pt; color: #444; }

            /* FOOTER */
            .section-footer {
                margin-top: 20px;
                font-size: 9pt;
                color: #666;
                text-align: center;
                border-top: 1px solid #ccc;
                padding-top: 6px;
            }

            /* TEXT & IMAGE */
            .section-text { margin: 10px 0; }
            .el-text { line-height: 1.5; }
            .el-image { line-height: 0; }

            /* UTILITIES */
            .page-break { page-break-after: always; }
            .section-divider { border: none; border-top: 1px solid #ccc; margin: 20px 0; }

            @media print {
                body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                .no-print { display: none !important; }
            }
            CSS;
    }

    // =========================================================
    // DOCUMENT WRAPPER
    // =========================================================

    protected function wrapDocument(string $html, string $css): string
    {
        return <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
        <title>Report Card</title>
        <style>{$css}</style>
        </head>
        <body>
        {$html}
        </body>
        </html>
        HTML;
    }

    protected function esc($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    public function getDelimiters(): array
    {
        return $this->delimiters;
    }
}