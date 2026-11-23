<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Komponen: {{ $component->name }}_Report Builder</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <style>
        [x-cloak] {
            display: none !important;
        }

        :root {
            --bg: #f2f5f3;
            --surface: #ffffff;
            --surface-soft: #f7f9f8;
            --line: #dfe6e2;
            --line-strong: #c5d0ca;
            --ink: #14231d;
            --ink-soft: #55665e;
            --ink-faint: #8a9a92;
            --brand: #0f6b57;
            --brand-dark: #0a5242;
            --brand-tint: #e3f2ed;
            --danger: #b4372f;
            --danger-tint: #fcecea;
            --code-bg: #10201a;
            --code-ink: #8de3c3;
        }

        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            background: var(--bg);
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
        }

        code,
        pre,
        .mono {
            font-family: 'JetBrains Mono', ui-monospace, monospace;
        }

        /* Surfaces */
        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
        }

        .card-head {
            padding: 20px 24px;
            border-bottom: 1px solid var(--line);
        }

        .card-body {
            padding: 24px;
        }

        .panel {
            background: var(--surface-soft);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 16px;
        }

        .row {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 10px;
        }

        .row:focus-within {
            border-color: var(--brand);
        }

        /* Form controls */
        .label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 6px;
        }

        .hint {
            font-size: 12px;
            color: var(--ink-faint);
            margin-top: 6px;
            line-height: 1.45;
        }

        .field {
            width: 100%;
            background: var(--surface);
            border: 1px solid var(--line-strong);
            border-radius: 10px;
            padding: 9px 12px;
            font-size: 14px;
            color: var(--ink);
            transition: border-color .15s, box-shadow .15s;
        }

        .field.sm {
            padding: 6px 9px;
            font-size: 12.5px;
            border-radius: 8px;
        }

        .field:hover {
            border-color: var(--ink-faint);
        }

        .field:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(15, 107, 87, .18);
        }

        .field::placeholder {
            color: var(--ink-faint);
        }

        .field.code {
            font-family: 'JetBrains Mono', monospace;
            color: var(--brand-dark);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            padding: 8px 14px;
            border: 1px solid var(--line-strong);
            background: var(--surface);
            color: var(--ink);
            transition: background .15s, border-color .15s, color .15s;
            cursor: pointer;
        }

        .btn:hover {
            background: var(--surface-soft);
            border-color: var(--ink-faint);
        }

        .btn:focus-visible,
        .chip:focus-visible,
        .type-card:focus-visible {
            outline: 3px solid rgba(15, 107, 87, .35);
            outline-offset: 2px;
        }

        .btn-primary {
            background: var(--brand);
            border-color: var(--brand);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--brand-dark);
            border-color: var(--brand-dark);
        }

        .btn-tint {
            background: var(--brand-tint);
            border-color: transparent;
            color: var(--brand-dark);
        }

        .btn-tint:hover {
            background: #d3eae1;
            border-color: transparent;
        }

        .btn-icon {
            width: 30px;
            height: 30px;
            padding: 0;
            color: var(--ink-faint);
            border-color: transparent;
            background: transparent;
        }

        .btn-icon:hover {
            color: var(--danger);
            background: var(--danger-tint);
            border-color: transparent;
        }

        /* Type selector cards */
        .type-card {
            text-align: left;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid var(--line-strong);
            background: var(--surface);
            cursor: pointer;
            transition: border-color .15s, background .15s;
        }

        .type-card:hover {
            border-color: var(--brand);
        }

        .type-card[aria-pressed="true"] {
            border-color: var(--brand);
            background: var(--brand-tint);
            box-shadow: inset 0 0 0 1px var(--brand);
        }

        .type-card .t-name {
            font-size: 13px;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
        }

        .type-card .t-desc {
            font-size: 12px;
            color: var(--ink-soft);
            margin-top: 2px;
            line-height: 1.35;
        }

        /* Chips */
        .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            background: var(--surface);
            border: 1px solid var(--line-strong);
            font-size: 12px;
            color: var(--ink);
            cursor: pointer;
            transition: background .15s, border-color .15s;
        }

        .chip:hover {
            background: var(--brand-tint);
            border-color: var(--brand);
        }

        .chip code {
            font-size: 11px;
            color: var(--brand);
        }

        .emoji-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid transparent;
            font-size: 16px;
            cursor: pointer;
            transition: background .15s;
        }

        .emoji-btn:hover {
            background: var(--brand-tint);
        }

        /* Segmented control */
        .seg {
            display: inline-flex;
            background: var(--bg);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 3px;
        }

        .seg button {
            padding: 6px 14px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            color: var(--ink-soft);
            transition: background .15s, color .15s;
        }

        .seg button[aria-pressed="true"] {
            background: var(--surface);
            color: var(--brand-dark);
            box-shadow: 0 1px 2px rgba(20, 35, 29, .12);
        }

        .section-title {
            font-size: 17px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .section-sub {
            font-size: 13px;
            color: var(--ink-soft);
            margin-top: 2px;
        }

        /* Sticky action bar */
        .action-bar {
            position: sticky;
            bottom: 0;
            z-index: 20;
            margin-top: 8px;
            background: rgba(255, 255, 255, .92);
            backdrop-filter: blur(8px);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 12px 16px;
            box-shadow: 0 -6px 24px rgba(20, 35, 29, .07);
        }

        .toggle {
            appearance: none;
            width: 38px;
            height: 22px;
            border-radius: 999px;
            background: var(--line-strong);
            position: relative;
            cursor: pointer;
            transition: background .2s;
            flex-shrink: 0;
        }

        .toggle::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #fff;
            transition: transform .2s;
        }

        .toggle:checked {
            background: var(--brand);
        }

        .toggle:checked::after {
            transform: translateX(16px);
        }

        .toggle:focus-visible {
            outline: 3px solid rgba(15, 107, 87, .35);
            outline-offset: 2px;
        }

        .check {
            width: 16px;
            height: 16px;
            accent-color: var(--brand);
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
            }
        }
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
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <span>✏️</span> Edit Komponen: <span class="text-blue-600">{{ $component->name }}</span>
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    Ubah konfigurasi, ikon, kategori, atau custom content bawaan dari komponen ini.
                </p>
            </div>
            <span class="text-xs font-mono bg-gray-200 text-gray-700 px-2.5 py-1 rounded-md">ID:
                {{ $component->id }}</span>
        </div>

        {{-- Form Container --}}
        <form action="{{ route('admin.report-builder.components.update', $component->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('ReportBuilder::components._form', ['component' => $component])
        </form>

    </div>

</body>

</html>
