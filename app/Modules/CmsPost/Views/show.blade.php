@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Post: {{ $cmsPost->title }}</h5>
            <div>
                <a href="{{ route('cms_post.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('cms_post.print', $cmsPost->id) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-printer"></i> Cetak
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    @if($cmsPost->featured_image)
                        <img src="{{ Str::startsWith($cmsPost->featured_image, 'http') ? $cmsPost->featured_image : asset('storage/' . $cmsPost->featured_image) }}" 
                             alt="{{ $cmsPost->title }}" class="img-fluid rounded mb-3">
                    @endif

                    <h2 class="fw-bold">{{ $cmsPost->title }}</h2>
                    <div class="text-muted mb-3">
                        <small>
                            <i class="bi bi-person"></i> {{ $cmsPost->user->name ?? 'Admin' }} &bull;
                            <i class="bi bi-folder"></i> {{ $cmsPost->category->name ?? 'Tanpa Kategori' }} &bull;
                            <i class="bi bi-calendar"></i> {{ $cmsPost->published_at ? $cmsPost->published_at->format('d M Y H:i') : '-' }} &bull;
                            <i class="bi bi-eye"></i> {{ $cmsPost->view_count }} tayangan
                        </small>
                    </div>

                    @if($cmsPost->excerpt)
                        <div class="lead mb-3 text-secondary">{{ $cmsPost->excerpt }}</div>
                    @endif

                    <div class="content mb-4">
                        {!! $cmsPost->content !!}
                    </div>

                    @if($cmsPost->tags && $cmsPost->tags->isNotEmpty())
                        <div class="tags mt-3">
                            <strong class="me-2"><i class="bi bi-tags"></i> Tags:</strong>
                            @foreach($cmsPost->tags as $tag)
                                <span class="badge bg-light text-dark border me-1">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="col-md-4 border-start">
                    <h6 class="fw-bold mb-3">Informasi Meta</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th class="text-muted" style="width: 120px;">Status:</th>
                            <td>
                                @php
                                    $color = match($cmsPost->status) {
                                        'published' => 'success',
                                        'scheduled' => 'info',
                                        'archived'  => 'secondary',
                                        default     => 'warning'
                                    };
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ ucfirst($cmsPost->status ?? 'draft') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Slug:</th>
                            <td><code>{{ $cmsPost->slug }}</code></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Featured:</th>
                            <td>{{ $cmsPost->is_featured ? 'Ya' : 'Tidak' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Komentar:</th>
                            <td>{{ $cmsPost->allow_comments ? 'Diizinkan' : 'Ditutup' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Meta Title:</th>
                            <td>{{ $cmsPost->meta_title ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Meta Desc:</th>
                            <td><small>{{ $cmsPost->meta_description ?? '-' }}</small></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
