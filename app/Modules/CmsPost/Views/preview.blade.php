@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="alert alert-info d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-info-circle me-1"></i> Mode Pratinjau Artikel (Status: <strong>{{ strtoupper($cmsPost->status) }}</strong>)
        </div>
        <a href="{{ route('cms_post.edit', $cmsPost->id) }}" class="btn btn-warning btn-sm">
            <i class="bi bi-pencil"></i> Edit Artikel Ini
        </a>
    </div>

    <article class="bg-white p-4 p-md-5 rounded shadow-sm">
        @if($cmsPost->featured_image)
            <img src="{{ Str::startsWith($cmsPost->featured_image, 'http') ? $cmsPost->featured_image : asset('storage/' . $cmsPost->featured_image) }}" 
                 alt="{{ $cmsPost->title }}" class="img-fluid rounded mb-4 w-100" style="max-height: 450px; object-fit: cover;">
        @endif

        <h1 class="fw-bold mb-3">{{ $cmsPost->title }}</h1>

        <div class="d-flex align-items-center gap-3 text-muted border-bottom pb-3 mb-4">
            <span><i class="bi bi-person"></i> {{ $cmsPost->user->name ?? 'Penulis' }}</span>
            <span>&bull;</span>
            <span><i class="bi bi-folder"></i> {{ $cmsPost->category->name ?? 'Umum' }}</span>
            <span>&bull;</span>
            <span><i class="bi bi-calendar"></i> {{ $cmsPost->published_at ? $cmsPost->published_at->format('d M Y') : 'Draft' }}</span>
        </div>

        @if($cmsPost->excerpt)
            <p class="lead text-secondary fst-italic mb-4">{{ $cmsPost->excerpt }}</p>
        @endif

        <div class="post-body fs-5" style="line-height: 1.8;">
            {!! $cmsPost->content !!}
        </div>
    </article>
</div>
@endsection
