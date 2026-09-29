{{-- resources/views/ppdb/partials/_header.blade.php --}}
<header class="app-header">
    <div class="container-fluid" style="max-width: 1280px;">
        <div class="d-flex justify-content-between align-items-center py-2">
            <div class="d-flex align-items-center gap-2">
                <div class="brand-icon">
                    <span class="material-symbols-outlined">school</span>
                </div>
                <div>
                    <div class="fw-bold" style="color:var(--bs-primary); font-size:0.95rem; line-height:1.1;">
                        PPDB {{ $admission->academicYear->name ?? '2025/2026' }}
                    </div>
                    <div class="d-flex align-items-center gap-1"
                        style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                        <span class="d-inline-block rounded-circle"
                            style="width:0.4rem;height:0.4rem;background:var(--bs-success);"></span>
                        {{ $admission->school->name ?? 'Provinsi' }} • {{ $admission->name ?? 'Jalur Reguler' }}
                    </div>
                </div>
            </div>
            <div>
                <a href="#bantuan" class="btn btn-sm rounded-circle p-2" style="color:var(--bs-primary);"
                    aria-label="Pusat Bantuan PPDB">
                    <span class="material-symbols-outlined">help_outline</span>
                </a>
            </div>
        </div>
    </div>
</header>
