/**
 * network-quality-checker.js
 *
 * Mendeteksi kualitas koneksi internet pembuat soal, khusus untuk
 * konteks upload model 3D (butuh bandwidth lebih besar & stabil
 * dibanding upload gambar/dokumen biasa).
 *
 * Cara kerja:
 *   1. Baca Network Information API kalau browser mendukung (Chrome/Edge)
 *      -> hanya sebagai info tambahan, TIDAK dijadikan satu-satunya acuan
 *      karena tidak didukung Safari/Firefox dan kadang tidak akurat.
 *   2. Speed test aktif: download file kecil berukuran TETAP dari server
 *      sendiri (same-origin, hindari CORS & cache), ukur waktunya, hitung
 *      estimasi Mbps. Ini sumber kebenaran utama.
 *   3. Hasil diklasifikasikan jadi 3 status: stable | unstable | poor
 *
 * Cara pakai (lihat juga question_3d_upload_section.blade.php):
 *
 *   const checker = new NetworkQualityChecker({
 *     testFileUrl: '/assets/netcheck/probe-300kb.bin',
 *     testFileSizeKB: 300,
 *     checkInterval: 15000,
 *     onUpdate: (result) => { ...update UI banner... }
 *   });
 *   checker.start();
 */
class NetworkQualityChecker {
    constructor({
        testFileUrl,
        testFileSizeKB,
        onUpdate,
        checkInterval = 15000,
        thresholds = { goodMbps: 2, okMbps: 0.5, goodRtt: 150, okRtt: 400 },
    }) {
        if (!testFileUrl || !testFileSizeKB) {
            throw new Error('testFileUrl dan testFileSizeKB wajib diisi.');
        }
        this.testFileUrl = testFileUrl;
        this.testFileSizeKB = testFileSizeKB;
        this.onUpdate = onUpdate || (() => { });
        this.checkInterval = checkInterval;
        this.thresholds = thresholds;

        this.timer = null;
        this.lastResult = null;
        this._onlineHandler = () => this.check();
        this._offlineHandler = () => this._reportOffline();
    }

    start() {
        this.check();
        this.timer = setInterval(() => this.check(), this.checkInterval);
        window.addEventListener('online', this._onlineHandler);
        window.addEventListener('offline', this._offlineHandler);
    }

    stop() {
        clearInterval(this.timer);
        window.removeEventListener('online', this._onlineHandler);
        window.removeEventListener('offline', this._offlineHandler);
    }

    /** Panggil manual sebelum submit form, misal untuk konfirmasi terakhir. */
    async checkNow() {
        return this.check();
    }

    _reportOffline() {
        const result = {
            status: 'poor',
            label: 'Tidak ada koneksi internet',
            downlinkMbps: 0,
            rttMs: null,
            checkedAt: new Date(),
        };
        this.lastResult = result;
        this.onUpdate(result);
        return result;
    }

    async check() {
        if (!navigator.onLine) {
            return this._reportOffline();
        }

        const nav = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
        const apiHint = {
            downlinkMbps: nav?.downlink ?? null,
            effectiveType: nav?.effectiveType ?? null,
        };

        let downlinkMbps;
        let rttMs;

        try {
            const measured = await this._measureActual();
            downlinkMbps = measured.downlinkMbps;
            rttMs = measured.rttMs;
        } catch (err) {
            // request gagal total (timeout/error jaringan) = sinyal kuat koneksi bermasalah
            downlinkMbps = 0;
            rttMs = null;
        }

        const result = this._classify({ downlinkMbps, rttMs, apiHint });
        result.checkedAt = new Date();
        this.lastResult = result;
        this.onUpdate(result);
        return result;
    }

    async _measureActual() {
        const cacheBust = `?t=${Date.now()}`;
        const controller = new AbortController();
        const timeout = setTimeout(() => controller.abort(), 8000);

        const t0 = performance.now();
        const res = await fetch(this.testFileUrl + cacheBust, {
            cache: 'no-store',
            signal: controller.signal,
        });
        if (!res.ok) throw new Error('File speed test tidak bisa diakses (HTTP ' + res.status + ')');
        await res.blob();
        clearTimeout(timeout);
        const durationSec = (performance.now() - t0) / 1000;

        const bits = this.testFileSizeKB * 1024 * 8;
        const downlinkMbps = bits / durationSec / 1_000_000;
        // Estimasi kasar: asumsikan ~15% dari total waktu adalah latency murni,
        // sisanya waktu transfer. Bukan RTT presisi, tapi cukup untuk klasifikasi.
        const rttMs = Math.round(durationSec * 1000 * 0.15);

        return {
            downlinkMbps: Number(downlinkMbps.toFixed(2)),
            rttMs,
        };
    }

    _classify({ downlinkMbps, rttMs }) {
        const t = this.thresholds;

        if (downlinkMbps >= t.goodMbps && (rttMs === null || rttMs <= t.goodRtt)) {
            return {
                status: 'stable',
                label: 'Koneksi stabil',
                message: 'Koneksi Anda cukup baik untuk mengunggah model 3D.',
                downlinkMbps,
                rttMs,
            };
        }

        if (downlinkMbps >= t.okMbps) {
            return {
                status: 'unstable',
                label: 'Koneksi kurang stabil',
                message: 'Upload model 3D mungkin berjalan lambat. Disarankan cek jaringan atau coba lagi nanti.',
                downlinkMbps,
                rttMs,
            };
        }

        return {
            status: 'poor',
            label: 'Koneksi tidak stabil',
            message: 'Koneksi Anda saat ini terlalu lemah untuk mengunggah model 3D dengan andal. Upload berisiko gagal di tengah jalan.',
            downlinkMbps,
            rttMs,
        };
    }
}

// Export untuk kebutuhan bundler (Vite/Webpack). Kalau dipakai lewat <script>
// biasa (non-module), class ini otomatis tersedia secara global.
if (typeof module !== 'undefined' && module.exports) {
    module.exports = NetworkQualityChecker;
}