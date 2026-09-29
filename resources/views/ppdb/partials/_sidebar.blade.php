{{-- resources/views/ppdb/partials/_sidebar.blade.php --}}
@php
    // Fallback jika $timelines tidak dikirim dari controller
    if (!isset($timelines)) {
        $timelines =
            $admission->timelines->count() > 0
                ? $admission->timelines
                : collect($admission->default_timelines)->map(fn($t) => (object) $t);
    }
@endphp
{{-- ============ JADWAL PPDB (TIMELINE) ============ --}}
<div class="ppdb-card mb-4" id="jadwal-section">
    <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom"
        style="border-color:var(--bs-outline-variant) !important;">
        <div class="d-flex align-items-center gap-2">
            <span class="material-symbols-outlined" style="color:var(--bs-primary);">calendar_month</span>
            <h3 class="mb-0" style="font-size:1rem; font-weight:600;">
                Jadwal PPDB {{ $admission->academic_year->name ?? date('Y') }}
            </h3>
        </div>
        @if ($admission->is_active && now()->between($admission->start_date, $admission->end_date))
            <span class="badge-soft badge-success-soft" style="font-size:0.65rem;">Aktif</span>
        @else
            <span class="badge-soft badge-warning-soft" style="font-size:0.65rem;">Tutup</span>
        @endif
    </div>

    @if ($timelines->count() > 0)
        <div class="timeline">
            @foreach ($timelines as $i => $timeline)
                @php
                    $startDate = \Carbon\Carbon::parse($timeline->start_date);
                    $endDate = \Carbon\Carbon::parse($timeline->end_date);
                    $isActive = now()->between($startDate, $endDate);
                    $isDone = now()->gt($endDate);
                    $stepNum = $i + 1;
                @endphp
                <div class="timeline-step">
                    <div class="timeline-dot {{ $isDone ? 'done' : ($isActive ? 'active' : '') }}">
                        @if ($isDone)
                            <span class="material-symbols-outlined" style="font-size:0.8rem;">check</span>
                        @else
                            <span class="dot-inner" style="{{ $isActive ? 'background:#fff;' : '' }}"></span>
                        @endif
                    </div>
                    <div class="p-3 rounded-3"
                        style="{{ $isActive ? 'background:rgba(221,225,255,0.3); border:1px solid rgba(0,40,142,0.2);' : '' }}">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                            <span class="fw-bold"
                                style="font-size:0.8rem; {{ $isActive ? 'color:var(--bs-primary);' : '' }}">
                                {{ $stepNum }}. {{ $timeline->phase_name }}
                            </span>
                            @if ($isActive)
                                <span class="badge"
                                    style="background:var(--bs-primary); color:#fff; font-size:0.55rem; text-transform:uppercase; letter-spacing:0.05em;">
                                    Sedang Berjalan
                                </span>
                            @endif
                        </div>
                        <p class="mb-0 mt-1" style="font-size:0.8rem;">
                            @if ($startDate->equalTo($endDate))
                                {{ $startDate->translatedFormat('d F Y') }}
                            @else
                                {{ $startDate->translatedFormat('d M') }} - {{ $endDate->translatedFormat('d M Y') }}
                            @endif
                        </p>
                        @if (!empty($timeline->description))
                            <p class="mb-0" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                                {{ $timeline->description }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted small mb-0">Jadwal belum tersedia.</p>
    @endif
</div>

{{-- ============ ESTIMASI KUOTA ============ --}}
<div class="ppdb-card mb-4">
    <div class="d-flex align-items-center gap-2 pb-3 mb-3 border-bottom"
        style="border-color:var(--bs-outline-variant) !important;">
        <span class="material-symbols-outlined" style="color:var(--bs-secondary);">pie_chart</span>
        <h3 class="mb-0" style="font-size:0.95rem; font-weight:700;">Estimasi Kuota Sekolah</h3>
    </div>
    <div class="d-flex flex-column gap-2">
        @php
            $quotas = $admission->quota_settings->take(3);
        @endphp
        @forelse($quotas as $quota)
            <div class="p-3 rounded-2 d-flex align-items-center justify-content-between"
                style="background:var(--bs-surface-container-low);">
                <div>
                    <div class="fw-bold" style="font-size:0.8rem;">
                        {{ $quota->study_program->name ?? ($quota->path->name ?? 'Jalur Umum') }}
                    </div>
                    <div style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                        Terisi {{ $quota->filled }} / {{ $quota->quota }} Siswa
                    </div>
                </div>
                <span class="badge-soft"
                    style="background:var(--bs-primary-fixed); color:var(--bs-primary); border:none; font-size:0.7rem; font-weight:700;">
                    Sisa {{ max(0, $quota->quota - $quota->filled) }}
                </span>
            </div>
        @empty
            {{-- Fallback: tampilkan jalur dari admission --}}
            @foreach ($admission->paths->take(3) as $path)
                <div class="p-3 rounded-2 d-flex align-items-center justify-content-between"
                    style="background:var(--bs-surface-container-low);">
                    <div>
                        <div class="fw-bold" style="font-size:0.8rem;">{{ $path->name }}</div>
                        <div style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                            Kuota {{ $path->quota }} siswa
                        </div>
                    </div>
                    <span class="badge-soft"
                        style="background:var(--bs-primary-fixed); color:var(--bs-primary); border:none; font-size:0.7rem; font-weight:700;">
                        {{ $path->quota }}
                    </span>
                </div>
            @endforeach
            @if ($admission->paths->count() === 0)
                <p class="text-muted small mb-0">Data kuota belum tersedia.</p>
            @endif
        @endforelse
    </div>
</div>

{{-- ============ PUSAT LAYANAN ============ --}}
<div class="help-card" id="bantuan">
    <div class="d-flex align-items-center gap-2 mb-2">
        <span class="material-symbols-outlined" style="color:var(--bs-secondary-container);">contact_support</span>
        <h3 class="mb-0" style="font-size:0.95rem; font-weight:700;">Pusat Layanan &amp; Pengaduan</h3>
    </div>
    <p class="mb-3" style="font-size:0.8rem; opacity:0.9;">
        Mengalami kendala sinkronisasi NIK atau titik peta koordinat rumah? Hubungi Posko Layanan Terpadu PPDB.
    </p>
    <div class="d-flex flex-column gap-2">
        <a href="tel:0224203344" class="help-link">
            <span class="d-flex align-items-center gap-2">
                <span class="material-symbols-outlined" style="font-size:1rem;">call</span>
                Hotline (022) 420-3344
            </span>
            <span class="material-symbols-outlined" style="font-size:0.85rem;">open_in_new</span>
        </a>
        <a href="https://wa.me/628112345678" target="_blank" rel="noopener" class="help-link">
            <span class="d-flex align-items-center gap-2">
                <span class="material-symbols-outlined" style="font-size:1rem;">chat</span>
                WhatsApp Helpdesk 24/7
            </span>
            <span class="material-symbols-outlined" style="font-size:0.85rem;">open_in_new</span>
        </a>
    </div>
</div>
