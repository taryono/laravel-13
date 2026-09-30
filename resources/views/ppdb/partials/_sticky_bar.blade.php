{{-- resources/views/ppdb/partials/_sticky_bar.blade.php --}}
@php
    $progress = 0;
    if ($applicant) {
        $checks = [
            $applicant->nik,
            $applicant->name,
            $applicant->birth_date,
            $applicant->gender,
            $applicant->address,
            $applicant->previous_school,
            $applicant->parent_name,
            $applicant->parent_phone,
            $applicant->domicile?->latitude,
            $applicant->choices->count() > 0,
            $applicant->documents->count() >= 3,
        ];
        $progress = round((count(array_filter($checks)) / count($checks)) * 100);
    }
@endphp

<section class="sticky-action-bar">
    <div class="container-fluid" style="max-width:1280px;">
        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
            <div class="w-100 d-flex align-items-center justify-content-between justify-content-sm-start gap-3">
                <div>
                    <div style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                        Langkah 5 dari 5 Formulir {{ $progress >= 100 ? 'Selesai' : 'Belum Selesai' }}
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold" style="font-size:0.8rem; color:var(--bs-primary);">
                            {{ $progress }}% Kelengkapan Data
                        </span>
                        <span class="rounded-circle"
                            style="width:0.5rem;height:0.5rem;background:{{ $progress >= 100 ? 'var(--bs-success)' : 'var(--bs-warning)' }};"></span>
                    </div>
                </div>
                <div class="progress-thin" style="width:8rem;">
                    <div class="progress-bar" style="width:{{ $progress }}%;"></div>
                </div>
            </div>
            <div class="w-100 d-flex align-items-center gap-2">
                <button type="button" class="btn-outline-custom flex-grow-1 flex-sm-grow-0" onclick="saveDraft()">
                    <span class="material-symbols-outlined" style="font-size:1.1rem;">bookmark_border</span>
                    Simpan Draf
                </button>
                <button type="button" class="btn-primary-custom flex-grow-1 flex-sm-grow-0 px-4" data-bs-toggle="modal"
                    data-bs-target="#modalConfirm">
                    Kirim &amp; Lanjut Review
                    <span class="material-symbols-outlined" style="font-size:1.1rem;">arrow_forward</span>
                </button>
            </div>
        </div>
    </div>
</section>
