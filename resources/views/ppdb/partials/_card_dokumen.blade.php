{{-- resources/views/ppdb/partials/_card_dokumen.blade.php --}}
@php
    $documents = $applicant?->documents->keyBy('document_type') ?? collect();
    $docTypes = [
        'akta_kelahiran' => ['label' => 'Akta Kelahiran Calon Siswa', 'required' => true, 'icon' => 'description'],
        'kartu_keluarga' => [
            'label' => 'Kartu Keluarga (KK) Asli / Legalisir',
            'required' => true,
            'icon' => 'family_restroom',
        ],
        'rapor' => ['label' => 'Scan Rapor Semester 1 - 5 (SMP / MTs)', 'required' => true, 'icon' => 'menu_book'],
        'sertifikat' => [
            'label' => 'Sertifikat Prestasi / Piagam (Opsional)',
            'required' => false,
            'icon' => 'military_tech',
        ],
    ];
@endphp

<article class="ppdb-card mb-4">
    <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom"
        style="border-color:var(--bs-outline-variant) !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="card-number">5</div>
            <div>
                <h2 class="mb-0" style="font-size:1.1rem; font-weight:600;">Unggah Dokumen Berkas Persyaratan</h2>
                <p class="mb-0" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Format berkas PDF/JPG/PNG
                    maksimal 2MB per file</p>
            </div>
        </div>
        <span class="material-symbols-outlined" style="color:var(--bs-primary);">upload_file</span>
    </div>

    <div class="d-flex flex-column gap-3">
        @foreach ($docTypes as $type => $config)
            @php $doc = $documents->get($type); @endphp
            <div>
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <label class="form-label mb-0">
                        {{ $config['label'] }}
                        @if ($config['required'])
                            <span style="color:var(--bs-error);">*</span>
                        @endif
                    </label>
                    @if ($doc)
                        <span class="d-flex align-items-center gap-1"
                            style="font-size:0.7rem; color:var(--bs-success-dark); font-weight:500;">
                            <span class="material-symbols-outlined"
                                style="font-size:0.9rem; color:var(--bs-success);">check_circle</span>
                            Terunggah
                        </span>
                    @endif
                </div>
                <div class="file-box {{ $doc ? 'file-box-uploaded' : '' }}" id="file-box-{{ $type }}">
                    <div class="d-flex align-items-center gap-3 min-w-0">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0 rounded-2"
                            style="width:2.5rem;height:2.5rem;background:{{ $doc ? 'var(--bs-success-light)' : 'var(--bs-surface-container-highest)' }}; color:{{ $doc ? 'var(--bs-success-dark)' : 'var(--bs-primary)' }};">
                            <span class="material-symbols-outlined"
                                style="font-size:1.3rem;">{{ $config['icon'] }}</span>
                        </div>
                        <div class="min-w-0">
                            <div class="fw-bold text-truncate" style="font-size:0.8rem;"
                                id="filename-{{ $type }}">
                                {{ $doc->file_name ?? 'Belum ada file' }}
                            </div>
                            <div style="font-size:0.7rem; color:var(--bs-on-surface-variant);"
                                id="filesize-{{ $type }}">
                                @if ($doc)
                                    {{ number_format(($doc->file_size ?? 0) / 1024 / 1024, 2) }} MB • Diunggah
                                    {{ optional($doc->uploaded_at)->format('d M Y') ?? '-' }}
                                @else
                                    Format PDF/JPG/PNG max 2MB
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if ($doc)
                            <button type="button" class="btn-sm-custom" data-bs-toggle="modal"
                                data-bs-target="#modalPreview{{ ucfirst($type) }}">
                                <span class="material-symbols-outlined" style="font-size:0.9rem;">visibility</span>
                                Lihat
                            </button>
                        @endif
                        <label class="btn-sm-custom" style="cursor:pointer;">
                            <span class="material-symbols-outlined" style="font-size:0.9rem;">upload</span>
                            {{ $doc ? 'Ganti' : 'Pilih File' }}
                            <input type="file" hidden accept=".pdf,.jpg,.jpeg,.png"
                                onchange="uploadDocument(this, '{{ $type }}', {{ $applicant?->id ?? 'null' }})">
                        </label>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="info-alert">
            <span class="material-symbols-outlined mt-1" style="color:var(--bs-primary); font-size:1.1rem;">info</span>
            <p class="mb-0" style="font-size:0.8rem;">
                Pastikan dokumen terlihat jelas, tidak buram, dan teks dapat terbaca untuk mempercepat proses verifikasi
                panitia.
            </p>
        </div>
    </div>
</article>
