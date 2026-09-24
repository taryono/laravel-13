{{-- resources/views/report_cards/render.blade.php --}}
@extends('ReportCard::base')

@section('content')
    @include('report_cards.partials.header', ['template' => $template, 'data' => $data])
    @include('report_cards.partials.identity', ['template' => $template, 'data' => $data])

    @foreach ($template['sections'] as $section)
        @php
            $sectionData = $data[$section['data_source'] ?? $section['key']] ?? [];
        @endphp

        <div class="section avoid-break">
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

                @default
                    {{-- fallback: tampilkan JSON mentah untuk debugging --}}
                    <pre style="font-size:9px;">{{ json_encode($sectionData, JSON_PRETTY_PRINT) }}</pre>
            @endswitch
        </div>
    @endforeach

    @include('report_cards.partials.footer', ['template' => $template, 'data' => $data])
@endsection
