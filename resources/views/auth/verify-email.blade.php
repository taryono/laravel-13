<x-clean-layout>
    <div class="d-flex align-items-center justify-content-center min-vh-100 bg-light px-4">
        <div class="w-100" style="max-width: 480px;"> 
            {{-- Kartu Utama --}}
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body p-4 p-sm-5">
                    <h4 class="card-title text-center fw-bold mb-3">Verifikasi Alamat Email</h4>
                    
                    <p class="text-muted mb-4">
                        Terima kasih telah mendaftar! Sebelum melanjutkan, silakan verifikasi alamat email Anda dengan mengeklik tautan yang telah kami kirimkan. Jika tidak menerima email, Anda dapat meminta kirim ulang di bawah.
                    </p>

                    {{-- Pesan Status --}}
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success mb-4" role="alert">
                            Tautan verifikasi baru telah dikirim ke alamat email Anda.
                        </div>
                    @endif

                    {{-- Tombol Kirim Ulang --}}
                    <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
                        @csrf
                        <button type="submit" id="resendBtn" class="btn btn-dark w-100 py-2 mb-3" style="font-size: 1rem; border-radius: 0.5rem;">
                            <span id="btnText">Kirim Ulang Email Verifikasi</span>
                            <span id="btnLoader" class="d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Mengirim...
                            </span>
                        </button>
                    </form>

                    {{-- Penghitung Waktu --}}
                    <p id="timerText" class="text-center text-muted small d-none">
                        Silakan tunggu <span id="countdown" class="fw-semibold text-dark">05:00</span> sebelum meminta kirim ulang lagi
                    </p>

                    {{-- Tombol Keluar --}}
                    <div class="text-center mt-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none text-muted">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Kartu Kontak WhatsApp --}}
            <div class="card border-success-subtle bg-success-subtle bg-opacity-10 rounded-3">
                <div class="card-body p-3">
                    <p class="mb-2 small text-success-emphasis">
                        <i class="bi bi-info-circle me-1"></i>
                        Jika tetap tidak menerima email setelah beberapa kali percobaan:
                    </p>
                    <p class="mb-0 fw-medium">
                        Hubungi Admin via WhatsApp:
                        <a href="https://wa.me/628788373xxxx" target="_blank" class="text-success text-decoration-none fw-bold">
                            0878-8373-xxxx
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk Timer & Loader --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const resendBtn = document.getElementById('resendBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            const timerText = document.getElementById('timerText');
            const countdownEl = document.getElementById('countdown');
            const resendForm = document.getElementById('resendForm');

            const COOLDOWN_SECONDS = 5 * 60; // 5 menit
            let timerInterval;

            // Cek apakah masih ada waktu tunggu dari localStorage
            let endTime = localStorage.getItem('verificationCooldownEnd');
            if (endTime && Date.now() < parseInt(endTime)) {
                startCountdown(parseInt(endTime) - Date.now());
            }

            resendForm.addEventListener('submit', function (e) {
                // Tampilkan loader
                btnText.classList.add('d-none');
                btnLoader.classList.remove('d-none');
                resendBtn.disabled = true;

                // Simpan waktu berakhirnya cooldown
                endTime = Date.now() + COOLDOWN_SECONDS * 1000;
                localStorage.setItem('verificationCooldownEnd', endTime);

                // Setelah submit (simulasi selesai), mulai hitung mundur
                setTimeout(() => {
                    startCountdown(COOLDOWN_SECONDS * 1000);
                }, 800);
            });

            function startCountdown(durationMs) {
                resendBtn.disabled = true;
                btnText.classList.add('d-none');
                btnLoader.classList.add('d-none');
                timerText.classList.remove('d-none');

                let remaining = Math.ceil(durationMs / 1000);
                updateTimerDisplay(remaining);

                clearInterval(timerInterval);
                timerInterval = setInterval(() => {
                    remaining--;
                    if (remaining <= 0) {
                        clearInterval(timerInterval);
                        localStorage.removeItem('verificationCooldownEnd');
                        timerText.classList.add('d-none');
                        btnText.classList.remove('d-none');
                        resendBtn.disabled = false;
                        return;
                    }
                    updateTimerDisplay(remaining);
                }, 1000);
            }

            function updateTimerDisplay(seconds) {
                const m = Math.floor(seconds / 60);
                const s = seconds % 60;
                countdownEl.textContent = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
            }
        });
    </script>
</x-clean-layout>
