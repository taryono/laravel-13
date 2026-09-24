<?php
// app/Services/ReportCard/TemplateParser.php

namespace App\Services\ReportCard;

class TemplateParser
{
    /**
     * Normalisasi template_data JSON agar aman dipakai di Blade.
     */
    public function parse(array $templateData): array
    {
        $default = [
            'meta'    => ['name' => 'Report Card', 'version' => '1.0'],
            'style'   => [],
            'header'  => [],
            'identity' => ['layout' => 'two_column', 'fields' => []],
            'sections' => [],
            'footer'  => [],
        ];

        $data = array_replace_recursive($default, $templateData);

        // Pastikan style punya default
        $data['style'] = array_replace_recursive([
            'font_family'  => 'DejaVu Sans',
            'font_size'    => 10,
            'header_color' => '#000000',
            'border_color' => '#333333',
            'table' => [
                'header_bg'      => '#E6EEF7',
                'header_bold'    => true,
                'row_alternate'  => '#F7FAFD',
                'border_width'   => 1,
            ],
        ], $data['style']);

        // Normalisasi section
        foreach ($data['sections'] as &$section) {
            $section['key']    = $section['key']    ?? 'section_' . uniqid();
            $section['title']  = $section['title']  ?? '';
            $section['type']   = $section['type']   ?? 'table';
            $section['columns'] = $section['columns'] ?? [];
            $section['fields'] = $section['fields'] ?? [];
        }
        unset($section);

        return $data;
    }

    /**
     * Ambil section berdasarkan tipe tertentu.
     */
    public function sectionsByType(array $template, string $type): array
    {
        return array_values(array_filter(
            $template['sections'] ?? [],
            fn($s) => ($s['type'] ?? '') === $type
        ));
    }
}
