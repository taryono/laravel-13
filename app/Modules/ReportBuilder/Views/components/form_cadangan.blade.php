<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Komponen Baru - Report Builder</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

<body class="min-h-screen">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Navigasi atas --}}
        <a href="{{ route('admin.report-builder.components.index') }}"
            class="inline-flex items-center gap-1.5 text-sm font-medium transition hover:underline"
            style="color: var(--ink-soft)">
            <span aria-hidden="true">←</span> Kembali ke daftar komponen
        </a>

        {{-- Judul --}}
        <header class="mt-5 mb-7">
            <h1 class="text-3xl font-bold" style="letter-spacing:-0.02em">Tambah komponen palette</h1>
            <p class="mt-1.5 text-sm max-w-xl" style="color: var(--ink-soft)">
                Atur komponen beserta isi bawaannya. Komponen ini akan muncul di panel kiri Report Builder.
            </p>
        </header>

        <form action="{{ route('admin.report-builder.components.store') }}" method="POST">
            @csrf
            @php
                $dataSourcesList =
                    isset($dataSources) && $dataSources->isNotEmpty()
                        ? $dataSources
                        : \Models\ReportDataSource::active()->orderBy('order')->orderBy('name')->get();
            @endphp
            <div x-data="componentForm({{ isset($component) ? json_encode($component) : 'null' }}, {{ json_encode($dataSourcesList) }})" class="space-y-6">

                @if (isset($errors) && $errors->any())
                    <div class="rounded-xl p-4" role="alert"
                        style="background: var(--danger-tint); border: 1px solid #efc3be;">
                        <div class="font-semibold text-sm mb-1.5" style="color: var(--danger)">Komponen belum bisa
                            disimpan. Periksa isian berikut:</div>
                        <ul class="list-disc list-inside text-[13px] space-y-0.5" style="color: #7d2822">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <input type="hidden" name="default_config" :value="JSON.stringify(config)">
                <input type="hidden" name="type" :value="type">

                {{-- ===================== INFORMASI DASAR ===================== --}}
                <section class="card">
                    <div class="card-head">
                        <h2 class="section-title">Informasi dasar</h2>
                        <p class="section-sub">Nama, tipe, dan tempat komponen ditampilkan.</p>
                    </div>

                    <div class="card-body space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="label" for="f-name">Nama komponen <span
                                        style="color: var(--danger)">*</span></label>
                                <input id="f-name" type="text" name="name" x-model="name" required
                                    placeholder="Contoh: Tabel Tahfidz Juz 30" class="field">
                                <p class="hint">Tampil pada tombol di panel kiri builder.</p>
                            </div>
                            <div>
                                <label class="label" for="f-cat">Kategori <span
                                        style="color: var(--danger)">*</span></label>
                                <select id="f-cat" x-model="category" name="category" class="field">
                                    <option value="blocks">Blocks (blok konten / tabel)</option>
                                    <option value="text">Text elements (elemen teks)</option>
                                    <option value="custom">Custom (komponen kustom)</option>
                                    @foreach ($categories as $cat)
                                        @if (!in_array($cat, ['blocks', 'text', 'custom']))
                                            <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <p class="hint">Kelompok komponen di panel builder.</p>
                            </div>
                        </div>

                        {{-- Tipe section sebagai kartu pilihan --}}
                        <div>
                            <div class="label">Tipe section <span style="color: var(--danger)">*</span></div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2.5">
                                <template x-for="t in types" :key="t.value">
                                    <button type="button" class="type-card" :aria-pressed="type === t.value"
                                        @click="type = t.value; onTypeChange()">
                                        <div class="t-name" x-text="t.value"></div>
                                        <div class="t-desc" x-text="t.desc"></div>
                                    </button>
                                </template>
                            </div>
                            <p class="hint">Menentukan cara kanvas menampilkan komponen ini.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="md:col-span-2">
                                <label class="label" for="f-icon">Ikon</label>
                                <div class="flex items-start gap-3">
                                    <input id="f-icon" type="text" name="icon" x-model="icon" maxlength="10"
                                        placeholder="📄" class="field text-center text-xl"
                                        style="width:64px; padding:6px">
                                    <div class="flex flex-wrap gap-0.5 flex-1">
                                        <template
                                            x-for="em in ['🔝','🆔','📊','📝','✍️','🔻','📄','➖','⏭️','🕌','📖','🏆','🎖️','⭐','📅','📌']"
                                            :key="em">
                                            <button type="button" @click="icon = em" class="emoji-btn" x-text="em"
                                                :aria-label="'Pilih ikon ' + em"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="label" for="f-order">Urutan tampil</label>
                                <input id="f-order" type="number" name="order" x-model.number="order"
                                    min="0" required class="field">
                                <p class="hint">Urutan di dalam kategori (1, 2, 3, …).</p>
                            </div>
                        </div>

                        <div>
                            <label class="label" for="f-desc">Deskripsi singkat</label>
                            <input id="f-desc" type="text" name="description" x-model="description"
                                placeholder="Muncul sebagai tooltip saat kursor diarahkan ke komponen" class="field">
                        </div>

                        <div class="panel">
                            <div class="text-sm font-semibold mb-3">Ruang lingkup</div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="label" for="f-cur">Kurikulum</label>
                                    <select id="f-cur" name="curriculum_id" x-model="curriculum_id"
                                        class="field">
                                        <option value="">Semua kurikulum (universal)</option>
                                        @foreach ($curriculums ?? [] as $c)
                                            <option value="{{ $c->id }}"
                                                {{ isset($component) && $component->curriculum_id == $c->id ? 'selected' : '' }}>
                                                {{ $c->name }} ({{ $c->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="hint">Jika dipilih, komponen hanya muncul saat mengedit template
                                        kurikulum ini.</p>
                                </div>
                                <div>
                                    <label class="label" for="f-ay">Tahun ajaran</label>
                                    <select id="f-ay" name="academic_year_id" x-model="academic_year_id"
                                        class="field">
                                        <option value="">Semua tahun ajaran (berlaku selamanya)</option>
                                        @foreach ($academicYears ?? [] as $ay)
                                            <option value="{{ $ay->id }}"
                                                {{ isset($component) && $component->academic_year_id == $ay->id ? 'selected' : '' }}>
                                                {{ $ay->formatted_name ?? $ay->start_year . '/' . $ay->end_year }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="hint">Opsional. Batasi komponen untuk tahun ajaran tertentu.</p>
                                </div>
                            </div>
                        </div>

                        <label class="flex items-center justify-between gap-4 cursor-pointer panel"
                            style="padding:14px 16px">
                            <span>
                                <span class="block text-sm font-semibold">Aktifkan komponen</span>
                                <span class="block text-[13px]" style="color: var(--ink-soft)">Komponen aktif muncul
                                    di panel kiri builder.</span>
                            </span>
                            <input type="checkbox" name="is_active" value="1" x-model="is_active"
                                class="toggle">
                        </label>
                    </div>
                </section>

                {{-- ===================== KONTEN & KONFIGURASI ===================== --}}
                <section class="card">
                    <div class="card-head flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="section-title">Isi bawaan komponen</h2>
                            <p class="section-sub">Terisi otomatis saat komponen ditarik atau diklik ke kanvas.</p>
                        </div>
                        <div class="seg" role="group" aria-label="Mode editor">
                            <button type="button" @click="editorMode = 'visual'"
                                :aria-pressed="editorMode === 'visual'">Form visual</button>
                            <button type="button" @click="editorMode = 'json'; syncToJson()"
                                :aria-pressed="editorMode === 'json'" class="mono">JSON</button>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- ============ VISUAL ============ --}}
                        <div x-show="editorMode === 'visual'" class="space-y-4">

                            {{-- TEXT --}}
                            <template x-if="type === 'text'">
                                <div class="space-y-5">
                                    <div>
                                        <label class="label">Teks bawaan</label>
                                        <textarea x-model="config.content" rows="4" placeholder="Ketik teks bawaan atau gunakan [[ binding ]]"
                                            class="field code"></textarea>
                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            <span class="text-[13px]" style="color: var(--ink-soft)">Sisipkan
                                                variabel:</span>
                                            <select
                                                @change="if ($event.target.value) { config.content = (config.content || '') + ' [[ ' + $event.target.value + ' ]]'; $event.target.value = ''; }"
                                                class="field sm" style="width:auto; min-width:220px">
                                                <option value="">Pilih variabel santri / rapor</option>
                                                <template x-for="(fields, model) in bindings" :key="model">
                                                    <optgroup :label="model">
                                                        <template x-for="f in fields" :key="f">
                                                            <option :value="model + '.' + f" x-text="model + '.' + f">
                                                            </option>
                                                        </template>
                                                    </optgroup>
                                                </template>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="panel">
                                        <div class="text-sm font-semibold mb-3">Tampilan teks</div>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            <div>
                                                <label class="label">Ukuran font (pt)</label>
                                                <input type="number" x-model.number="config.style.fontSize"
                                                    class="field sm">
                                            </div>
                                            <div>
                                                <label class="label">Jenis font</label>
                                                <select x-model="config.style.fontFamily" class="field sm">
                                                    <option>Times New Roman</option>
                                                    <option>Arial</option>
                                                    <option>Calibri</option>
                                                    <option>Cambria</option>
                                                    <option>Courier New</option>
                                                    <option>Georgia</option>
                                                    <option>Verdana</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="label">Perataan</label>
                                                <select x-model="config.style.textAlign" class="field sm">
                                                    <option value="left">Kiri</option>
                                                    <option value="center">Tengah</option>
                                                    <option value="right">Kanan</option>
                                                    <option value="justify">Rata kanan-kiri</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="label">Warna teks</label>
                                                <input type="color" x-model="config.style.color" class="field"
                                                    style="height:34px; padding:3px; cursor:pointer">
                                            </div>
                                        </div>
                                        <div class="flex gap-6 mt-4">
                                            <label class="inline-flex items-center gap-2 text-[13px] cursor-pointer">
                                                <input type="checkbox" class="check"
                                                    :checked="config.style.fontWeight === 'bold'"
                                                    @change="config.style.fontWeight = $event.target.checked ? 'bold' : 'normal'">
                                                <span class="font-bold">Tebal</span>
                                            </label>
                                            <label class="inline-flex items-center gap-2 text-[13px] cursor-pointer">
                                                <input type="checkbox" class="check"
                                                    :checked="config.style.fontStyle === 'italic'"
                                                    @change="config.style.fontStyle = $event.target.checked ? 'italic' : 'normal'">
                                                <span class="italic">Miring</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            {{-- TABLE --}}
                            <template x-if="type === 'table'">
                                <div class="space-y-5">
                                    {{-- Preset --}}
                                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl p-3.5"
                                        style="background: var(--brand-tint)">
                                        <div class="text-[13px] font-semibold" style="color: var(--brand-dark)">
                                            Mulai dari template kurikulum
                                        </div>
                                        <div class="flex flex-wrap gap-2">
                                            <button type="button" @click="loadPreset('merdeka')" class="btn"
                                                style="padding:5px 12px">Kurikulum Merdeka</button>
                                            <button type="button" @click="loadPreset('k13')" class="btn"
                                                style="padding:5px 12px">K-13</button>
                                            <button type="button" @click="loadPreset('pesantren')" class="btn"
                                                style="padding:5px 12px">Pesantren / Kitab</button>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start">
                                        <div>
                                            <label class="label">Judul tabel</label>
                                            <input type="text" x-model="config.title" @input="syncToJson()"
                                                placeholder="Contoh: A. NILAI AKADEMIK" class="field">
                                            <p class="hint">Muncul di atas tabel pada rapor santri.</p>
                                        </div>
                                        <div>
                                            <div class="flex items-center justify-between mb-1.5">
                                                <label class="label" style="margin:0">Sumber data</label>
                                                <a href="{{ route('admin.report-builder.data-sources.index') }}"
                                                    target="_blank" class="text-xs font-semibold hover:underline"
                                                    style="color: var(--brand)"
                                                    title="Buka kelola sumber data di tab baru">Kelola sumber data
                                                    ↗</a>
                                            </div>
                                            <select x-model="config.dataSource" @change="onDataSourceSelectChange()"
                                                class="field code">
                                                <template x-for="source in dataSources" :key="source.key">
                                                    <option :value="source.key"
                                                        x-text="source.name + ' (' + source.key + ')'"></option>
                                                </template>
                                            </select>
                                            <p class="hint">Data yang di-looping menjadi baris tabel.</p>
                                        </div>
                                    </div>

                                    {{-- Detail sumber data --}}
                                    <div x-show="activeDataSource" x-transition class="panel space-y-3">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="text-sm font-bold"
                                                    x-text="activeDataSource?.name"></span>
                                                <span class="text-[11px] px-2 py-0.5 rounded-full font-semibold"
                                                    :style="activeDataSource?.category === 'academic' ?
                                                        'background:#dbeafe;color:#1e4b8f' :
                                                        (activeDataSource?.category === 'pesantren' ?
                                                            'background:#d5f0e3;color:#0a5242' :
                                                            (activeDataSource?.category === 'tahfidz' ?
                                                                'background:#fdeccb;color:#8a5a06' :
                                                                'background:#e5e9e7;color:#3c4a43'))"
                                                    x-text="activeDataSource?.category"></span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button type="button" @click="applyDefaultColumnsFromDataSource()"
                                                    class="btn btn-primary" style="padding:6px 12px">
                                                    Terapkan kolom bawaan
                                                </button>
                                                <button type="button" @click="showSampleModal = !showSampleModal"
                                                    class="btn" style="padding:6px 12px"
                                                    x-text="showSampleModal ? 'Sembunyikan contoh' : 'Lihat contoh data'"></button>
                                            </div>
                                        </div>

                                        <p class="text-[13px] leading-relaxed" style="color: var(--ink-soft)"
                                            x-text="activeDataSource?.description"
                                            x-show="activeDataSource?.description"></p>

                                        <div class="pt-3" style="border-top: 1px solid var(--line)">
                                            <div class="text-[13px] font-semibold mb-2">
                                                Field tersedia
                                                <span class="font-normal" style="color: var(--ink-faint)">— klik untuk
                                                    menambah sebagai kolom</span>
                                            </div>
                                            <div class="flex flex-wrap gap-1.5 max-h-32 overflow-y-auto pr-1">
                                                <template x-for="f in (activeDataSource?.fields || [])"
                                                    :key="f.field">
                                                    <button type="button" @click="addSpecificColumn(f)"
                                                        class="chip">
                                                        <span style="color: var(--brand); font-weight:700">+</span>
                                                        <span class="font-medium" x-text="f.label || f.field"></span>
                                                        <code x-text="f.field"></code>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>

                                        <div x-show="showSampleModal" x-transition class="pt-3"
                                            style="border-top: 1px solid var(--line)">
                                            <div class="text-[13px] font-semibold mb-1.5">Contoh data (JSON)</div>
                                            <pre class="p-3 rounded-lg text-[11px] max-h-40 overflow-auto"
                                                style="background: var(--code-bg); color: var(--code-ink)"
                                                x-text="JSON.stringify(activeDataSource?.sample_data, null, 2)"></pre>
                                        </div>
                                    </div>

                                    <datalist id="datasource-fields-options">
                                        <template x-for="f in (activeDataSource?.fields || [])"
                                            :key="f.field">
                                            <option :value="f.field"
                                                :label="f.label ? (f.label + ' (' + (f.type || 'string') + ')') : f.field">
                                            </option>
                                        </template>
                                    </datalist>

                                    {{-- Kolom --}}
                                    <div class="pt-5" style="border-top: 1px solid var(--line)">
                                        <div class="flex flex-wrap justify-between items-end gap-3 mb-3">
                                            <div>
                                                <div class="text-sm font-bold">Kolom tabel</div>
                                                <p class="text-[13px]" style="color: var(--ink-soft)">Atur header,
                                                    field data, lebar, dan perataan.</p>
                                            </div>
                                            <button type="button" @click="addTableColumn()" class="btn btn-tint">+
                                                Tambah kolom</button>
                                        </div>

                                        <div class="hidden md:grid gap-2 px-3 pb-1.5 text-xs font-semibold"
                                            style="grid-template-columns: 28px 1fr 1fr 90px 90px 30px; color: var(--ink-faint)">
                                            <span>#</span><span>Header</span><span>Field</span><span>Lebar</span><span>Rata</span><span></span>
                                        </div>

                                        <div class="space-y-2">
                                            <template x-for="(col, idx) in (config.columns || [])"
                                                :key="idx">
                                                <div
                                                    class="row p-2.5 grid gap-2 items-center grid-cols-2 md:[grid-template-columns:28px_1fr_1fr_90px_90px_30px]">
                                                    <div class="hidden md:block text-center text-xs font-semibold mono"
                                                        style="color: var(--ink-faint)" x-text="idx + 1"></div>
                                                    <input type="text" x-model="col.header" @input="syncToJson()"
                                                        placeholder="Mata Pelajaran" aria-label="Header kolom"
                                                        class="field sm">
                                                    <input type="text" x-model="col.field" @input="syncToJson()"
                                                        list="datasource-fields-options" placeholder="subject.name"
                                                        aria-label="Field data" class="field sm code">
                                                    <input type="text" x-model="col.width" @input="syncToJson()"
                                                        placeholder="20%" aria-label="Lebar kolom"
                                                        class="field sm code text-center">
                                                    <select x-model="col.align" @change="syncToJson()"
                                                        aria-label="Perataan" class="field sm">
                                                        <option value="left">Kiri</option>
                                                        <option value="center">Tengah</option>
                                                        <option value="right">Kanan</option>
                                                    </select>
                                                    <button type="button" @click="removeTableColumn(idx)"
                                                        class="btn btn-icon" title="Hapus kolom"
                                                        aria-label="Hapus kolom">✕</button>
                                                </div>
                                            </template>
                                            <div x-show="!(config.columns && config.columns.length)"
                                                class="text-center text-[13px] py-8 rounded-xl"
                                                style="border: 1px dashed var(--line-strong); color: var(--ink-soft)">
                                                Belum ada kolom. Pilih template, klik field di atas, atau tambah kolom
                                                kosong.
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Footer --}}
                                    <div class="pt-5 space-y-3" style="border-top: 1px solid var(--line)">
                                        <label class="flex items-center gap-3 cursor-pointer">
                                            <input type="checkbox" x-model="config.showFooter" @change="syncToJson()"
                                                class="toggle">
                                            <span class="text-sm font-semibold">Tampilkan baris footer (mis.
                                                rata-rata)</span>
                                        </label>
                                        <div x-show="config.showFooter" x-transition
                                            class="panel grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="label">Label footer</label>
                                                <input type="text" x-model="config.footerLabel"
                                                    @input="syncToJson()" placeholder="Rata-rata" class="field sm">
                                            </div>
                                            <div>
                                                <label class="label">Field nilai footer</label>
                                                <input type="text" x-model="config.footerField"
                                                    @input="syncToJson()" placeholder="average_score"
                                                    class="field sm code">
                                            </div>
                                            <div>
                                                <label class="label">Tampilkan nilai di kolom</label>
                                                <select x-model.number="config.footerTargetIndex"
                                                    @change="syncToJson()" class="field sm">
                                                    <template x-for="(col, idx) in (config.columns || [])"
                                                        :key="idx">
                                                        <option :value="idx"
                                                            x-text="'Kolom ' + (idx + 1) + ': ' + (col.header || col.field || ('Kolom ' + (idx + 1)))">
                                                        </option>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            {{-- KEYVALUE --}}
                            <template x-if="type === 'keyvalue' || type === 'identity'">
                                <div class="space-y-3">
                                    <div class="flex flex-wrap justify-between items-end gap-3">
                                        <div>
                                            <div class="text-sm font-bold">Baris identitas</div>
                                            <p class="text-[13px]" style="color: var(--ink-soft)">Setiap baris berisi
                                                label dan data yang ditampilkan.</p>
                                        </div>
                                        <button type="button" @click="addKeyValueField()" class="btn btn-tint">+
                                            Tambah baris</button>
                                    </div>
                                    <div class="space-y-2">
                                        <template x-for="(f, idx) in (config.fields || [])" :key="idx">
                                            <div class="row p-2.5 flex flex-wrap md:flex-nowrap items-center gap-2">
                                                <input type="text" x-model="f.label" placeholder="Nama Santri"
                                                    aria-label="Label" class="field sm md:w-1/3">
                                                <input type="text" x-model="f.binding"
                                                    placeholder="student.full_name" aria-label="Binding"
                                                    class="field sm code flex-1">
                                                <button type="button" @click="removeKeyValueField(idx)"
                                                    class="btn btn-icon" title="Hapus baris"
                                                    aria-label="Hapus baris">✕</button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            {{-- TEXT-BLOCK --}}
                            <template x-if="type === 'text-block'">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="label">Judul catatan</label>
                                        <input type="text" x-model="config.title"
                                            placeholder="Contoh: Catatan Wali Kelas" class="field">
                                    </div>
                                    <div>
                                        <label class="label">Sumber isi catatan</label>
                                        <select x-model="config.binding" class="field code">
                                            <option value="report_card.homeroom_notes">report_card.homeroom_notes
                                                (catatan wali kelas)</option>
                                            <option value="report_card.headmaster_notes">report_card.headmaster_notes
                                                (catatan pimpinan)</option>
                                            <option value="content">content (teks bebas)</option>
                                        </select>
                                    </div>
                                </div>
                            </template>

                            {{-- SIGNATURE --}}
                            <template x-if="type === 'signature'">
                                <div class="space-y-3">
                                    <div class="flex flex-wrap justify-between items-end gap-3">
                                        <div>
                                            <div class="text-sm font-bold">Kolom tanda tangan</div>
                                            <p class="text-[13px]" style="color: var(--ink-soft)">Tiap kolom mewakili
                                                satu pejabat.</p>
                                        </div>
                                        <button type="button" @click="addSignatureCol()" class="btn btn-tint">+
                                            Tambah tanda tangan</button>
                                    </div>
                                    <div class="space-y-2">
                                        <template x-for="(col, idx) in (config.columns || [])" :key="idx">
                                            <div class="row p-3 space-y-3">
                                                <div class="flex flex-wrap md:flex-nowrap items-center gap-2">
                                                    <input type="text" x-model="col.label"
                                                        placeholder="Wali Kelas" aria-label="Label"
                                                        class="field sm flex-1">
                                                    <select x-model="col.role" aria-label="Peran"
                                                        class="field sm code md:w-52">
                                                        <option value="homeroom_teacher">homeroom_teacher</option>
                                                        <option value="headmaster">headmaster</option>
                                                        <option value="official">official</option>
                                                        <option value="parent">parent</option>
                                                    </select>
                                                    <button type="button" @click="removeSignatureCol(idx)"
                                                        class="btn btn-icon" title="Hapus"
                                                        aria-label="Hapus tanda tangan">✕</button>
                                                </div>
                                                <div class="flex items-center gap-6 text-[13px]">
                                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                                        <input type="checkbox" x-model="col.showImage"
                                                            class="check"> Gambar tanda tangan digital
                                                    </label>
                                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                                        <input type="checkbox" x-model="col.showEmployeeNumber"
                                                            class="check"> Tampilkan NIP
                                                    </label>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            {{-- DIVIDER / PAGEBREAK --}}
                            <template x-if="type === 'divider' || type === 'pagebreak'">
                                <div class="text-center text-[13px] py-10 rounded-xl"
                                    style="border: 1px dashed var(--line-strong); color: var(--ink-soft)">
                                    Tipe ini tidak punya isi yang perlu diatur.
                                </div>
                            </template>
                        </div>

                        {{-- ============ JSON ============ --}}
                        <div x-show="editorMode === 'json'" class="space-y-3">
                            <div class="flex justify-between items-center gap-3">
                                <span class="text-[13px]" style="color: var(--ink-soft)">Konfigurasi dalam format
                                    JSON</span>
                                <button type="button" @click="formatJson()" class="btn"
                                    style="padding:5px 12px">Rapikan JSON</button>
                            </div>
                            <textarea x-model="jsonRaw" @input="syncFromRawJson()" rows="14" spellcheck="false" class="field mono"
                                style="background: var(--code-bg); color: var(--code-ink); border-color: var(--code-bg); font-size:12.5px; line-height:1.6"></textarea>
                            <p class="hint">JSON ini dipakai langsung oleh kanvas saat komponen ditambahkan.</p>
                        </div>
                    </div>
                </section>

                {{-- ===================== AKSI ===================== --}}
                <div class="action-bar flex items-center justify-between gap-3">
                    <span class="hidden sm:block text-[13px]" style="color: var(--ink-soft)">
                        Komponen baru akan langsung tersedia di Report Builder bila status aktif.
                    </span>
                    <div class="flex items-center gap-2 ml-auto">
                        <a href="{{ route('admin.report-builder.components.index') }}" class="btn">Batal</a>
                        <button type="submit" class="btn btn-primary" style="padding:9px 20px">Simpan
                            komponen</button>
                    </div>
                </div>

            </div>

            <script>
                function componentForm(initialData, initialDataSources) {
                    const defaultTemplates = {
                        'text': {
                            type: 'text',
                            content: 'Custom text content here...',
                            style: {
                                fontSize: 12,
                                fontWeight: 'normal',
                                fontStyle: 'normal',
                                textAlign: 'left',
                                color: '#000000',
                                fontFamily: 'Times New Roman'
                            }
                        },
                        'table': {
                            type: 'table',
                            title: 'A. NILAI SANTRI',
                            dataSource: 'academic_scores',
                            columns: [{
                                    header: 'No',
                                    field: 'index',
                                    width: '8%',
                                    align: 'center'
                                },
                                {
                                    header: 'Mata Pelajaran',
                                    field: 'subject.name',
                                    width: '45%',
                                    align: 'left'
                                },
                                {
                                    header: 'Nilai',
                                    field: 'numeric_score',
                                    width: '20%',
                                    align: 'center'
                                },
                                {
                                    header: 'Predikat',
                                    field: 'predicate',
                                    width: '27%',
                                    align: 'center'
                                }
                            ],
                            style: {
                                fontSize: 10,
                                textAlign: 'left',
                                color: '#000000',
                                fontFamily: 'Times New Roman'
                            },
                            showFooter: false,
                            footerLabel: 'Rata-rata',
                            footerField: 'average_score',
                            footerTargetIndex: 2
                        },
                        'keyvalue': {
                            type: 'keyvalue',
                            fields: [{
                                    label: 'Nama Santri',
                                    binding: 'student.full_name'
                                },
                                {
                                    label: 'Nomor Induk',
                                    binding: 'student.student_number'
                                },
                                {
                                    label: 'Kelas',
                                    binding: 'classroom.name'
                                },
                                {
                                    label: 'Asrama',
                                    binding: 'dormitory.name'
                                }
                            ],
                            style: {
                                fontSize: 12,
                                textAlign: 'left',
                                color: '#000000',
                                fontFamily: 'Times New Roman'
                            }
                        },
                        'text-block': {
                            type: 'text-block',
                            title: 'Catatan Wali Kelas',
                            binding: 'report_card.homeroom_notes',
                            style: {
                                fontSize: 12,
                                fontStyle: 'italic',
                                textAlign: 'left',
                                color: '#000000',
                                fontFamily: 'Times New Roman'
                            }
                        },
                        'signature': {
                            type: 'signature',
                            columns: [{
                                    role: 'homeroom_teacher',
                                    label: 'Wali Kelas',
                                    source: 'automatic',
                                    showImage: true,
                                    showEmployeeNumber: true
                                },
                                {
                                    role: 'headmaster',
                                    label: 'Kepala Pesantren',
                                    source: 'automatic',
                                    showImage: true,
                                    showEmployeeNumber: false
                                }
                            ],
                            style: {
                                fontSize: 11,
                                textAlign: 'center',
                                color: '#000000',
                                fontFamily: 'Times New Roman'
                            }
                        },
                        'divider': {
                            type: 'divider',
                            style: {}
                        },
                        'pagebreak': {
                            type: 'pagebreak',
                            style: {}
                        }
                    };

                    let initialConfig = initialData?.default_config || defaultTemplates[initialData?.type || 'text'] ||
                        defaultTemplates['text'];
                    if (typeof initialConfig === 'string') {
                        try {
                            initialConfig = JSON.parse(initialConfig);
                        } catch (e) {
                            initialConfig = defaultTemplates['text'];
                        }
                    }

                    return {
                        name: initialData?.name || '',
                        category: initialData?.category || 'blocks',
                        type: initialData?.type || 'text',
                        icon: initialData?.icon || '📄',
                        description: initialData?.description || '',
                        order: initialData?.order ?? 1,
                        is_active: initialData ? Boolean(initialData.is_active) : true,
                        curriculum_id: initialData?.curriculum_id ?? '',
                        academic_year_id: initialData?.academic_year_id ?? '',
                        editorMode: 'visual',
                        config: initialConfig,
                        jsonRaw: JSON.stringify(initialConfig, null, 2),
                        bindings: @json($availableBindings ?? []),
                        dataSources: Array.isArray(initialDataSources) ? initialDataSources : [],
                        showSampleModal: false,
                        types: [{
                                value: 'text',
                                desc: 'Teks, kop, atau banner'
                            },
                            {
                                value: 'table',
                                desc: 'Tabel nilai atau daftar data'
                            },
                            {
                                value: 'keyvalue',
                                desc: 'Identitas santri, baris label-nilai'
                            },
                            {
                                value: 'text-block',
                                desc: 'Kotak catatan'
                            },
                            {
                                value: 'signature',
                                desc: 'Tanda tangan pejabat'
                            },
                            {
                                value: 'divider',
                                desc: 'Garis pemisah'
                            },
                            {
                                value: 'pagebreak',
                                desc: 'Pindah halaman'
                            }
                        ],

                        get activeDataSource() {
                            if (!this.config?.dataSource) return null;
                            return this.dataSources.find(ds => ds.key === this.config.dataSource) || null;
                        },

                        onTypeChange() {
                            if (defaultTemplates[this.type]) {
                                this.config = JSON.parse(JSON.stringify(defaultTemplates[this.type]));
                                this.syncToJson();
                            }
                        },

                        onDataSourceSelectChange() {
                            this.syncToJson();
                        },

                        applyDefaultColumnsFromDataSource() {
                            const src = this.activeDataSource;
                            if (!src || !Array.isArray(src.fields) || src.fields.length === 0) {
                                alert('Sumber data ini belum memiliki definisi field kolom bawaan.');
                                return;
                            }

                            if (this.config.columns && this.config.columns.length > 0) {
                                if (!confirm('Terapkan kolom bawaan dari sumber data ini? Kolom saat ini akan digantikan.')) {
                                    return;
                                }
                            }

                            this.config.columns = src.fields.map(f => ({
                                header: f.label || f.field,
                                field: f.field,
                                width: f.default_width || 'auto',
                                align: f.default_align || (f.type === 'number' ? 'center' : 'left')
                            }));

                            if (!this.config.title || this.config.title.startsWith('A. ') || this.config.title ===
                                'A. NILAI SANTRI') {
                                this.config.title = src.name.toUpperCase();
                            }

                            this.syncToJson();
                        },

                        addSpecificColumn(fieldObj) {
                            if (!this.config.columns) this.config.columns = [];
                            this.config.columns.push({
                                header: fieldObj.label || fieldObj.field,
                                field: fieldObj.field,
                                width: fieldObj.default_width || '20%',
                                align: fieldObj.default_align || (fieldObj.type === 'number' ? 'center' : 'left')
                            });
                            this.syncToJson();
                        },

                        syncToJson() {
                            this.jsonRaw = JSON.stringify(this.config, null, 2);
                        },

                        syncFromRawJson() {
                            try {
                                this.config = JSON.parse(this.jsonRaw);
                            } catch (e) {
                                // JSON belum valid saat sedang diketik, abaikan
                            }
                        },

                        formatJson() {
                            try {
                                this.config = JSON.parse(this.jsonRaw);
                                this.jsonRaw = JSON.stringify(this.config, null, 2);
                            } catch (e) {
                                alert('Format JSON tidak valid: ' + e.message);
                            }
                        },

                        // Table helpers
                        addTableColumn() {
                            if (!this.config.columns) this.config.columns = [];
                            this.config.columns.push({
                                header: 'Kolom Baru',
                                field: 'name',
                                width: '20%',
                                align: 'center'
                            });
                            this.syncToJson();
                        },
                        removeTableColumn(idx) {
                            if (this.config.columns) this.config.columns.splice(idx, 1);
                            this.syncToJson();
                        },

                        // Keyvalue helpers
                        addKeyValueField() {
                            if (!this.config.fields) this.config.fields = [];
                            this.config.fields.push({
                                label: 'Label',
                                binding: 'student.full_name'
                            });
                            this.syncToJson();
                        },
                        removeKeyValueField(idx) {
                            if (this.config.fields) this.config.fields.splice(idx, 1);
                            this.syncToJson();
                        },

                        // Signature helpers
                        addSignatureCol() {
                            if (!this.config.columns) this.config.columns = [];
                            this.config.columns.push({
                                role: 'official',
                                label: 'Pejabat',
                                source: 'automatic',
                                showImage: false,
                                showEmployeeNumber: false
                            });
                            this.syncToJson();
                        },
                        removeSignatureCol(idx) {
                            if (this.config.columns) this.config.columns.splice(idx, 1);
                            this.syncToJson();
                        },

                        loadPreset(presetType) {
                            if (presetType === 'merdeka') {
                                this.config.title = 'A. NILAI AKADEMIK & CAPAIAN KOMPETENSI';
                                this.config.dataSource = 'academic_scores';
                                this.config.columns = [{
                                        header: 'No',
                                        field: 'index',
                                        width: '6%',
                                        align: 'center'
                                    },
                                    {
                                        header: 'Mata Pelajaran',
                                        field: 'subject.name',
                                        width: '34%',
                                        align: 'left'
                                    },
                                    {
                                        header: 'Nilai Akhir',
                                        field: 'numeric_score',
                                        width: '15%',
                                        align: 'center'
                                    },
                                    {
                                        header: 'Capaian Kompetensi',
                                        field: 'description',
                                        width: '45%',
                                        align: 'left'
                                    },
                                ];
                                this.config.footerTargetIndex = 2;
                            } else if (presetType === 'k13') {
                                this.config.title = 'A. NILAI PENGETAHUAN & KETERAMPILAN';
                                this.config.dataSource = 'academic_scores';
                                this.config.columns = [{
                                        header: 'No',
                                        field: 'index',
                                        width: '6%',
                                        align: 'center'
                                    },
                                    {
                                        header: 'Mata Pelajaran',
                                        field: 'subject.name',
                                        width: '34%',
                                        align: 'left'
                                    },
                                    {
                                        header: 'Pengetahuan',
                                        field: 'midterm_score',
                                        width: '15%',
                                        align: 'center'
                                    },
                                    {
                                        header: 'Keterampilan',
                                        field: 'final_score',
                                        width: '15%',
                                        align: 'center'
                                    },
                                    {
                                        header: 'Predikat',
                                        field: 'predicate',
                                        width: '15%',
                                        align: 'center'
                                    },
                                ];
                                this.config.footerTargetIndex = 3;
                            } else if (presetType === 'pesantren') {
                                this.config.title = 'B. CAPAIAN KAJIAN KITAB (SOROGAN)';
                                this.config.dataSource = 'sorogan';
                                this.config.columns = [{
                                        header: 'No',
                                        field: 'index',
                                        width: '8%',
                                        align: 'center'
                                    },
                                    {
                                        header: 'Nama Kitab',
                                        field: 'book.title',
                                        width: '42%',
                                        align: 'left'
                                    },
                                    {
                                        header: 'Nilai Bacaan',
                                        field: 'reading_score',
                                        width: '25%',
                                        align: 'center'
                                    },
                                    {
                                        header: 'Pemahaman',
                                        field: 'translation_score',
                                        width: '25%',
                                        align: 'center'
                                    },
                                ];
                                this.config.footerTargetIndex = 2;
                            }
                            this.syncToJson();
                        }
                    };
                }
            </script>
        </form>

    </div>

</body>

</html>
