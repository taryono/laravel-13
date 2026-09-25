<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $template['meta']['name'] ?? 'Report Card' }}</title>
    <style>
        @page {
            size: {{ $template['meta']['page']['size'] ?? 'A4' }} {{ $template['meta']['page']['orientation'] ?? 'portrait' }};
            margin: {{ $template['meta']['page']['margin']['top'] ?? 20 }}mm {{ $template['meta']['page']['margin']['right'] ?? 20 }}mm {{ $template['meta']['page']['margin']['bottom'] ?? 20 }}mm {{ $template['meta']['page']['margin']['left'] ?? 25 }}mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "{{ $template['style']['font_family'] ?? 'DejaVu Sans' }}", sans-serif;
            font-size: {{ $template['style']['font_size'] ?? 10 }}pt;
            color: #222;
            line-height: 1.4;
        }

        .header-title {
            color: {{ $template['style']['header_color'] ?? '#000' }};
        }

        .divider {
            border: none;
            border-top: {{ ($template['header']['divider']['type'] ?? 'single') === 'double' ? '3px double' : '1px solid' }} {{ $template['header']['divider']['color'] ?? '#000' }};
            margin: 6px 0 12px 0;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        table.data th {
            background: {{ $template['style']['table']['header_bg'] ?? '#E6EEF7' }};
            border: {{ $template['style']['table']['border_width'] ?? 1 }}px solid {{ $template['style']['border_color'] ?? '#333' }};
            padding: 5px 6px;
            font-weight: {{ $template['style']['table']['header_bold'] ?? true ? 'bold' : 'normal' }};
            text-align: center;
        }

        table.data td {
            border: {{ $template['style']['table']['border_width'] ?? 1 }}px solid {{ $template['style']['border_color'] ?? '#333' }};
            padding: 4px 6px;
            vertical-align: top;
        }

        table.data tr:nth-child(even) td {
            background: {{ $template['style']['table']['row_alternate'] ?? '#F7FAFD' }};
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .section-title {
            font-weight: bold;
            margin: 12px 0 6px 0;
            color: {{ $template['style']['header_color'] ?? '#000' }};
        }

        .identity-table {
            width: 100%;
            margin-bottom: 12px;
        }

        .identity-table td {
            padding: 2px 4px;
            vertical-align: top;
        }

        .box {
            border: 1px solid {{ $template['style']['border_color'] ?? '#333' }};
            padding: 6px 8px;
            min-height: 40px;
        }

        .footer-sign {
            width: 100%;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .footer-sign td {
            text-align: center;
            vertical-align: top;
            padding: 4px;
        }

        .sign-space {
            height: 50px;
        }

        .watermark {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate({{ $template['footer']['watermark']['rotation'] ?? -45 }}deg);
            font-size: {{ $template['footer']['watermark']['font_size'] ?? 60 }}px;
            color: #000;
            opacity: {{ $template['footer']['watermark']['opacity'] ?? 0.05 }};
            z-index: -1;
            white-space: nowrap;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    @if (!empty($template['footer']['watermark']['enabled']) && $template['footer']['watermark']['enabled'])
        <div class="watermark">{{ $template['footer']['watermark']['text'] ?? '' }}</div>
    @endif

    @include('report_cards.partials.header', ['template' => $template, 'data' => $data])
    @include('report_cards.partials.identity', ['template' => $template, 'data' => $data])

    @foreach ($template['sections'] as $section)
        @php
            $sectionData = $data[$section['data_source'] ?? $section['key']] ?? [];
        @endphp
        @switch($section['type'])
            @case('table')
                @include('report_cards.partials.section_table', [
                    'section' => $section,
                    'rows' => $sectionData,
                    'data' => $data,
                ])
            @break

            @case('descriptive')
                @include('report_cards.partials.section_descriptive', [
                    'section' => $section,
                    'row' => $sectionData,
                ])
            @break

            @case('inline')
                @include('report_cards.partials.section_inline', [
                    'section' => $section,
                    'row' => $sectionData,
                ])
            @break

            @case('textarea')
                @include('report_cards.partials.section_textarea', [
                    'section' => $section,
                    'row' => $sectionData,
                ])
            @break
        @endswitch
    @endforeach

    @include('report_cards.partials.footer', ['template' => $template, 'data' => $data])

</body>

</html>
