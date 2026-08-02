{{-- resources/views/errors/404.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>404 - Halaman Tidak Ditemukan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #f5f5f5;
        }
        .error-container {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 { font-size: 72px; margin: 0; color: #e74c3c; }
        .message { color: #666; font-size: 18px; }
        .back-link { 
            display: inline-block; 
            margin-top: 20px; 
            padding: 10px 20px; 
            background: #3498db; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>404</h1>
        <p class="message">{{ $message ?? 'Halaman yang Anda cari tidak ditemukan' }}</p>
        <a href="{{ url('/') }}" class="back-link">Kembali ke Beranda</a>
    </div>
</body>
</html>