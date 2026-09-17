// resources/js/proctoring.js
async function waitForFaceApi(maxWait = 5000) {
    const start = Date.now();
    while (typeof faceapi === 'undefined') {
        if (Date.now() - start > maxWait) {
            throw new Error('face-api.js tidak kunjung tersedia');
        }
        await new Promise(r => setTimeout(r, 100));
    }
}

const SESSION_ID = CBT_CONFIG.sessionId;
const DETECTION_INTERVAL = CBT_CONFIG.detectionInterval ?? 5000;
const THRESHOLD_MULTIPLE_FACES = 1;
const THRESHOLD_CONFIDENCE = 0.7;
const DEBUG_MODE = CBT_CONFIG.debug ?? false;


let modelsLoaded = false;
let gazeOffCount = 0;
const GAZE_OFF_THRESHOLD = 3; // 3 frame berturut-turut

// Muat model sekali saat halaman dibuka
async function loadModels() {
    try {
        const MODEL_URL = 'https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/weights';
        await Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
            faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
            faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL),
        ]);
        modelsLoaded = true;
        console.log('✅ Model deteksi wajah siap');
        startDetectionLoop();
    } catch (error) {
        console.error('Gagal load model:', error);
        reportViolation('proctoring_error', 'Model deteksi gagal dimuat', 'critical', {
            error: error.message
        });
        alert('Gagal memuat model deteksi wajah. Proctoring tidak dapat dijalankan.');
        return;
    }
}

// Inisialisasi kamera
async function initWebcam() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({
            video: {
                width: 320,
                height: 240
            },
            audio: false
        });
        document.getElementById('webcam').srcObject = stream;
        return true;
    } catch (err) {
        console.error('Webcam error:', err);
        // tampilkan pesan ke user + log violation
        reportViolation('camera_denied', 'Akses kamera ditolak', 'critical', {});
        return false;
    }
}

// Analisis bingkai & laporkan pelanggaran
async function analyzeFrame() {
    if (!modelsLoaded) return;
    if (!SESSION_ID) return;

    const video = document.getElementById('webcam');
    const canvas = document.getElementById('detection-canvas');

    // Guard: pastikan video sudah ready
    if (!video.videoWidth || !video.videoHeight) return;

    // Samakan ukuran canvas dengan video
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    // Deteksi wajah
    const detections = await faceapi.detectAllFaces(
        video,
        new faceapi.TinyFaceDetectorOptions({
            scoreThreshold: THRESHOLD_CONFIDENCE
        })
    ).withFaceLandmarks();

    // === DRAWING ===
    // Karena canvas dan video ukurannya sama (640x480),
    // kita bisa langsung pakai resizeResults tanpa scaling
    const resizedDetections = faceapi.resizeResults(detections, {
        width: canvas.width,
        height: canvas.height
    });

    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);  // bersihkan frame lama

    // Gambar bounding box
    if (DEBUG_MODE) {
        faceapi.draw.drawDetections(canvas, resizedDetections, {
            boxColor: count > THRESHOLD_MULTIPLE_FACES ? '#ef4444' : '#10b981',
            lineWidth: 2,
            label: count > 1 ? `${count} wajah` : 'OK'
        });
    } else {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    // === LOGIKA PELANGGARAN ===
    const count = detections.length;
    const report = {
        session_id: SESSION_ID,
        face_count: count,
        timestamp: new Date().toISOString(),
    };

    if (count === 0) {
        reportViolation('no_face_detected', 'Wajah tidak terlihat di kamera', 'danger', report);
    } else if (count > THRESHOLD_MULTIPLE_FACES) {
        reportViolation('multiple_faces', `${count} wajah terdeteksi — dilarang`, 'critical', report);
    } else {
        checkGazeDirection(detections[0], report);
    }
}

// Periksa apakah peserta sering menengok ke samping
function checkGazeDirection(detection, baseReport) {
    const landmarks = detection.landmarks;
    const nose = landmarks.getNose();
    const jaw = landmarks.getJawOutline();

    const leftJaw = jaw[0];
    const rightJaw = jaw[jaw.length - 1];
    const noseX = nose[30].x;
    const faceWidth = rightJaw.x - leftJaw.x;
    const centerX = leftJaw.x + faceWidth / 2;
    const offset = (noseX - centerX) / faceWidth;

    const report = {
        ...baseReport,
        gaze_offset: offset.toFixed(3)
    };

    if (Math.abs(offset) > 0.25) {
        gazeOffCount++;                          // 👈 tambah counter
        if (gazeOffCount >= GAZE_OFF_THRESHOLD) { // 👈 baru lapor kalau sudah 3x
            reportViolation('looking_away',
                offset > 0 ? 'Menengok ke kanan' : 'Menengok ke kiri',
                'warning', report);
            gazeOffCount = 0;                    // 👈 reset setelah lapor
        }
    } else {
        gazeOffCount = 0;                        // 👈 reset kalau kembali normal
    }
}

// Kirim pelanggaran ke Laravel
function reportViolation(type, description, severity = 'warning', extraData = {}) {
    const csrfToken = CBT_CONFIG.csrfToken
        || document.querySelector('meta[name="csrf-token"]')?.content;

    if (!csrfToken) {
        console.error('CSRF token tidak ditemukan!');
        return;
    }

    fetch('/api/proctoring/log-activity', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            session_id: SESSION_ID,
            activity_type: type,
            activity_data: extraData,
            severity: severity,
        })
    })
        .then(r => {
            if (!r.ok) console.warn('Log violation gagal:', r.status);
        })
        .catch(err => console.error('Network error:', err));
}

// Jalankan berulang
let detectionTimer = null;

function startDetectionLoop() {
    if (detectionTimer) clearInterval(detectionTimer);
    gazeOffCount = 0;  // 👈 reset state
    analyzeFrame();
    detectionTimer = setInterval(analyzeFrame, DETECTION_INTERVAL);
}
window.addEventListener('beforeunload', () => clearInterval(detectionTimer));

let hiddenTimer = null;
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        clearInterval(detectionTimer);
        detectionTimer = null;
        hiddenTimer = setTimeout(() => {
            reportViolation('tab_hidden', 'Tab berpindah > 2 detik', 'critical', {});
        }, 2000);
    } else {
        clearTimeout(hiddenTimer);
        hiddenTimer = null;
        if (!detectionTimer && modelsLoaded) {
            startDetectionLoop();
        }
    }
});

document.addEventListener('DOMContentLoaded', async () => {
    try {
        await waitForFaceApi();
    } catch (err) {
        console.error(err);
        reportViolation('proctoring_error', 'face-api gagal dimuat', 'critical', {});
        return;
    }

    const ok = await initWebcam();
    if (!ok) {
        console.warn('Proctoring dilewati karena kamera tidak tersedia');
        return;
    }
    await loadModels();
});