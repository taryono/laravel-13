{{-- Shared Module Sub Navigation --}}
<nav class="ep-subnav sticky-top px-3 px-sm-4 py-2 mb-3 rounded-4"
    aria-label="{{ $ariaLabel ?? ($title ?? 'Navigasi Modul') }}">
    <div class="ep-subnav-wrap d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('dashboard.back-to-home') }}" aria-label="Kembali ke Beranda"
                title="Kembali ke Beranda"
                class="back-to-home rounded-3 text-decoration-none">
                <span class="material-symbols-outlined ep-subnav-back-icon" aria-hidden="true">arrow_back</span>
            </a>
            <div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <h2 class="m-0 ep-subnav-title">{{ $title }}</h2>
                    @if (!empty($badge))
                        <span class="ep-subnav-badge">
                            <span class="ep-subnav-dot placeholder-glow" aria-hidden="true"><span
                                    class="placeholder rounded-circle w-100 h-100"
                                    style="display:block"></span></span>
                            {{ $badge }}
                        </span>
                    @endif
                </div>
                @if (!empty($subtitle))
                    <p class="m-0 ep-subnav-subtitle d-none d-sm-block">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        @if (!empty($metaValue))
            <div class="ep-subnav-meta px-3 py-1 rounded-3">
                <span class="material-symbols-outlined ep-subnav-meta-icon" aria-hidden="true">{{ $metaIcon ?? 'schedule' }}</span>
                <span>
                    @if (!empty($metaLabel))
                        {{ $metaLabel }}:
                    @endif
                    <strong>{{ $metaValue }}</strong>
                </span>
            </div>
        @endif
    </div>
</nav>

