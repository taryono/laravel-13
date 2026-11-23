<div class="container py-4">

    {{-- Ringkasan hitungan (read-only, diisi oleh proses kenaikan kelas) --}}
    <div class="card shadow-sm mb-3">
        <div class="card-header">
            <h6 class="mb-0">Ringkasan Proses</h6>
        </div>
        <div class="card-body">
            <div class="row text-center g-3">
                <div class="col-6 col-md">
                    <div class="fs-4 fw-semibold">{{ $promotion_batch->total_students ?? 0 }}</div><small
                        class="text-muted">Total Siswa</small>
                </div>
                <div class="col-6 col-md">
                    <div class="fs-4 fw-semibold">{{ $promotion_batch->processed_count ?? 0 }}</div><small
                        class="text-muted">Diproses</small>
                </div>
                <div class="col-6 col-md">
                    <div class="fs-4 fw-semibold text-success">{{ $promotion_batch->promoted_count ?? 0 }}</div><small
                        class="text-muted">Naik Kelas</small>
                </div>
                <div class="col-6 col-md">
                    <div class="fs-4 fw-semibold text-warning">{{ $promotion_batch->retained_count ?? 0 }}</div><small
                        class="text-muted">Tinggal Kelas</small>
                </div>
                <div class="col-6 col-md">
                    <div class="fs-4 fw-semibold text-primary">{{ $promotion_batch->graduated_count ?? 0 }}</div><small
                        class="text-muted">Lulus</small>
                </div>
            </div>
            <div class="progress mt-3" style="height: 8px;">
                <div class="progress-bar" style="width: {{ $promotion_batch->progress }}%"></div>
            </div>
            <small class="text-muted">Progres {{ $promotion_batch->progress }}%</small>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Edit Batch Kenaikan Kelas</h5>
        </div>
        <div class="card-body">
            {{ Form::model($promotion_batch, ['method' => 'PUT', 'route' => ['promotion_batch.update', $promotion_batch->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
            @include('PromotionBatch::form')
            {{ Form::close() }}
        </div>
    </div>
</div>
