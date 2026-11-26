@php
    $isAjax = request()->ajax();
    $statusValue = $cmsPost->status instanceof \BackedEnum ? $cmsPost->status->value : (string) $cmsPost->status;
    $statusLabel = $cmsPost->status instanceof \App\Enums\PostStatus ? $cmsPost->status->label() : strtoupper($statusValue);
@endphp

@if (!$isAjax)
@extends('layouts.app')
@section('content')
<div class="container py-4">
@endif

    <div class="alert alert-info d-flex justify-content-between align-items-center mb-3">
        <div>
            <i class="bi bi-info-circle me-1"></i> Mode Pratinjau Artikel (Status: <strong>{{ $statusLabel }}</strong>)
        </div>
        <button type="button" class="btn btn-warning btn-sm"
            data-toggle="ajax-modal" data-size="lg" data-method="GET"
            data-title="Edit Konten: {{ e($cmsPost->title) }}" data-url="{{ route('cms_post.edit', $cmsPost->id) }}"
            data-submit='<button class="btn btn-primary" type="submit" form="formCmsPost">Simpan Perubahan</button>'
            data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
            <i class="bi bi-pencil"></i> Edit Artikel Ini
        </button>
    </div>

    <article class="bg-white p-4 rounded border">
        @if($cmsPost->featured_image)
            <img src="{{ Str::startsWith($cmsPost->featured_image, 'http') ? $cmsPost->featured_image : asset('storage/' . $cmsPost->featured_image) }}" 
                 alt="{{ $cmsPost->title }}" class="img-fluid rounded mb-3 w-100" style="max-height: 380px; object-fit: cover;">
        @endif

        <h2 class="fw-bold mb-3">{{ $cmsPost->title }}</h2>

        <div class="d-flex align-items-center flex-wrap gap-3 text-muted border-bottom pb-3 mb-3 small">
            <span><i class="bi bi-person"></i> {{ $cmsPost->user->name ?? 'Penulis' }}</span>
            <span>&bull;</span>
            <span><i class="bi bi-folder"></i> {{ $cmsPost->category->name ?? 'Umum' }}</span>
            <span>&bull;</span>
            <span><i class="bi bi-calendar"></i> {{ $cmsPost->published_at ? \Carbon\Carbon::parse($cmsPost->published_at)->format('d M Y H:i') : 'Draft' }}</span>
        </div>

        @if($cmsPost->excerpt)
            <p class="lead text-secondary fst-italic mb-3">{{ $cmsPost->excerpt }}</p>
        @endif

        <div class="post-body" style="line-height: 1.8;">
            {!! $cmsPost->content !!}
        </div>
    </article>

@if (!$isAjax)
</div>
@endsection
@endif
