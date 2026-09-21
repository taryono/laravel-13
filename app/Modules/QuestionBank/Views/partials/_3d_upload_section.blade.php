{{--
    resources/views/admin/question_banks/partials/_3d_upload_section.blade.php

    PRASYARAT:
    1. layouts/app.blade.php harus punya @stack('scripts') dan @stack('styles')
    2. resources/js/network-quality-checker.js sudah dimuat (lihat @push di bawah)
    3. Alpine component `questionForm` punya state: networkStatus, has3DFile, netChecker
--}}

<div class="card mb-3">
    <div class="card-header fw-bold d-flex justify-content-between align-items-center">
        <span>Model 3D (opsional)</span>

        <span class="badge"
            :class="{
                'bg-success': networkStatus && networkStatus.status === 'stable',
                'bg-warning text-dark': networkStatus && networkStatus.status === 'unstable',
                'bg-danger': networkStatus && networkStatus.status === 'poor',
                'bg-secondary': !networkStatus,
            }">
            <i class="fa fa-wifi"></i>
            <span x-text="networkStatus ? networkStatus.label : 'Mengecek koneksi...'"></span>
        </span>
    </div>

    <div class="card-body">

        {{-- Banner peringatan --}}
        <div class="alert alert-warning d-flex align-items-start gap-2 mb-3"
            x-show="networkStatus && networkStatus.status !== 'stable'" x-cloak>
            <i class="fa fa-triangle-exclamation mt-1"></i>
            <div>
                <div class="fw-bold" x-text="networkStatus && networkStatus.label"></div>
                <div class="small" x-text="networkStatus && networkStatus.message"></div>
                <div class="small text-muted mt-1">
                    Estimasi kecepatan:
                    <span x-text="networkStatus && networkStatus.downlinkMbps"></span> Mbps
                </div>
            </div>
        </div>

        <label class="form-label">Upload Model 3D (.glb)</label>
        <input type="file" name="question_media_3d" class="form-control" accept=".glb"
            @change="onSelect3DFile($event)">
        <small class="text-muted d-block mt-1">
            Format wajib .glb (Draco compressed), maks 10MB.
        </small>

        @if (isset($question) && $question->relationLoaded('media'))
            @php($model3d = $question->media->firstWhere('media_type', 'model_3d'))
            @if ($model3d)
                <div class="mt-3">
                    <label class="form-label d-block">Model Saat Ini</label>
                    <model-viewer src="{{ Storage::url($model3d->file_path) }}"
                        style="width: 100%; max-width: 400px; height: 300px;" camera-controls auto-rotate loading="lazy"
                        reveal="auto">
                    </model-viewer>
                </div>
            @endif
        @endif

    </div>
</div>

@once
    @push('scripts')
        <script type="module" src="https://cdnjs.cloudflare.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js"></script>
    @endpush
@endonce
