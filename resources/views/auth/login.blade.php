<x-guest-layout>
    <!-- Logo -->
    <div class="auth-logo">
        <div class="logo-box"><i class="bi bi-mortarboard-fill"></i></div>
        <span>EduPulse</span>
    </div>

    <!-- Card -->
    <div class="auth-card">
        <h4>Welcome back! 👋</h4>
        <p class="subtitle">Login to your student account to continue</p>

        <!-- Alert Error -->
        <div id="loginAlert" class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3 mb-3"
            style="display:none; font-size:.83rem; border-radius:10px;">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span id="loginAlertMsg">Invalid email or password.</span>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3 mb-3"
                style="font-size:.83rem; border-radius:10px;">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3 mb-3"
                style="font-size:.83rem; border-radius:10px;">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success d-flex align-items-center gap-2 py-2 px-3 mb-3"
                style="font-size:.83rem; border-radius:10px;">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <!-- Email -->
            <label class="form-label">Email / Student ID / admin@contoh.id</label>
            <div class="input-group-custom">
                <i class="bi bi-person input-icon"></i>
                <input type="text" id="loginEmail" name="email" class="form-control"
                    placeholder="Enter your email or ID" value="{{ old('email', 'admin@contoh.id') }}" required
                    autofocus />
            </div>
            <div class="invalid-msg" id="emailErr">Please enter your email or Student ID.</div>

            <!-- Password -->
            <label class="form-label">Password</label>
            <div class="input-group-custom">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" id="loginPw" name="password" class="form-control"
                    placeholder="Enter your password" value="{{ old('password', 'rahasia123') }}" required />
                <button type="button" class="toggle-pw" onclick="togglePw('loginPw', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <div class="invalid-msg" id="pwErr">Please enter your password.</div>

            <!-- Remember / Forgot -->
            <div class="row-opts mt-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" />
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>

            <!-- Submit → HAPUS onclick="handleLogin()" agar pakai validasi JS -->
            <button type="submit" class="btn-primary-edu" id="loginBtn">
                <span id="btnText"><i class="bi bi-box-arrow-in-right"></i> Login</span>
                <span id="btnLoading" style="display:none;"><span
                        class="spinner-border spinner-border-sm me-2"></span>Logging in...</span>
            </button>
        </form>

        <!-- Divider -->
        <div class="or-divider">or continue with</div>

        <!-- Social -->
        <button type="button" class="btn-social" disabled>
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" />
            Continue with Google
        </button>
        <button type="button" class="btn-social" disabled>
            <i class="bi bi-microsoft" style="color:#00A4EF; font-size:1.1rem;"></i>
            Continue with Microsoft
        </button>
    </div>

    <!-- Footer -->
    <div class="auth-footer d-none">
        Don't have an account? <a href="{{ route('register') }}">Sign up here</a>
    </div>
    <div class="auth-footer">
        <a href="{{ route('welcome.index') }}">Kembali ke Home</a>
    </div>

    <script>
        // ── Toggle Password Visibility ──
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

        // ── Show Error Message ──
        function showError(msg) {
            const alertBox = document.getElementById('loginAlert');
            const alertMsg = document.getElementById('loginAlertMsg');
            alertMsg.textContent = msg;
            alertBox.style.display = 'flex';
        }

        // ── Hide Error ──
        function hideError() {
            document.getElementById('loginAlert').style.display = 'none';
        }

        // ── Validasi Form Sebelum Submit ──
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('loginEmail');
            const pwInput = document.getElementById('loginPw');
            const emailErr = document.getElementById('emailErr');
            const pwErr = document.getElementById('pwErr');
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');
            const loginBtn = document.getElementById('loginBtn');

            // Sembunyikan error saat mengetik
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

            // Validasi saat submit
            form.addEventListener('submit', function(e) {
                let isValid = true;
                hideError();

                // Validasi Email
                if (emailInput.value.trim() === '') {
                    emailInput.classList.add('is-invalid');
                    emailErr.classList.add('show');
                    isValid = false;
                }

                // Validasi Password
                if (pwInput.value.trim() === '') {
                    pwInput.classList.add('is-invalid');
                    pwErr.classList.add('show');
                    isValid = false;
                }

                // ❌ Jika belum lengkap → STOP! Jangan reload/kirim!
                if (!isValid) {
                    e.preventDefault();
                    return false;
                }

                // ✅ Jika valid → tampilkan loading
                loginBtn.disabled = true;
                btnText.style.display = 'none';
                btnLoading.style.display = 'inline-flex';
            });
        });
    </script>
</x-guest-layout>
