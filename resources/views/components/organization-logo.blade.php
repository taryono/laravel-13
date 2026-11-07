@props(['organization' => null, 'size' => 48])

@php
    $logoUrl = $organization?->logo
        ? asset('public/assets/images/logo/' . $organization->logo)
        : null;
@endphp

@if($logoUrl)
    <img 
        src="{{ $logoUrl }}" 
        alt="{{ $organization->name ?? 'Logo' }}"
        width="{{ $size }}"
        height="{{ $size }}"
        {{ $attributes->merge(['class' => 'object-contain rounded']) }}
    >
@else
    {{-- Fallback ke logo default Laravel --}}
    <x-logo {{ $attributes->merge(['class' => 'w-' . $size . ' h-' . $size]) }} />
@endif