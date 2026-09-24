@php
    $identity = $template['identity'] ?? [];
    $fields = $identity['fields'] ?? [];
@endphp

@if (!empty($fields))
    <table class="identity-table">
        @foreach (array_chunk($fields, 2) as $pair)
            <tr>
                @foreach ($pair as $field)
                    @php
                        $key = $field['field_key'] ?? null;
                        $value = data_get($data, $key);
                        $label = $field['label'] ?? $key;
                        $width = $field['width'] ?? '50%';
                    @endphp
                    <td style="width: {{ $width }};">
                        <strong>{{ $label }}</strong> : {{ $value }}
                    </td>
                @endforeach
                @if (count($pair) === 1)
                    <td></td>
                @endif
            </tr>
        @endforeach
    </table>
@endif
