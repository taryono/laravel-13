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

    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">

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

        <main class="flex-grow-1 p-4 mx-auto w-100" style="max-width:1320px;">
            @yield('content')
        </main>

        <x-footer />

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/core.js') }}"></script>
    <script src="{{ asset('assets/js/modal.js') }}"></script>
    @stack('scripts')

</body>

</html>
