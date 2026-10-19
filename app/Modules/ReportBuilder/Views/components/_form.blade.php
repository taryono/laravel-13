{{-- Shared Form Component for Create & Edit --}}
@php
    $dataSourcesList =
        isset($dataSources) && $dataSources->isNotEmpty()
            ? $dataSources
            : \Models\ReportDataSource::active()->orderBy('order')->orderBy('name')->get();
@endphp
<div x-data="componentForm({{ isset($component) ? json_encode($component) : 'null' }}, {{ json_encode($dataSourcesList) }})" class="space-y-6">

    @if (isset($errors) && $errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
            <div class="font-bold text-red-800 text-sm mb-1">Terjadi kesalahan validasi:</div>
            <ul class="list-disc list-inside text-xs text-red-700 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Hidden input for JSON default_config --}}
    <input type="hidden" name="default_config" :value="JSON.stringify(config)">

    {{-- SECTION 1: Informasi Dasar --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        <h2 class="text-base font-bold text-gray-800 border-b pb-2 flex items-center gap-2">
            <span>📋</span> Informasi Dasar Komponen
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                    Nama Komponen <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" x-model="name" required placeholder="Contoh: Tabel Tahfidz Juz 30"
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                <p class="text-[11px] text-gray-400 mt-1">Nama yang akan tampil pada tombol di panel kiri builder.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-2">
                    <select x-model="category" name="category"
                        class="border rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-blue-400 outline-none flex-1">
                        <option value="blocks">Blocks (Blok Konten / Tabel)</option>
                        <option value="text">Text Elements (Elemen Teks)</option>
                        <option value="custom">Custom (Komponen Kustom)</option>
                        @foreach ($categories as $cat)
                            @if (!in_array($cat, ['blocks', 'text', 'custom']))
                                <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Kelompok palette di panel builder.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                    Tipe Section <span class="text-red-500">*</span>
                </label>
                <select name="type" x-model="type" @change="onTypeChange()"
                    class="w-full border rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-blue-400 outline-none font-mono">
                    <option value="text">text (Teks / Kop / Banner)</option>
                    <option value="table">table (Tabel Nilai / Data List)</option>
                    <option value="keyvalue">keyvalue (Identitas Santri / Baris Nilai)</option>
                    <option value="text-block">text-block (Kotak Catatan / Notes)</option>
                    <option value="signature">signature (Tanda Tangan Pejabat)</option>
                    <option value="divider">divider (Garis Pemisah)</option>
                    <option value="pagebreak">pagebreak (Pindah Halaman)</option>
                </select>
                <p class="text-[11px] text-gray-400 mt-1">Engine renderer yang digunakan kanvas.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                    Ikon / Emoji
                </label>
                <div class="flex items-center gap-2">
                    <input type="text" name="icon" x-model="icon" maxlength="10" placeholder="🔝"
                        class="w-16 border rounded-lg px-3 py-2 text-center text-lg focus:ring-2 focus:ring-blue-400 outline-none">

                    {{-- Quick Emoji Picker --}}
                    <div class="flex flex-wrap gap-1 flex-1">
                        <template
                            x-for="em in ['🔝','🆔','📊','📝','✍️','🔻','📄','➖','⏭️','🕌','📖','🏆','🎖️','⭐','📅','📌']"
                            :key="em">
                            <button type="button" @click="icon = em"
                                class="w-7 h-7 rounded hover:bg-gray-100 border text-sm flex items-center justify-center transition"
                                x-text="em"></button>
                        </template>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                    Urutan Tampil (Order)
                </label>
                <input type="number" name="order" x-model.number="order" min="0" required
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                <p class="text-[11px] text-gray-400 mt-1">Angka urutan di dalam kategori palette (contoh: 1, 2, 3).</p>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                Deskripsi Singkat (Tooltip)
            </label>
            <input type="text" name="description" x-model="description"
                placeholder="Penjelasan singkat komponen saat kursor diarahkan ke komponen"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                    Target Kurikulum
                </label>
                <select name="curriculum_id" x-model="curriculum_id"
                    class="w-full border rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-blue-400 outline-none">
                    <option value="">🌐 Semua Kurikulum (Universal / Global)</option>
                    @foreach ($curriculums ?? [] as $c)
                        <option value="{{ $c->id }}"
                            {{ isset($component) && $component->curriculum_id == $c->id ? 'selected' : '' }}>
                            {{ $c->name }} ({{ $c->code }})
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-gray-400 mt-1">Komponen hanya muncul saat mengedit template kurikulum ini.
                </p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                    Target Tahun Ajaran
                </label>
                <select name="academic_year_id" x-model="academic_year_id"
                    class="w-full border rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-blue-400 outline-none">
                    <option value="">📅 Semua Tahun Ajaran (Berlaku Selamanya)</option>
                    @foreach ($academicYears ?? [] as $ay)
                        <option value="{{ $ay->id }}"
                            {{ isset($component) && $component->academic_year_id == $ay->id ? 'selected' : '' }}>
                            {{ $ay->formatted_name ?? $ay->start_year . '/' . $ay->end_year }}
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-gray-400 mt-1">Batasi komponen untuk tahun ajaran tertentu (opsional).</p>
            </div>
        </div>

        <div class="pt-2">
            <label class="inline-flex items-center gap-2 cursor-pointer bg-gray-50 border rounded-lg px-4 py-2.5">
                <input type="checkbox" name="is_active" value="1" x-model="is_active"
                    class="w-4 h-4 rounded text-blue-600 focus:ring-blue-400">
                <span class="text-sm font-medium text-gray-800">Aktifkan komponen ini (muncul di panel kiri
                    builder)</span>
            </label>
        </div>
    </div>

    {{-- SECTION 2: Custom Content & Default Configuration --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <div>
                <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <span>⚙️</span> Custom Content &amp; Konfigurasi Bawaan
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">
                    Konten ini akan langsung terisi saat komponen di-drag atau diklik masuk ke dalam kanvas builder.
                </p>
            </div>

            <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-lg text-xs font-medium">
                <button type="button" @click="editorMode = 'visual'"
                    :class="editorMode === 'visual' ? 'bg-white text-blue-600 shadow-sm' :
                        'text-gray-600 hover:text-gray-900'"
                    class="px-3 py-1.5 rounded-md transition">
                    📝 Form Visual
                </button>
                <button type="button" @click="editorMode = 'json'; syncToJson()"
                    :class="editorMode === 'json' ? 'bg-white text-blue-600 shadow-sm' :
                        'text-gray-600 hover:text-gray-900'"
                    class="px-3 py-1.5 rounded-md transition font-mono">
                    { } Raw JSON
                </button>
            </div>
        </div>


        {{-- ======================================================== --}}
        {{-- VISUAL FORM BUILDER --}}
        {{-- ======================================================== --}}
        <div x-show="editorMode === 'visual'" class="space-y-4">
            {{-- 1. TYPE TEXT BUILDER --}}
            <template x-if="type === 'text'">
                <div class="space-y-4 bg-gray-50/70 p-4 rounded-xl border border-gray-200">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                            Teks Konten Bawaan
                        </label>
                        <textarea x-model="config.content" rows="4" placeholder="Ketik teks bawaan atau gunakan [[ binding ]]"
                            class="w-full border rounded-lg p-2.5 text-sm font-mono focus:ring-2 focus:ring-blue-400 outline-none"></textarea>

                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-xs text-gray-500 font-medium">Sisipkan Binding:</span>
                            <select
                                @change="if ($event.target.value) { config.content = (config.content || '') + ' [[ ' + $event.target.value + ' ]]'; $event.target.value = ''; }"
                                class="text-xs border rounded px-2 py-1 bg-white">
                                <option value="">-- Pilih variabel santri / rapor --</option>
                                <template x-for="(fields, model) in bindings" :key="model">
                                    <optgroup :label="model">
                                        <template x-for="f in fields" :key="f">
                                            <option :value="model + '.' + f" x-text="model + '.' + f"></option>
                                        </template>
                                    </optgroup>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 border-t pt-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Ukuran Font (pt)</label>
                            <input type="number" x-model.number="config.style.fontSize"
                                class="w-full border rounded px-2 py-1 text-sm bg-white">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Font Family</label>
                            <select x-model="config.style.fontFamily"
                                class="w-full border rounded px-2 py-1 text-sm bg-white">
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
                            <label class="block text-xs text-gray-600 mb-1">Perataan Teks</label>
                            <select x-model="config.style.textAlign"
                                class="w-full border rounded px-2 py-1 text-sm bg-white">
                                <option value="left">Kiri (Left)</option>
                                <option value="center">Tengah (Center)</option>
                                <option value="right">Kanan (Right)</option>
                                <option value="justify">Rata Kanan Kiri (Justify)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Warna Teks</label>
                            <input type="color" x-model="config.style.color"
                                class="w-full h-8 border rounded p-0.5 cursor-pointer">
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <label class="inline-flex items-center gap-1.5 text-xs text-gray-700 cursor-pointer">
                            <input type="checkbox" :checked="config.style.fontWeight === 'bold'"
                                @change="config.style.fontWeight = $event.target.checked ? 'bold' : 'normal'"
                                class="rounded">
                            <span class="font-bold">Tebal (Bold)</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 text-xs text-gray-700 cursor-pointer">
                            <input type="checkbox" :checked="config.style.fontStyle === 'italic'"
                                @change="config.style.fontStyle = $event.target.checked ? 'italic' : 'normal'"
                                class="rounded">
                            <span class="italic">Miring (Italic)</span>
                        </label>
                    </div>
                </div>
            </template>

            {{-- 2. TYPE TABLE BUILDER --}}
            <template x-if="type === 'table'">
                <div class="space-y-4 bg-gray-50/70 p-4 rounded-xl border border-gray-200">
                    {{-- Preset Kurikulum Loader --}}
                    <div class="flex items-center justify-between bg-blue-50/60 p-3 rounded-lg border border-blue-200">
                        <div class="text-xs text-blue-900 font-semibold flex items-center gap-1.5">
                            <span>⚡</span> Terapkan Template Standar Kurikulum:
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            <button type="button" @click="loadPreset('merdeka')"
                                class="px-2.5 py-1 text-xs bg-white hover:bg-blue-600 hover:text-white border border-blue-300 rounded font-medium transition">
                                Kurikulum Merdeka
                            </button>
                            <button type="button" @click="loadPreset('k13')"
                                class="px-2.5 py-1 text-xs bg-white hover:bg-blue-600 hover:text-white border border-blue-300 rounded font-medium transition">
                                K-13
                            </button>
                            <button type="button" @click="loadPreset('pesantren')"
                                class="px-2.5 py-1 text-xs bg-white hover:bg-blue-600 hover:text-white border border-blue-300 rounded font-medium transition">
                                Pesantren / Kitab
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-start">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Judul Tabel</label>
                            <input type="text" x-model="config.title" @input="syncToJson()"
                                placeholder="Contoh: A. NILAI AKADEMIK"
                                class="w-full border rounded px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-blue-400 outline-none">
                            <p class="text-[10px] text-gray-400 mt-1">Judul yang muncul di atas tabel rapor santri.</p>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-semibold text-gray-700 uppercase">Sumber Data (Data
                                    Source)</label>
                                <a href="{{ route('admin.report-builder.data-sources.index') }}" target="_blank"
                                    class="text-[11px] text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1 transition"
                                    title="Buka kelola data source di tab baru">
                                    <span>⚙️</span> Kelola Sumber Data ↗
                                </a>
                            </div>

                            <select x-model="config.dataSource" @change="onDataSourceSelectChange()"
                                class="w-full border rounded px-3 py-2 text-sm bg-white font-mono text-blue-700 font-semibold focus:ring-2 focus:ring-blue-400 outline-none">
                                <template x-for="source in dataSources" :key="source.key">
                                    <option :value="source.key" x-text="source.name + ' (' + source.key + ')'">
                                    </option>
                                </template>
                            </select>
                            <p class="text-[10px] text-gray-400 mt-1">Pilih sumber data yang akan di-looping pada baris
                                tabel rapor.</p>
                        </div>
                    </div>

                    {{-- Data Source Details & Field Badges Banner --}}
                    <div x-show="activeDataSource" x-transition
                        class="bg-gradient-to-r from-blue-50/80 to-indigo-50/80 p-3.5 rounded-xl border border-blue-200/80 space-y-2.5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="text-lg">📊</span>
                                <div>
                                    <span class="text-xs font-bold text-gray-900"
                                        x-text="activeDataSource?.name"></span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold uppercase ml-1.5"
                                        :class="activeDataSource?.category === 'academic' ? 'bg-blue-200 text-blue-800' :
                                            (activeDataSource?.category === 'pesantren' ?
                                                'bg-emerald-200 text-emerald-800' :
                                                (activeDataSource?.category === 'tahfidz' ?
                                                    'bg-amber-200 text-amber-800' : 'bg-gray-200 text-gray-800'))"
                                        x-text="activeDataSource?.category"></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button type="button" @click="applyDefaultColumnsFromDataSource()"
                                    class="px-2.5 py-1 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-sm transition flex items-center gap-1">
                                    <span>⚡</span> Terapkan Kolom Bawaan
                                </button>
                                <button type="button" @click="showSampleModal = !showSampleModal"
                                    class="px-2.5 py-1 text-xs bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 rounded-lg font-medium transition">
                                    <span x-text="showSampleModal ? 'Sembunyikan Sampel' : '👁️ Contoh Data'"></span>
                                </button>
                            </div>
                        </div>

                        <p class="text-[11px] text-gray-600 leading-relaxed" x-text="activeDataSource?.description"
                            x-show="activeDataSource?.description"></p>

                        {{-- Available Fields Pills --}}
                        <div class="border-t border-blue-200/60 pt-2">
                            <div
                                class="text-[11px] font-bold text-gray-700 uppercase mb-1.5 flex items-center justify-between">
                                <span>Field Tersedia (Klik untuk auto-insert kolom):</span>
                                <span class="text-[10px] text-gray-500 font-normal">Klik chip di bawah untuk menambah
                                    kolom</span>
                            </div>
                            <div class="flex flex-wrap gap-1.5 max-h-32 overflow-y-auto pr-1">
                                <template x-for="f in (activeDataSource?.fields || [])" :key="f.field">
                                    <button type="button" @click="addSpecificColumn(f)"
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded bg-white hover:bg-blue-100 hover:border-blue-400 border border-gray-200 text-[11px] text-gray-800 transition group shadow-2xs">
                                        <span class="text-blue-500 font-bold group-hover:scale-110 transition">+</span>
                                        <span class="font-medium" x-text="f.label || f.field"></span>
                                        <code class="text-[10px] text-blue-600 font-mono"
                                            x-text="'(' + f.field + ')'"></code>
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Collapsible Sample Data Box --}}
                        <div x-show="showSampleModal" x-transition class="mt-2 pt-2 border-t border-blue-200/60">
                            <div class="text-[11px] font-bold text-gray-700 uppercase mb-1">Contoh Data Sampel (JSON):
                            </div>
                            <pre class="bg-gray-900 text-green-400 p-2.5 rounded-lg text-[10px] font-mono max-h-36 overflow-y-auto"
                                x-text="JSON.stringify(activeDataSource?.sample_data, null, 2)"></pre>
                        </div>
                    </div>

                    {{-- Native Datalist for Autocomplete in Column Fields --}}
                    <datalist id="datasource-fields-options">
                        <template x-for="f in (activeDataSource?.fields || [])" :key="f.field">
                            <option :value="f.field"
                                :label="f.label ? (f.label + ' (' + (f.type || 'string') + ')') : f.field"></option>
                        </template>
                    </datalist>

                    <div class="border-t pt-3">
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase">Daftar Kolom
                                    Tabel</label>
                                <p class="text-[10px] text-gray-400">Atur header, field data, lebar kolom, dan perataan
                                    teks.</p>
                            </div>
                            <button type="button" @click="addTableColumn()"
                                class="text-xs bg-blue-600 text-white px-3 py-1.5 rounded-lg font-medium hover:bg-blue-700 transition flex items-center gap-1 shadow-sm">
                                <span>+</span> Tambah Kolom Kosong
                            </button>
                        </div>

                        <div class="space-y-2">
                            <template x-for="(col, idx) in (config.columns || [])" :key="idx">
                                <div
                                    class="bg-white p-2.5 rounded-lg border border-gray-200 flex flex-wrap items-center gap-2 hover:border-blue-300 transition shadow-2xs">
                                    <div class="w-8 text-center text-xs font-bold text-gray-400 font-mono"
                                        x-text="idx + 1"></div>
                                    <div class="flex-1 min-w-[130px]">
                                        <input type="text" x-model="col.header" @input="syncToJson()"
                                            placeholder="Header (e.g. Mata Pelajaran)"
                                            class="w-full border rounded px-2.5 py-1 text-xs focus:ring-1 focus:ring-blue-400 outline-none">
                                    </div>
                                    <div class="flex-1 min-w-[130px]">
                                        <input type="text" x-model="col.field" @input="syncToJson()"
                                            list="datasource-fields-options" placeholder="Field (e.g. subject.name)"
                                            class="w-full border rounded px-2.5 py-1 text-xs font-mono text-blue-600 focus:ring-1 focus:ring-blue-400 outline-none">
                                    </div>
                                    <div class="w-24">
                                        <input type="text" x-model="col.width" @input="syncToJson()"
                                            placeholder="Width (e.g. 20%)"
                                            class="w-full border rounded px-2 py-1 text-xs font-mono text-center">
                                    </div>
                                    <div class="w-24">
                                        <select x-model="col.align" @change="syncToJson()"
                                            class="w-full border rounded px-2 py-1 text-xs bg-white">
                                            <option value="left">Left</option>
                                            <option value="center">Center</option>
                                            <option value="right">Right</option>
                                        </select>
                                    </div>
                                    <button type="button" @click="removeTableColumn(idx)"
                                        class="text-red-500 hover:text-red-700 px-2 py-1 font-bold rounded hover:bg-red-50 transition"
                                        title="Hapus kolom">✕</button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="border-t pt-3 space-y-3">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="config.showFooter" @change="syncToJson()"
                                class="rounded text-blue-600">
                            <span class="text-xs font-semibold text-gray-700">Tampilkan Baris Footer / Rata-rata</span>
                        </label>
                        <div x-show="config.showFooter"
                            class="grid grid-cols-1 md:grid-cols-3 gap-3 pl-6 bg-white/70 p-3 rounded-lg border border-gray-200">
                            <div>
                                <label class="text-[11px] text-gray-600 font-medium block mb-1">Label Footer</label>
                                <input type="text" x-model="config.footerLabel" @input="syncToJson()"
                                    placeholder="Average"
                                    class="w-full border rounded px-2.5 py-1.5 text-xs bg-white focus:ring-1 focus:ring-blue-400 outline-none">
                            </div>
                            <div>
                                <label class="text-[11px] text-gray-600 font-medium block mb-1">Field Nilai
                                    Footer</label>
                                <input type="text" x-model="config.footerField" @input="syncToJson()"
                                    placeholder="average_score"
                                    class="w-full border rounded px-2.5 py-1.5 text-xs bg-white font-mono text-blue-600 focus:ring-1 focus:ring-blue-400 outline-none">
                            </div>
                            <div>
                                <label class="text-[11px] text-gray-600 font-medium block mb-1">Sejajarkan Nilai di
                                    Kolom</label>
                                <select x-model.number="config.footerTargetIndex" @change="syncToJson()"
                                    class="w-full border rounded px-2.5 py-1.5 text-xs bg-white focus:ring-1 focus:ring-blue-400 outline-none">
                                    <template x-for="(col, idx) in (config.columns || [])" :key="idx">
                                        <option :value="idx"
                                            x-text="'Kolom ' + (idx + 1) + ': ' + (col.header || col.field || ('Kolom ' + (idx + 1)))">
                                        </option>
                                    </template>
                                </select>
                                <p class="text-[10px] text-gray-400 mt-0.5">Kolom tempat angka rata-rata dimunculkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- 3. TYPE KEYVALUE / IDENTITY BUILDER --}}
            <template x-if="type === 'keyvalue' || type === 'identity'">
                <div class="space-y-4 bg-gray-50/70 p-4 rounded-xl border border-gray-200">
                    <div class="flex justify-between items-center">
                        <label class="block text-xs font-bold text-gray-700 uppercase">Daftar Baris Identitas (Label
                            &amp; Nilai)</label>
                        <button type="button" @click="addKeyValueField()"
                            class="text-xs bg-blue-600 text-white px-3 py-1 rounded font-medium hover:bg-blue-700 transition">
                            + Tambah Baris
                        </button>
                    </div>

                    <div class="space-y-2">
                        <template x-for="(f, idx) in (config.fields || [])" :key="idx">
                            <div class="bg-white p-2.5 rounded-lg border flex items-center gap-2">
                                <input type="text" x-model="f.label" placeholder="Label (e.g. Nama Santri)"
                                    class="w-1/3 border rounded px-2.5 py-1 text-xs font-medium">
                                <input type="text" x-model="f.binding"
                                    placeholder="Binding (e.g. student.full_name)"
                                    class="flex-1 border rounded px-2.5 py-1 text-xs font-mono text-blue-600">
                                <button type="button" @click="removeKeyValueField(idx)"
                                    class="text-red-500 hover:text-red-700 px-2 font-bold"
                                    title="Hapus field">✕</button>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- 4. TYPE TEXT-BLOCK (NOTES) BUILDER --}}
            <template x-if="type === 'text-block'">
                <div class="space-y-4 bg-gray-50/70 p-4 rounded-xl border border-gray-200">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Judul Catatan</label>
                        <input type="text" x-model="config.title" placeholder="Contoh: Catatan Wali Kelas"
                            class="w-full border rounded px-3 py-1.5 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Target Data
                            Binding</label>
                        <select x-model="config.binding"
                            class="w-full border rounded px-3 py-1.5 text-sm bg-white font-mono">
                            <option value="report_card.homeroom_notes">report_card.homeroom_notes (Catatan Wali Kelas)
                            </option>
                            <option value="report_card.headmaster_notes">report_card.headmaster_notes (Catatan
                                Pimpinan)
                            </option>
                            <option value="content">content (Teks Bebas)</option>
                        </select>
                    </div>
                </div>
            </template>

            {{-- 5. TYPE SIGNATURE BUILDER --}}
            <template x-if="type === 'signature'">
                <div class="space-y-4 bg-gray-50/70 p-4 rounded-xl border border-gray-200">
                    <div class="flex justify-between items-center">
                        <label class="block text-xs font-bold text-gray-700 uppercase">Kolom Tanda Tangan</label>
                        <button type="button" @click="addSignatureCol()"
                            class="text-xs bg-blue-600 text-white px-3 py-1 rounded font-medium hover:bg-blue-700 transition">
                            + Tambah TTD
                        </button>
                    </div>

                    <div class="space-y-2">
                        <template x-for="(col, idx) in (config.columns || [])" :key="idx">
                            <div class="bg-white p-3 rounded-lg border space-y-2">
                                <div class="flex items-center gap-2">
                                    <input type="text" x-model="col.label" placeholder="Label (e.g. Wali Kelas)"
                                        class="flex-1 border rounded px-2 py-1 text-xs">
                                    <select x-model="col.role"
                                        class="border rounded px-2 py-1 text-xs bg-white font-mono">
                                        <option value="homeroom_teacher">homeroom_teacher</option>
                                        <option value="headmaster">headmaster</option>
                                        <option value="official">official</option>
                                        <option value="parent">parent</option>
                                    </select>
                                    <button type="button" @click="removeSignatureCol(idx)"
                                        class="text-red-500 hover:text-red-700 font-bold px-2">✕</button>
                                </div>
                                <div class="flex items-center gap-4 text-xs text-gray-600">
                                    <label class="inline-flex items-center gap-1 cursor-pointer">
                                        <input type="checkbox" x-model="col.showImage" class="rounded">
                                        <span>Gambar TTD Digital</span>
                                    </label>
                                    <label class="inline-flex items-center gap-1 cursor-pointer">
                                        <input type="checkbox" x-model="col.showEmployeeNumber" class="rounded">
                                        <span>Tampilkan NIP</span>
                                    </label>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- 6. TYPE DIVIDER / PAGEBREAK --}}
            <template x-if="type === 'divider' || type === 'pagebreak'">
                <div class="p-6 text-center bg-gray-50 border rounded-xl text-gray-500 text-xs">
                    Tipe ini tidak memerlukan kustomisasi konten khusus.
                </div>
            </template>

        </div>

        {{-- ======================================================== --}}
        {{-- RAW JSON EDITOR --}}
        {{-- ======================================================== --}}
        <div x-show="editorMode === 'json'" class="space-y-2">
            <div class="flex justify-between items-center">
                <span class="text-xs font-mono text-gray-500">JSON Configuration Payload:</span>
                <button type="button" @click="formatJson()"
                    class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-2.5 py-1 rounded transition font-mono">
                    ✨ Format / Pretty JSON
                </button>
            </div>
            <textarea x-model="jsonRaw" @input="syncFromRawJson()" rows="12"
                class="w-full border rounded-lg p-3 text-xs font-mono bg-gray-900 text-emerald-400 focus:ring-2 focus:ring-blue-400 outline-none"></textarea>
            <p class="text-[11px] text-gray-400">
                JSON ini akan langsung dieksekusi oleh canvas builder saat komponen ditambahkan.
            </p>
        </div>
    </div>

    {{-- Form Submit Actions --}}
    <div class="flex items-center justify-end gap-3 pt-2">
        <a href="{{ route('admin.report-builder.components.index') }}"
            class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
            Batal
        </a>
        <button type="submit"
            class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm transition flex items-center gap-1.5">
            <span>💾</span> Simpan Komponen
        </button>
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
                    // Invalid json while typing, ignore
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
