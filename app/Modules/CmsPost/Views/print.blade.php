<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak: {{ $cmsPost->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Times New Roman', serif; color: #000; background: #fff; padding: 20px; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="container py-3" style="max-width: 800px;">
        <div class="no-print mb-4 d-flex justify-content-between align-items-center border-bottom pb-2">
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                Cetak Dokumen Ini
            </button>
            <button onclick="window.close()" class="btn btn-secondary btn-sm">
                Tutup
            </button>
        </div>

        <h2 class="fw-bold mb-2">{{ $cmsPost->title }}</h2>
        <div class="text-muted mb-3" style="font-size: 0.9rem;">
            Penulis: {{ $cmsPost->user->name ?? 'Admin' }} | Kategori: {{ $cmsPost->category->name ?? '-' }} | Tanggal: {{ $cmsPost->published_at ? $cmsPost->published_at->format('d/m/Y') : '-' }}
        </div>

        <hr>

        @if($cmsPost->excerpt)
            <div class="fst-italic mb-3">
                <strong>Ringkasan:</strong> {{ $cmsPost->excerpt }}
            </div>
        @endif

        <div class="content mt-3" style="line-height: 1.6;">
            {!! $cmsPost->content !!}
        </div>
    </div>
</body>
</html>
