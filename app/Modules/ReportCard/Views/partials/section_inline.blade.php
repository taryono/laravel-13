<div class="section-title">{{ $section['title'] }}</div>

<table class="data">
    <tr>
        @foreach ($section['fields'] as $field)
            @php
                $key = $field['field_key'];
                $value = data_get($row, $key);
                $suffix = $field['suffix'] ?? '';
            @endphp
            <td style="text-align:center;">
                <strong>{{ $field['label'] }}</strong><br>
                {{ $value ?? 0 }} {{ $suffix }}
            </td>
        @endforeach
    </tr>
</table>
