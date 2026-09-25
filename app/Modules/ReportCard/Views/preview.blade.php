{{-- resources/views/report_cards/preview.blade.php --}}
@extends('report_cards.layouts.base')

@push('styles')
    <style>
        /* Override khusus preview web */
        body {
            background: #f0f2f5;
            padding: 20px;
        }

        .report-body {
            background: #ffffff;
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm 20mm 20mm 25mm;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }

        /* Sembunyikan watermark gelap di preview */
        .watermark {
            opacity: 0.03 !important;
        }
    </style>
@endpush

@section('content')
    @include('report_cards.partials.header', ['template' => $template, 'data' => $data])
    @include('report_cards.partials.identity', ['template' => $template, 'data' => $data])

    @foreach ($template['sections'] as $section)
        @php
            $sectionData = $data[$section['data_source'] ?? $section['key']] ?? [];
        @endphp

        <div class="section">
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
        </div>
    @endforeach

    @include('report_cards.partials.footer', ['template' => $template, 'data' => $data])
@endsection
