<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    {{-- Di halaman ujian --}}
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.20.0/dist/tf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

    <video id="webcam" autoplay muted playsinline style="display:none;"></video>
    <canvas id="detection-canvas" style="position:absolute;top:0;left:0;display:none;"></canvas>
</head>
<body>
    

    <script>
        // php artisan reverb:start    # Jalankan server WebSocket
        // php artisan queue:work       # Jika menggunakan antrean
        // 📤 Langkah 6: Pengiriman Detak Jantung & Log dari Halaman Ujian
        // Tambahkan ini ke halaman ujian peserta:

        // Di halaman ujian peserta
        const SESSION_ID = {{ $current_session->id }};
        const DETECTION_INTERVAL = 30000; // periksa setiap 30 detik
        const THRESHOLD_MULTIPLE_FACES = 1; // lebih dari 1 wajah = pelanggaran
        const THRESHOLD_CONFIDENCE = 0.7;

        let modelsLoaded = false;

        // Muat model sekali saat halaman dibuka
        async function loadModels() {
            const MODEL_URL = 'https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/weights';
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL),
            ]);
            modelsLoaded = true;
            console.log('✅ Model deteksi wajah siap');
            startDetectionLoop();
        }

        // Inisialisasi kamera
        async function initWebcam() {
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: { width: 640, height: 480 },
                audio: true 
            });
            document.getElementById('webcam').srcObject = stream;
        }

        // Analisis bingkai & laporkan pelanggaran
        async function analyzeFrame() {
            if (!modelsLoaded) return;

            const video = document.getElementById('webcam');
            const canvas = document.getElementById('detection-canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Deteksi wajah
            const detections = await faceapi.detectAllFaces(
                video,
                new faceapi.TinyFaceDetectorOptions({ scoreThreshold: THRESHOLD_CONFIDENCE })
            )
            .withFaceLandmarks();

            const count = detections.length;
            const report = {
                session_id: SESSION_ID,
                face_count: count,
                timestamp: new Date().toISOString(),
            };

            // === LOGIKA PELANGGARAN ===
            if (count === 0) {
                reportViolation('no_face_detected', 'Wajah tidak terlihat di kamera', 'danger', report);
            } else if (count > THRESHOLD_MULTIPLE_FACES) {
                reportViolation('multiple_faces', `${count} wajah terdeteksi — dilarang`, 'critical', report);
            } else {
                // Cek arah pandangan menggunakan titik wajah
                checkGazeDirection(detections[0], report);
            }
        }

        // Periksa apakah peserta sering menengok ke samping
        function checkGazeDirection(detection, baseReport) {
            const landmarks = detection.landmarks;
            const nose = landmarks.getNose();
            const jaw = landmarks.getJawOutline();
            
            // Hitung posisi hidung relatif ke wajah
            const leftJaw = jaw[0];
            const rightJaw = jaw[jaw.length - 1];
            const noseX = nose[30].x;
            const faceWidth = rightJaw.x - leftJaw.x;
            const centerX = leftJaw.x + faceWidth / 2;
            const offset = (noseX - centerX) / faceWidth; // -0.5 s/d +0.5

            const report = { ...baseReport, gaze_offset: offset.toFixed(3) };

            if (Math.abs(offset) > 0.25) {
                reportViolation('looking_away', 
                    offset > 0 ? 'Menengok ke kanan' : 'Menengok ke kiri', 
                    'warning', report);
            }
        }

        // Kirim pelanggaran ke Laravel
        function reportViolation(type, description, severity = 'warning', extraData = {}) {
            fetch('/api/proctoring/log-activity', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                },
                body: JSON.stringify({
                    session_id: SESSION_ID,
                    activity_type: type,
                    activity_data: extraData,
                    severity: severity,
                })
            });
        }

        // Jalankan berulang
        function startDetectionLoop() {
            analyzeFrame();
            setInterval(analyzeFrame, DETECTION_INTERVAL);
        }

        // Mulai
        document.addEventListener('DOMContentLoaded', async () => {
            await initWebcam();
            await loadModels();
        });
    </script>

</body>
</html>