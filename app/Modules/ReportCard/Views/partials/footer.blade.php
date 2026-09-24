@php
    $footer = $template['footer'] ?? [];
    $signatures = $footer['signatures'] ?? [];
    $city = $footer['city'] ?? '';
    $date = $footer['show_date'] ?? true ? $data['signature']['date'] ?? now()->translatedFormat('d F Y') : '';
@endphp

@if ($city || $date)
    <div style="text-align:right; margin-top:16px;">
        {{ $city }}{{ $city && $date ? ', ' : '' }}{{ $date }}
    </div>
@endif

@if (!empty($signatures))
    <table class="footer-sign">
        <tr>
            @foreach ($signatures as $sig)
                @php
                    $name = data_get($data, 'signature.' . ($sig['name_field'] ?? ''));
                @endphp
                <td style="width: {{ 100 / count($signatures) }}%;">
                    <div>{{ $sig['role'] }}</div>
                    <div class="sign-space"></div>
                    <div class="bold">{{ $name ?? '________________' }}</div>
                </td>
            @endforeach
        </tr>
    </table>
@endif
