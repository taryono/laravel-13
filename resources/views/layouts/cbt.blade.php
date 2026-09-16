<!DOCTYPE html>
<html lang="en">
{{-- resources/views/layouts/cbt.blade.php --}}

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/general.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/modal.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    <link
        href="https://cdn.datatables.net/v/bs5/moment-2.29.4/jszip-3.10.1/dt-3.0.0/af-3.0.0/b-4.0.0/cr-3.0.0/cc-2.0.0/date-2.0.0/fc-6.0.0/fh-5.0.0/r-4.0.0/rr-2.0.0/sc-3.0.0/sb-2.0.0/sl-4.0.0/datatables.min.css"
        rel="stylesheet" integrity="sha384-YNHRxm6Vr29HJTQ8v1aLtlWi3mpEkk1B+elXLCf/7tFakByky4LC7vDLiRM6Fh4t"
        crossorigin="anonymous">

    {{-- Di halaman ujian --}}
    <style>
        #proctoring-widget {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 9999;
            width: 320px;
            height: 240px;
        }

        #webcam {
            display: block;
            width: 320px;
            height: 240px;
            border: 3px solid #10b981;
            border-radius: 4px;
            object-fit: cover;
        }

        #detection-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 320px;
            height: 240px;
            pointer-events: none;
            /* biar klik tembus */
            border: 3px solid #10b981;
            /* samakan border biar align */
            border-radius: 4px;
            box-sizing: border-box;
        }

        #proctoring-widget {
            border: 3px solid #10b981;
            border-radius: 4px;
            overflow: hidden;
        }

        #webcam,
        #detection-canvas {
            width: 320px;
            height: 240px;
            display: block;
        }

        #detection-canvas {
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none;
        }
    </style>
    @stack('styles')
</head>

<body>
    <main class="flex-grow-1 p-4 mx-auto w-100 main-content" id="mainContent" style="max-width:1320px;">
        @yield('content')
    </main>
    <div id="proctoring-widget" style="position:fixed;top:20px;left:20px;z-index:9999;">
        <video id="webcam" autoplay muted playsinline width="320" height="240"></video>
        <canvas id="detection-canvas" style="position:absolute;top:0;left:0;"></canvas>
    </div>
    {{-- ============================================= --}}
    {{-- 1. JAVASCRIPT (Optimized Order) --}}
    {{-- ============================================= --}}

    {{-- jQuery 3.7.1 (stable) --}}
    <script src="{{ asset('vendor/jquery/jquery-3.7.1.min.js') }}"></script>

    {{-- Bootstrap Bundle 5.3.3 --}}
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    {{-- Select2 --}}
    <script src="{{ asset('vendor/select2/select2.min.js') }}"></script>

    {{-- SweetAlert2 --}}
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>

    {{-- Axios --}}
    <script src="{{ asset('vendor/axios/axios.min.js') }}"></script>

    {{-- ============================================= --}}
    {{-- 2. CUSTOM SCRIPTS --}}
    {{-- ============================================= --}}
    <script src="{{ asset('assets/js/modal.js') }}"></script>
    {{-- 1. Core.js (HARUS PERTAMA) --}}
    <script src="{{ asset('assets/js/core.js') }}?v={{ filemtime(public_path('assets/js/core.js')) }}"></script>
    <script>
        document.addEventListener('ajax-modal:loaded', function() {
            initSelect2InModal();
        });
    </script>

    {{-- 3. Button.js (KEDUA) --}}
    <script src="{{ asset('assets/js/button.js') }}?v={{ filemtime(public_path('assets/js/button.js')) }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script>
        window.CBT_CONFIG = {
            sessionId: {{ $current_session?->id ?? 'null' }},
            csrfToken: '{{ csrf_token() }}',
            detectionInterval: 5000,
            debug: false,
        };
    </script>
    <script src="{{ asset('assets/js/proctoring.js') }}?v={{ filemtime(public_path('assets/js/proctoring.js')) }}">
    </script>

    @stack('scripts')

</body>

</html>
