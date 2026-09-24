<div class="section-title">{{ $section['title'] }}</div>

<table class="data">
    <tbody>
        @foreach ($section['fields'] as $field)
            @php
                $key = $field['field_key'];
                $value = data_get($row, $key);
            @endphp
            <tr>
                <td style="width: 25%;" class="{{ !empty($field['bold']) ? 'bold' : '' }}">
                    {{ $field['label'] }}
                </td>
                <td>{{ $value ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
