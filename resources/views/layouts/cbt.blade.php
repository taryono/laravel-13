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

        //   Kirim detak setiap 30 detik
        setInterval(() => {
            fetch('/api/proctoring/heartbeat', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ session_id: SESSION_ID, status: 'in_progress' })
            });
        }, 30000);

        Kirim pelanggaran saat terdeteksi
        function reportViolation(type, desc, severity = 'warning') {
            fetch('/api/proctoring/log-activity', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({
                    session_id: SESSION_ID,
                    activity_type: type,
                    activity_data: { description: desc, url: window.location.href },
                    severity: severity
                })
            });
        }

        {{-- Contoh: Pergantian tab terdeteksi → laporkan --}}
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) reportViolation('tab_switch', 'Peserta meninggalkan halaman ujian', 'danger');
        });
    </script>

</body>
</html>