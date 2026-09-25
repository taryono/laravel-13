<div class="section-title">{{ $section['title'] }}</div>

@php
    $key = $section['field_key'];
    $value = data_get($row, $key);
@endphp

<div class="box">{{ $value ?? '-' }}</div>
