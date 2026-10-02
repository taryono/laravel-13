<x-guest-layout>
    <!-- Logo & Brand Header -->
    <div class="auth-logo">
        <div class="logo-box" style="background: linear-gradient(135deg, #00288e 0%, #0284c7 100%); color: white;">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <span>Portal Pendaftar PPDB</span>
    </div>

    <!-- Login Card -->
    <div class="auth-card">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h4 class="mb-0">Masuk Akun Calon Siswa 🎓</h4>
            <span class="badge bg-info-subtle text-primary border border-info-subtle px-2 py-1" style="font-size: 0.75rem;">
                Portal Siswa
            </span>
        </div>
        <p class="subtitle">Pantau status pendaftaran, verifikasi berkas, dan hasil seleksi PPDB Anda di sini.</p>

        <!-- Alert Session Error -->
        @if (session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3 mb-3" style="font-size:.83rem; border-radius:10px;">
                <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3 mb-3" style="font-size:.83rem; border-radius:10px;">
                <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Client-side Error Alert -->
        <div id="loginAlert" class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3 mb-3"
            style="display:none; font-size:.83rem; border-radius:10px;">
            <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
            <span id="loginAlertMsg">Nomor Registrasi dan Password wajib diisi.</span>
        </div>

        <form method="POST" action="{{ route('ppdb.login.post') }}" id="loginForm">
            @csrf

            <!-- Registration Number or NIK -->
            <label class="form-label">Nomor Registrasi / NIK</label>
            <div class="input-group-custom">
                <i class="bi bi-person-badge input-icon"></i>
                <input type="text" id="regNumber" name="registration_number" class="form-control"
                    placeholder="Contoh: REG-2025-0001 atau 3273xxxxxxxxxxxx" value="{{ old('registration_number') }}" required autofocus />
            </div>
            <div class="invalid-msg" id="regErr">Silakan masukkan Nomor Registrasi atau NIK yang valid.</div>

            <!-- Password -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <label class="form-label mb-0">Kata Sandi / Tanggal Lahir</label>
                <span class="text-muted" style="font-size: 0.75rem;">Format tgl lahir: <code>HHBBTTTT</code></span>
            </div>
            <div class="input-group-custom mt-1">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" id="loginPw" name="password" class="form-control"
                    placeholder="••••••••" required />
                <button type="button" class="toggle-pw" onclick="togglePw('loginPw', this)" tabindex="-1">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <div class="invalid-msg" id="pwErr">Silakan masukkan kata sandi atau tanggal lahir Anda.</div>

            <!-- Remember Me & Help -->
            <div class="row-opts mt-3 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" checked />
                    <label class="form-check-label" for="rememberMe" style="font-size: 0.85rem;">Ingat Saya</label>
                </div>
                <a href="{{ route('ppdb.index') }}#cek-status" class="text-decoration-none" style="font-size: 0.8rem; color: var(--bs-primary);">
                    Lupa No. Registrasi?
                </a>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary-edu mt-4 w-100" id="loginBtn" style="background: linear-gradient(135deg, #00288e 0%, #0284c7 100%);">
                <span id="btnText"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Dashboard</span>
                <span id="btnLoading" style="display:none;">
                    <span class="spinner-border spinner-border-sm me-2"></span>Memeriksa Data...
                </span>
            </button>
        </form>

        <div class="text-center mt-4 pt-3 border-top">
            <p class="text-muted mb-2" style="font-size: 0.82rem;">Belum memiliki akun pendaftaran?</p>
            <a href="{{ route('ppdb.index') }}#jalur" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 fw-bold" style="font-size: 0.82rem;">
                <i class="bi bi-pencil-square me-1"></i> Daftar PPDB Sekarang
            </a>
        </div>
    </div>

    <!-- Footer links -->
    <div class="auth-footer text-center mt-3 text-muted" style="font-size: 0.8rem;">
        <a href="{{ route('ppdb.index') }}" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda PPDB
        </a>
    </div>

    <script>
        function togglePw(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        function showError(msg) {
            const alertBox = document.getElementById('loginAlert');
            const alertMsg = document.getElementById('loginAlertMsg');
            alertMsg.textContent = msg;
            alertBox.style.display = 'flex';
        }

        function hideError() {
            document.getElementById('loginAlert').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const regInput = document.getElementById('regNumber');
            const pwInput = document.getElementById('loginPw');
            const regErr = document.getElementById('regErr');
            const pwErr = document.getElementById('pwErr');
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');
            const loginBtn = document.getElementById('loginBtn');

            regInput.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                regErr.classList.remove('show');
                hideError();
            });
            pwInput.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                pwErr.classList.remove('show');
                hideError();
            });

            form.addEventListener('submit', function(e) {
                let isValid = true;
                hideError();

                if (regInput.value.trim() === '') {
                    regInput.classList.add('is-invalid');
                    regErr.classList.add('show');
                    isValid = false;
                }

                if (pwInput.value.trim() === '') {
                    pwInput.classList.add('is-invalid');
                    pwErr.classList.add('show');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    return false;
                }

                loginBtn.disabled = true;
                btnText.style.display = 'none';
                btnLoading.style.display = 'inline-flex';
            });
        });
    </script>
</x-guest-layout>
