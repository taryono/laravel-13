{{-- resources/views/components/menu/icon.blade.php --}}
@if ($icon) 
    @if ($iconType === 'fa')
        <i class="{{ $icon }}"></i>
    @else
        <span class="material-symbols-outlined">{{ $icon }}</span>
    @endif
@endif
