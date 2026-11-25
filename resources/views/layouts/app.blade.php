<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ============================================= --}}
    {{-- 1. META TAGS (untuk core.js) --}}
    {{-- ============================================= --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user_id" content="{{ auth()->user()?->id ?? '' }}">
    <meta name="login_as" content="{{ session('user_auth.role_as') ?? '' }}">
    <meta name="app-url" content="{{ config('app.url') }}">
    <meta name="env" content="{{ app()->environment() }}">
    <meta name="color-scheme" content="light dark">

    <title>@yield('title', config('app.name', 'Dashboard'))</title>
    {{-- @vite dinonaktifkan di layout utama karena Bootstrap 5.3, jQuery 3.7.1, dan DataTables 3.0.0 + Buttons 4.0.0 sudah dimuat langsung (mencegah bentrok versi & error Vite manifest) --}}
    {{-- ============================================= --}}
    {{-- 2. STYLESHEETS (Optimized) --}}
    {{-- ============================================= --}}
    {{-- Bootstrap 5.3 hanya 1x --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

    {{-- FontAwesome 6.5.1 (stable version) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- DataTables CSS (hanya yang diperlukan) --}}
    <link
        href="https://cdn.datatables.net/v/bs5/moment-2.29.4/jszip-3.10.1/dt-3.0.0/af-3.0.0/b-4.0.0/cr-3.0.0/cc-2.0.0/date-2.0.0/fc-6.0.0/fh-5.0.0/r-4.0.0/rr-2.0.0/sc-3.0.0/sb-2.0.0/sl-4.0.0/datatables.min.css"
        rel="stylesheet" integrity="sha384-YNHRxm6Vr29HJTQ8v1aLtlWi3mpEkk1B+elXLCf/7tFakByky4LC7vDLiRM6Fh4t"
        crossorigin="anonymous">

    {{-- Custom Styles --}}
    <link rel="stylesheet" href="{{ asset('assets/css/general.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/connector-line-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/modal.css') }}">
    @stack('styles')
</head>

<body>
    {{-- ============================================= --}}
    {{-- 3. SIDEBAR & BACKDROP --}}
    {{-- ============================================= --}}
    <x-sidebar />
    <div class="bnsp-backdrop" id="bnspBackdrop"></div>

    {{-- ============================================= --}}
    {{-- 4. TOP NAV --}}
    {{-- ============================================= --}}
    <nav class="bnsp-topnav" id="bnspTopnav">
        <div class="bnsp-topnav-brand">
            <span class="material-symbols-outlined">workspace_premium</span>
            <span>EduPulse</span>
        </div>

        {{-- Flash Messages Container --}}
        <x-flash-messages />
    </nav>

    {{-- ============================================= --}}
    {{-- 5. MAIN CONTENT --}}
    {{-- ============================================= --}}
    <div class="bnsp-main-wrapper" id="bnspMainWrapper">
        <x-topbar />

        {{-- Main content with ID for dynamic loading --}}
        <main class="flex-grow-1 p-4 mx-auto w-100 main-content" id="mainContent" style="max-width:1320px;">
            @yield('content')
        </main>

        {{-- Bottom Navigation --}}
        @yield('bottom_navigation')

        <x-footer />
    </div>

    {{-- ============================================= --}}
    {{-- 6. JAVASCRIPT (Optimized Order) --}}
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

    {{-- DataTables (minimal) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"
        integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"
        integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous">
    </script>
    <script
        src="https://cdn.datatables.net/v/bs5/moment-2.29.4/jszip-3.10.1/dt-3.0.0/af-3.0.0/b-4.0.0/cr-3.0.0/cc-2.0.0/date-2.0.0/fc-6.0.0/fh-5.0.0/r-4.0.0/rr-2.0.0/sc-3.0.0/sb-2.0.0/sl-4.0.0/datatables.min.js"
        integrity="sha384-OaKEle/U0cMG9jUTQQsGJE3gkQhgmqG3mQf1s6Cz2XbXrm6ntMTDMkOtapkUordX" crossorigin="anonymous">
    </script>

    {{-- ============================================= --}}
    {{-- 7. CUSTOM SCRIPTS --}}
    {{-- ============================================= --}}
    <script src="{{ asset('assets/js/modal.js') }}"></script>
    {{-- 2. Core.js (HARUS PERTAMA) --}}
    <script src="{{ asset('assets/js/core.js') }}?v={{ time() }}"></script>
    <script>
        document.addEventListener('ajax-modal:loaded', function() {
            initSelect2InModal();
        });
    </script>

    {{-- 3. Button.js (KEDUA) --}}
    <script src="{{ asset('assets/js/button.js') }}?v={{ time() }}"></script>

    {{-- 4. Script Lainnya --}}
    <script src="{{ asset('assets/js/sidebar.js') }}"></script>



    {{-- AlpineJS (lebih baik di awal, bukan defer) --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    {{-- ============================================= --}}
    {{-- 8. INLINE SCRIPTS (Initialization) --}}
    {{-- ============================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto dismiss flash messages
            const flashContainer = document.getElementById('flash-messages');
            if (flashContainer) {
                const duration = parseInt(flashContainer.dataset.autoDismiss) || 5000;
                setTimeout(() => {
                    const alerts = flashContainer.querySelectorAll('.alert');
                    alerts.forEach(alert => {
                        const bsAlert = bootstrap.Alert.getInstance(alert);
                        if (bsAlert) {
                            bsAlert.close();
                        } else {
                            alert.style.transition = 'opacity 0.5s';
                            alert.style.opacity = '0';
                            setTimeout(() => alert.remove(), 500);
                        }
                    });
                }, duration);
            }
            // Debug info (only in development)
            @if (app()->environment('local'))
                console.log('✅ App initialized in', '{{ app()->environment() }}', 'mode');
                console.log('📦 Dependencies:', {
                    jQuery: typeof $ !== 'undefined',
                    Bootstrap: typeof bootstrap !== 'undefined',
                    Swal: typeof Swal !== 'undefined',
                    axios: typeof axios !== 'undefined',
                    DataTable: typeof DataTable !== 'undefined'
                });
            @endif
        });
        @if (isset($modelTableId))
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    let url = $(this).attr('href');
                    let token = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: token
                        },
                        success: function(response) {
                            if (response.success) {
                                window.LaravelDataTables["{{ modelTableId }}-table"].ajax.reload();
                            }
                        }
                    });
                }
            });
        @endif
    </script>
    <!-- CSS & JS Summernote BS5 -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.js"></script>

    <script>
        $(function() {
            $('#content').summernote({
                height: 300,
                placeholder: 'Tulis isi artikel di sini...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['codeview']]
                ]
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
