{{-- resources/views/ppdb/partials/_hero.blade.php --}}
<section class="mb-4">
    <div class="ppdb-card">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                    @if ($admission->is_active && now()->between($admission->start_date, $admission->end_date))
                        <span class="badge-soft badge-success-soft">
                            <span class="rounded-circle"
                                style="width:0.4rem;height:0.4rem;background:var(--bs-success); animation:pulse 1.5s infinite;"></span>
                            Pendaftaran Dibuka
                        </span>
                    @else
                        <span class="badge-soft badge-warning-soft">
                            <span class="material-symbols-outlined" style="font-size:0.8rem;">schedule</span>
                            Pendaftaran Ditutup
                        </span>
                    @endif
                    <span class="badge-soft"
                        style="background:var(--bs-surface-container); color:var(--bs-on-surface-variant); border:none;">
                        {{ $admission->name }}
                    </span>
                </div>
                <h1 class="headline-lg-mobile mb-1">Formulir Pendaftaran Siswa Baru</h1>
                <p class="mb-0" style="font-size:0.8rem; color:var(--bs-on-surface-variant);">
                    Tahun Ajaran {{ $admission->academicYear->name ?? '2025/2026' }}. Lengkapi data berkas dengan valid
                    sesuai dokumen resmi Dukcapil dan Kemendikbudristek.
                </p>
            </div>
            <div class="timer-box flex-shrink-0">
                <span class="material-symbols-outlined" style="color:var(--bs-warning-dark);">timer</span>
                <div>
                    <div style="font-size:0.65rem; color:var(--bs-warning-dark);">Batas Pengisian Berkas</div>
                    <div class="fw-bold" style="font-size:0.8rem; color:var(--bs-warning-dark);">
                        {{ \Carbon\Carbon::parse($admission->end_date)->translatedFormat('d F Y') }}, 23:59 WIB
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
