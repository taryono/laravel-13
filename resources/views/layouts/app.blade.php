<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" />
    <meta name="user_id" content="{{ auth()->user()->id }}" />
    <meta name="login_as" content="{{ session('user_auth.role_as') }}" />
    <title>
        @yield('title', 'Dashboard')

    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.8/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/v/bs5/moment-2.29.4/jszip-3.10.1/dt-3.0.0/af-3.0.0/b-4.0.0/cr-3.0.0/cc-2.0.0/date-2.0.0/fc-6.0.0/fh-5.0.0/r-4.0.0/rr-2.0.0/sc-3.0.0/sb-2.0.0/sl-4.0.0/datatables.min.css" rel="stylesheet" integrity="sha384-YNHRxm6Vr29HJTQ8v1aLtlWi3mpEkk1B+elXLCf/7tFakByky4LC7vDLiRM6Fh4t" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-datatable.css') }}">
    @stack('styles')

</head>

<body>

    <x-sidebar />
    <!-- Backdrop for mobile drawer -->
    <div class="bnsp-backdrop" id="bnspBackdrop"></div>

    <!-- Topnav (mode layout: topbar) — isi menu disalin otomatis dari
         sidebar lewat JS (buildTopNav), jadi menu cukup ditulis SEKALI
         saja di dalam <aside class="bnsp-sidebar">. Jangan tulis ulang
         item menu secara manual di sini. -->
    <nav class="bnsp-topnav" id="bnspTopnav">
        <div class="bnsp-topnav-brand">
            <span class="material-symbols-outlined">workspace_premium</span>
            <span>BNSP Portal</span>
        </div>
    </nav>

    <div class="bnsp-main-wrapper" id="bnspMainWrapper">

        <x-topbar />

        <main class="flex-grow-1 p-4 mx-auto w-100 main-content" style="max-width:1320px;">
            @yield('content')
        </main>

        <x-footer />

    </div>
    <script src="https://code.jquery.com/jquery-4.0.0.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.8/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/v/bs5/moment-2.29.4/jszip-3.10.1/dt-3.0.0/af-3.0.0/b-4.0.0/cr-3.0.0/cc-2.0.0/date-2.0.0/fc-6.0.0/fh-5.0.0/r-4.0.0/rr-2.0.0/sc-3.0.0/sb-2.0.0/sl-4.0.0/datatables.min.js" integrity="sha384-OaKEle/U0cMG9jUTQQsGJE3gkQhgmqG3mQf1s6Cz2XbXrm6ntMTDMkOtapkUordX" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/core.js') }}"></script>
    <script src="{{ asset('assets/js/modal.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')

</body>

</html>
