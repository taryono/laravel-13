{{-- resources/views/ppdb/form_registrasi.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pendaftaran PPDB {{ $admission->academic_year->name ?? '2025/2026' }} - Portal Resmi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet">

    @include('ppdb.partials._styles')
</head>

<body>

    @include('ppdb.partials._header', ['admission' => $admission])

    <main class="container-fluid pt-3 px-3 px-md-4" style="max-width: 1280px;">

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Periksa kembali data Anda:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Hero Banner --}}
        @include('ppdb.partials._hero', ['admission' => $admission])

        {{-- FORM --}}
        <form id="ppdb-form"
            action="{{ $applicant ? route('ppdb.register.update', $applicant->registration_number) : route('ppdb.register.store', $admission->code) }}"
            method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @if ($applicant)
                @method('PUT')
            @endif

            <input type="hidden" name="admission_path_id" id="admission_path_id"
                value="{{ old('admission_path_id', $applicant->admission_path_id ?? '') }}">
            <input type="hidden" name="latitude" id="latitude"
                value="{{ old('latitude', $applicant->domicile->latitude ?? '') }}">
            <input type="hidden" name="longitude" id="longitude"
                value="{{ old('longitude', $applicant->domicile->longitude ?? '') }}">
            <input type="hidden" name="distance" id="distance"
                value="{{ old('distance', $applicant->domicile->distance_to_school_m ?? '') }}">

            <div class="row g-4 align-items-start">

                {{-- LEFT: FORM --}}
                <div class="col-12 col-lg-8">
                    @include('ppdb.partials._card_pribadi', ['applicant' => $applicant])
                    @include('ppdb.partials._card_jalur', [
                        'applicant' => $applicant,
                        'admission' => $admission,
                    ])
                    @include('ppdb.partials._card_minat_bakat', ['applicant' => $applicant])
                    @include('ppdb.partials._card_ortu', ['applicant' => $applicant])
                    @include('ppdb.partials._card_dokumen', ['applicant' => $applicant])

                </div>

                {{-- RIGHT: SIDEBAR --}}
                <aside class="col-12 col-lg-4">
                    @include('ppdb.partials._sidebar', ['admission' => $admission])
                </aside>

            </div>
        </form>
    </main>

    @include('ppdb.partials._sticky_bar', ['applicant' => $applicant])
    @include('ppdb.partials._bottom_nav')
    @include('ppdb.partials._modals', ['applicant' => $applicant])

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @include('ppdb.partials._scripts')
</body>

</html>
