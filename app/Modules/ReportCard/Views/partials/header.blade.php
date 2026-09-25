<div style="text-align:center;">
    @if (!empty($template['header']['show_logo']))
        <img src="{{ public_path('assets/logo-sekolah.png') }}" style="height:80px; float:left;" alt="Logo">
    @endif

    @if (!empty($template['header']['show_photo']))
        <img src="{{ public_path('assets/photo-placeholder.png') }}" style="height:80px; float:right;" alt="Photo">
    @endif

    @foreach ($template['header']['lines'] ?? [] as $line)
        <div
            style="
            font-size: {{ $line['font_size'] ?? 11 }}px;
            font-weight: {{ !empty($line['bold']) ? 'bold' : 'normal' }};
            font-style: {{ !empty($line['italic']) ? 'italic' : 'normal' }};
            text-align: {{ $line['align'] ?? 'center' }};
            color: {{ $template['style']['header_color'] ?? '#000' }};
        ">
            {{ $line['text'] }}</div>
    @endforeach
</div>

<hr class="divider">

@if (!empty($template['header']['title']['text']))
    <div class="header-title"
        style="
        font-size: {{ $template['header']['title']['font_size'] ?? 14 }}px;
        font-weight: {{ !empty($template['header']['title']['bold']) ? 'bold' : 'normal' }};
        text-align: {{ $template['header']['title']['align'] ?? 'center' }};
        margin-bottom: {{ $template['header']['title']['spacing_after'] ?? 8 }}px;
    ">
        {{ $template['header']['title']['text'] }}
        @if (!empty($template['header']['title']['subtitle']))
            <div style="font-size:11px; font-weight:normal;">
                {{ $template['header']['title']['subtitle'] }}
            </div>
        @endif
    </div>
@endif
