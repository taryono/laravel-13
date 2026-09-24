<div class="section-title">{{ $section['title'] }}</div>

<table class="data">
    <thead>
        <tr>
            @foreach ($section['columns'] as $col)
                <th style="width: {{ $col['width'] ?? 'auto' }}; text-align: {{ $col['align'] ?? 'left' }};">
                    {{ $col['label'] }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($rows as $i => $row)
            <tr>
                @foreach ($section['columns'] as $col)
                    @php
                        $key = $col['key'];
                        $value = $key === 'no' ? $i + 1 : data_get($row, $key);
                    @endphp
                    <td style="text-align: {{ $col['align'] ?? 'left' }};"
                        class="{{ !empty($col['bold']) ? 'bold' : '' }}">
                        {{ $value ?? '-' }}
                    </td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($section['columns']) }}" class="text-center">
                    Tidak ada data
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
