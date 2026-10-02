<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Komponen Baru - Report Builder</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Top Navigation --}}
        <div class="mb-6">
            <a href="{{ route('admin.report-builder.components.index') }}"
                class="text-sm font-medium text-gray-500 hover:text-gray-700 flex items-center gap-1 transition">
                ← Kembali ke Daftar Komponen
            </a>
        </div>

        {{-- Title --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <span>➕</span> Tambah Komponen Palette Baru
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                Definisikan komponen dan custom content bawaan yang akan tampil di panel kiri Report Builder.
            </p>
        </div>

        {{-- Form Container --}}
        <form action="{{ route('admin.report-builder.components.store') }}" method="POST">
            @csrf
            @include('admin.report-builder.components._form')
        </form>

    </div>

</body>

</html>
