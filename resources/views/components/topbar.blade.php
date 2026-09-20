<header class="bnsp-topbar d-flex align-items-center px-4 gap-3">
    <button class="bnsp-hamburger" id="bnspHamburger" aria-label="Tampilkan atau sembunyikan menu">
        <span class="material-symbols-outlined">menu</span>
    </button>

    <div class="bnsp-search position-relative flex-grow-1">
        <span class="material-symbols-outlined">search</span>
        <input type="text" class="form-control py-2" placeholder="Search certifications...">
    </div>

    <div class="d-flex align-items-center gap-2 ms-3">
        <!-- Toggle dark mode layout yang tampil saat halaman light mode -->
        <button class="btn btn-light me-2" id="darkModeToggle">
            <span class="material-symbols-outlined" id="darkModeIcon">dark_mode</span>
        </button>

        <!-- Toggle mode layout: sidebar <-> topbar -->
        <button class="bnsp-icon-btn bnsp-layout-toggle" type="button" id="bnspLayoutToggle"
            aria-label="Ganti mode tampilan menu" title="Ganti ke tampilan menu atas">
            <span class="material-symbols-outlined">view_agenda</span>
        </button>
        @if (auth()->check())
            <!-- Notifikasi -->
            <div class="dropdown">
                <button class="bnsp-icon-btn" type="button" id="notifDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="notification-badge">3</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown" style="width: 320px;">
                    <li>
                        <h6 class="dropdown-header">Notifikasi</h6>
                    </li>
                    <li class="notification-item unread">
                        <div class="notification-title">Pengajuan Disetujui</div>
                        <div class="notification-desc">Pengajuan sertifikasi CPOF telah disetujui.</div>
                        <div class="notification-time">2 menit lalu</div>
                    </li>
                    <li class="notification-item unread">
                        <div class="notification-title">Jadwal Ujian</div>
                        <div class="notification-desc">Jadwal ujian CPSP telah ditetapkan tanggal 20 Mei.</div>
                        <div class="notification-time">1 jam lalu</div>
                    </li>
                    <li class="notification-item">
                        <div class="notification-title">Pembaruan Dokumen</div>
                        <div class="notification-desc">Pedoman CCMS telah diperbarui.</div>
                        <div class="notification-time">Kemarin</div>
                    </li>
                </ul>
            </div>
            @if (isset($activeOrganization))
                {{-- Pemilih Organisasi --}}
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        {{ $activeOrganization?->name ?? 'Pilih Organisasi' }}
                    </button>
                    <ul class="dropdown-menu">
                        @foreach (auth()->user()->organizations as $org)
                            @if ($org->pivot->is_active)
                                <li>
                                    <form action="{{ route('organization.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="organization_id" value="{{ $org->id }}">
                                        <button type="submit"
                                            class="dropdown-item {{ $activeOrganization?->id == $org->id ? 'active' : '' }}">
                                            {{ $org->name }}
                                            @if ($org->pivot->is_default)
                                                <span class="badge bg-info ms-1">Utama</span>
                                            @endif
                                        </button>
                                    </form>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- Pengaturan Cepat -->
            <div class="dropdown">
                <button class="bnsp-icon-btn" type="button" id="settingsDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <span class="material-symbols-outlined">settings</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsDropdown" style="width: 240px;">
                    <li>
                        <h6 class="dropdown-header">Pengaturan Cepat</h6>
                    </li>
                    <li><a class="profile-dropdown-item" href="#">
                            <span class="material-symbols-outlined">palette</span> Tampilan & Tema
                        </a></li>
                    <li><a class="profile-dropdown-item" href="#">
                            <span class="material-symbols-outlined">notifications_active</span> Notifikasi
                        </a></li>
                    <li><a class="profile-dropdown-item" href="#">
                            <span class="material-symbols-outlined">language</span> Bahasa
                        </a></li>
                    <li><a class="profile-dropdown-item" href="#">
                            <span class="material-symbols-outlined">lock</span> Keamanan & Sandi
                        </a></li>
                    <li><a class="profile-dropdown-item" href="#">
                            <span class="material-symbols-outlined">help_center</span> Bantuan
                        </a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="profile-dropdown-item" href="#">
                            <span class="material-symbols-outlined">info</span> Tentang Portal
                        </a></li>
                </ul>
            </div>
            <div class="vr mx-2"></div>

            <!-- Avatar & Menu Profil -->
            <div class="dropdown">
                <div class="d-flex align-items-center gap-2" id="profileDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false" style="cursor:pointer;">
                    {{-- {{ auth()->user()->avatar_url }} --}}
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuC8QXqfhf55Db1G_7iCJ6RxOrqozW_8NK1rQcH2FbUf8Usecta3J_NRv33NA4-HMWCGsbCSxtVTxbs0fahJ02YfrUZWhY6pTBIb37NLj62m5uKwjltiZZe3mTbGMBrCe9_WwRJqqt2UR0Dw3Boz9Y6-F7We1Zq8eb7XzNDyoSblO2O5Xr7XXXT-Hu2A5uWEWD4541Pd_6m1RmH9QRjRSnEnn32EOTz8n0n4YPD-lZxcLNjOcrSZ7ypv7D4iCm4G5PlZupLNZY8rYZE"
                        class="bnsp-avatar" alt="Foto profil Asesi Testing">
                    <div class="d-none d-lg-block text-start">
                        <p class="mb-0 fw-bold small lh-1">{{ auth()->user()->name }}</p>
                        <p class="mb-0 text-muted" style="font-size:.75rem;">Pro Level</p>
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown" style="width: 220px;">
                    <li><span class="dropdown-header">Masuk sebagai:</span>
                        <div class="px-3 pb-2">
                            <p class="mb-0 fw-bold small">{{ Auth::user()->name }}</p>
                            <p class="mb-0 text-muted small">{{ Auth::user()->email }}</p>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="profile-dropdown-item" href="#"><span
                                class="material-symbols-outlined">person</span>
                            Profil Saya</a></li>
                    <li><a class="profile-dropdown-item" href="#"><span
                                class="material-symbols-outlined">verified_user</span> Sertifikasi Saya</a></li>
                    <li><a class="profile-dropdown-item" href="#"><span
                                class="material-symbols-outlined">settings</span>
                            Pengaturan Akun</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li>
                            <a class="profile-dropdown-item text-danger" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                                                    document.getElementById('logout-form').submit();"><span
                                    class="material-symbols-outlined">logout</span> Keluar
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        @endif
    </div>
</header>
