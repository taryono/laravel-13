<x-guest-layout>
    <!-- Logo -->
    <div class="auth-logo">
        <div class="logo-box" style="background: linear-gradient(135deg, #00288e 0%, #1e40af 100%); color: white;">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <span>Portal Panitia PPDB</span>
    </div>

    <!-- Card -->
    <div class="auth-card">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h4 class="mb-0">Masuk Panitia PPDB 🔐</h4>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size: 0.75rem;">
                Akses Terbatas
            </span>
        </div>
        <p class="subtitle">Silakan masuk menggunakan akun Panitia atau Administrator PPDB.</p>

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
            <span id="loginAlertMsg">Email dan password wajib diisi.</span>
        </div>

        <form method="POST" action="{{ route('ppdb-admin.login.post') }}" id="loginForm">
            @csrf

            <!-- Email -->
            <label class="form-label">Email Administrator / Panitia</label>
            <div class="input-group-custom">
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" id="loginEmail" name="email" class="form-control"
                    placeholder="admin@contoh.id" value="{{ old('email', 'admin@contoh.id') }}" required autofocus />
            </div>
            <div class="invalid-msg" id="emailErr">Silakan masukkan email yang valid.</div>

            <!-- Password -->
            <label class="form-label mt-3">Kata Sandi</label>
            <div class="input-group-custom">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" id="loginPw" name="password" class="form-control"
                    placeholder="••••••••" value="rahasia123" required />
                <button type="button" class="toggle-pw" onclick="togglePw('loginPw', this)" tabindex="-1">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <div class="invalid-msg" id="pwErr">Silakan masukkan kata sandi Anda.</div>

            <!-- Remember Me -->
            <div class="row-opts mt-3 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" checked />
                    <label class="form-check-label" for="rememberMe" style="font-size: 0.85rem;">Ingat Saya</label>
                </div>
                <span class="text-muted" style="font-size: 0.78rem;">Demo: <code>admin@contoh.id</code></span>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary-edu mt-4 w-100" id="loginBtn" style="background: linear-gradient(135deg, #00288e 0%, #1e40af 100%);">
                <span id="btnText"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk Panel PPDB</span>
                <span id="btnLoading" style="display:none;">
                    <span class="spinner-border spinner-border-sm me-2"></span>Memverifikasi...
                </span>
            </button>
        </form>

        <div class="text-center mt-3 pt-2 border-top">
            <a href="{{ route('ppdb.index') }}" class="text-decoration-none text-muted" style="font-size: 0.85rem;">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda PPDB
            </a>
        </div>
    </div>

    <!-- Footer -->
    <div class="auth-footer text-center mt-3 text-muted" style="font-size: 0.8rem;">
        Sistem Informasi Penerimaan Peserta Didik Baru Terpadu
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
            const emailInput = document.getElementById('loginEmail');
            const pwInput = document.getElementById('loginPw');
            const emailErr = document.getElementById('emailErr');
            const pwErr = document.getElementById('pwErr');
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');
            const loginBtn = document.getElementById('loginBtn');

            emailInput.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                emailErr.classList.remove('show');
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

                if (emailInput.value.trim() === '') {
                    emailInput.classList.add('is-invalid');
                    emailErr.classList.add('show');
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
