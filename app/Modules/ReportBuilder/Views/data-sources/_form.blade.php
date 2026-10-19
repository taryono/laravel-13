@php
    $isEdit = !empty($dataSource->id);
    $initialFields = $dataSource->fields ?? [
        ['field' => 'index', 'label' => 'No', 'type' => 'number', 'default_width' => '8%', 'default_align' => 'center'],
        ['field' => 'name', 'label' => 'Nama / Judul', 'type' => 'string', 'default_width' => '50%', 'default_align' => 'left'],
        ['field' => 'score', 'label' => 'Nilai', 'type' => 'number', 'default_width' => '20%', 'default_align' => 'center'],
        ['field' => 'description', 'label' => 'Keterangan', 'type' => 'string', 'default_width' => '22%', 'default_align' => 'left'],
    ];
    $initialSample = $dataSource->sample_data ?? [
        ['index' => 1, 'name' => 'Contoh Baris 1', 'score' => 90, 'description' => 'Sangat Baik'],
        ['index' => 2, 'name' => 'Contoh Baris 2', 'score' => 85, 'description' => 'Baik'],
    ];
@endphp

<div x-data="dataSourceForm({
    key: '{{ old('key', $dataSource->key ?? '') }}',
    name: '{{ old('name', $dataSource->name ?? '') }}',
    category: '{{ old('category', $dataSource->category ?? 'custom') }}',
    description: '{{ old('description', $dataSource->description ?? '') }}',
    order: {{ old('order', $dataSource->order ?? 1) }},
    is_active: {{ old('is_active', $dataSource->is_active ?? true) ? 'true' : 'false' }},
    fields: {{ json_encode(old('fields', $initialFields)) }},
    sampleData: {{ json_encode(old('sample_data', $initialSample)) }}
})" class="space-y-6">

    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
            <div class="flex items-center gap-2 text-red-800 text-sm font-semibold mb-1">
                <span>⚠️</span> Ada kesalahan pada formulir:
            </div>
            <ul class="list-disc list-inside text-xs text-red-700 space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Section 1: Informasi Pokok --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2 border-b pb-2">
            <span>📝</span> Informasi Pokok Sumber Data
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Name --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                    Nama Sumber Data <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" x-model="name" @input="onNameChange()" required
                    placeholder="Contoh: Nilai Mata Pelajaran (Akademik)"
                    class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <p class="text-[11px] text-gray-400 mt-1">Nama deskriptif yang muncul pada pilihan Data Source di Report Builder.</p>
            </div>

            {{-- Key / Identifier --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                    Kunci Unik (Key / Slug) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" name="key" x-model="key" required
                        placeholder="contoh: academic_scores"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm font-mono text-blue-600 focus:ring-2 focus:ring-blue-500 focus:outline-none {{ $isEdit && $dataSource->is_system ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                        {{ $isEdit && $dataSource->is_system ? 'readonly' : '' }}>
                </div>
                <p class="text-[11px] text-gray-400 mt-1">
                    Gunakan huruf kecil, angka, dan underscore (_). Dipakai di template Handlebars (e.g. <code>[[#each key]]</code>).
                </p>
            </div>

            {{-- Category --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <select name="category" x-model="category" required
                    class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="academic">Akademik</option>
                    <option value="pesantren">Kepesantrenan</option>
                    <option value="tahfidz">Tahfidz Al-Qur'an</option>
                    <option value="attendance">Kehadiran</option>
                    <option value="character">Karakter & Adab</option>
                    <option value="custom">Kustom / Lainnya</option>
                </select>
            </div>

            {{-- Order & Status --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                        Urutan Tampil
                    </label>
                    <input type="number" name="order" x-model="order" min="0"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm font-mono focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                        Status Aktif
                    </label>
                    <div class="pt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" x-model="is_active"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-xs font-medium text-gray-700" x-text="is_active ? 'Aktif' : 'Nonaktif'"></span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">
                    Deskripsi / Penjelasan Singkat
                </label>
                <textarea name="description" x-model="description" rows="2"
                    placeholder="Jelaskan jenis data yang dicakup oleh sumber data ini..."
                    class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            </div>
        </div>
    </div>

    {{-- Section 2: Skema Field / Kolom Tersedia --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b pb-3">
            <div>
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                    <span>🏷️</span> Skema Field / Kolom Tersedia
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    Field-field inilah yang akan otomatis muncul dan dapat dipilih saat merancang tabel rapor.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" @click="addField()"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition flex items-center gap-1 shadow-sm">
                    <span>+</span> Tambah Field
                </button>
            </div>
        </div>

        {{-- Quick Add Badges --}}
        <div class="flex flex-wrap items-center gap-1.5 bg-gray-50 p-2.5 rounded-lg border border-gray-200">
            <span class="text-[11px] text-gray-500 font-medium">Tambah Cepat:</span>
            <button type="button" @click="quickAddField('index', 'No', 'number', '8%', 'center')"
                class="px-2 py-0.5 rounded text-[11px] bg-white border border-gray-300 hover:border-blue-500 text-gray-700 transition">
                + No (index)
            </button>
            <button type="button" @click="quickAddField('name', 'Nama Item', 'string', '40%', 'left')"
                class="px-2 py-0.5 rounded text-[11px] bg-white border border-gray-300 hover:border-blue-500 text-gray-700 transition">
                + Nama / Judul
            </button>
            <button type="button" @click="quickAddField('score', 'Nilai Angka', 'number', '15%', 'center')"
                class="px-2 py-0.5 rounded text-[11px] bg-white border border-gray-300 hover:border-blue-500 text-gray-700 transition">
                + Nilai Angka
            </button>
            <button type="button" @click="quickAddField('predicate', 'Predikat', 'string', '15%', 'center')"
                class="px-2 py-0.5 rounded text-[11px] bg-white border border-gray-300 hover:border-blue-500 text-gray-700 transition">
                + Predikat
            </button>
            <button type="button" @click="quickAddField('description', 'Keterangan / Capaian', 'string', '35%', 'left')"
                class="px-2 py-0.5 rounded text-[11px] bg-white border border-gray-300 hover:border-blue-500 text-gray-700 transition">
                + Keterangan
            </button>
        </div>

        {{-- Fields List Table --}}
        <div class="overflow-x-auto border rounded-lg">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 border-b text-[11px] font-bold text-gray-600 uppercase">
                    <tr>
                        <th class="py-2.5 px-3 w-8 text-center">#</th>
                        <th class="py-2.5 px-3">Field Key / Path <span class="text-red-500">*</span></th>
                        <th class="py-2.5 px-3">Label Header Kolom</th>
                        <th class="py-2.5 px-3 w-28">Tipe Data</th>
                        <th class="py-2.5 px-3 w-24">Lebar Bawaan</th>
                        <th class="py-2.5 px-3 w-24 text-center">Perataan</th>
                        <th class="py-2.5 px-3 w-10 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="(col, idx) in fields" :key="idx">
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="py-2 px-3 text-center text-gray-400 font-mono font-bold" x-text="idx + 1"></td>
                            <td class="py-2 px-3">
                                <input type="text" x-model="col.field" required placeholder="e.g. subject.name"
                                    class="w-full border rounded px-2.5 py-1 text-xs font-mono text-blue-600 focus:ring-1 focus:ring-blue-500">
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" x-model="col.label" placeholder="e.g. Mata Pelajaran"
                                    class="w-full border rounded px-2.5 py-1 text-xs focus:ring-1 focus:ring-blue-500">
                            </td>
                            <td class="py-2 px-3">
                                <select x-model="col.type" class="w-full border rounded px-2 py-1 text-xs bg-white">
                                    <option value="string">Text (string)</option>
                                    <option value="number">Angka (number)</option>
                                    <option value="date">Tanggal (date)</option>
                                    <option value="boolean">Boolean</option>
                                </select>
                            </td>
                            <td class="py-2 px-3">
                                <input type="text" x-model="col.default_width" placeholder="e.g. 20%"
                                    class="w-full border rounded px-2 py-1 text-xs font-mono">
                            </td>
                            <td class="py-2 px-3 text-center">
                                <select x-model="col.default_align" class="w-full border rounded px-2 py-1 text-xs bg-white">
                                    <option value="left">Left</option>
                                    <option value="center">Center</option>
                                    <option value="right">Right</option>
                                </select>
                            </td>
                            <td class="py-2 px-3 text-center">
                                <button type="button" @click="removeField(idx)"
                                    class="text-red-500 hover:text-red-700 font-bold px-1 py-0.5 rounded hover:bg-red-50"
                                    title="Hapus field">✕</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Hidden JSON input for fields --}}
        <input type="hidden" name="fields" :value="JSON.stringify(fields)">
    </div>

    {{-- Section 3: Data Sampel (Sample Data) --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b pb-3">
            <div>
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                    <span>🧪</span> Data Sampel untuk Live Preview
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    Data contoh berformat JSON array yang dipakai untuk render preview rapor di studio builder.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" @click="generateSampleFromFields()"
                    class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-semibold px-3 py-1.5 rounded-lg transition flex items-center gap-1">
                    <span>⚡</span> Auto-Generate dari Field
                </button>
                <button type="button" @click="formatSampleJson()"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                    Format JSON
                </button>
            </div>
        </div>

        <div>
            <textarea x-model="sampleDataRaw" rows="6" @input="syncSampleFromJson()"
                placeholder="[ { &quot;index&quot;: 1, &quot;name&quot;: &quot;Contoh 1&quot; } ]"
                class="w-full border border-gray-300 rounded-lg p-3 text-xs font-mono bg-gray-900 text-green-400 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
            <p class="text-[11px] text-gray-400 mt-1">
                Pastikan format berupa JSON Array of Objects valid.
            </p>
        </div>

        {{-- Hidden JSON input for sample_data --}}
        <input type="hidden" name="sample_data" :value="JSON.stringify(sampleData)">
    </div>

    {{-- Form Buttons --}}
    <div class="flex items-center justify-between pt-2">
        <a href="{{ route('admin.report-builder.data-sources.index') }}"
            class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 text-sm font-medium transition">
            Batal
        </a>
        <button type="submit"
            class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm transition flex items-center gap-1.5">
            <span>💾</span> Simpan Sumber Data
        </button>
    </div>

</div>

<script>
    function dataSourceForm(initial) {
        return {
            key: initial.key || '',
            name: initial.name || '',
            category: initial.category || 'custom',
            description: initial.description || '',
            order: initial.order ?? 1,
            is_active: initial.is_active ?? true,
            fields: Array.isArray(initial.fields) ? initial.fields : [],
            sampleData: Array.isArray(initial.sampleData) ? initial.sampleData : [],
            sampleDataRaw: JSON.stringify(initial.sampleData || [], null, 2),

            onNameChange() {
                if (!this.key || !this.key.trim()) {
                    this.key = this.name.toLowerCase()
                        .replace(/[^a-z0-9]+/g, '_')
                        .replace(/^_+|_+$/g, '');
                }
            },

            addField() {
                this.fields.push({
                    field: 'kolom_' + (this.fields.length + 1),
                    label: 'Kolom ' + (this.fields.length + 1),
                    type: 'string',
                    default_width: '20%',
                    default_align: 'left'
                });
            },

            quickAddField(field, label, type, width, align) {
                // Jangan duplikasi jika sudah ada
                if (this.fields.some(f => f.field === field)) {
                    return;
                }
                this.fields.push({
                    field: field,
                    label: label,
                    type: type,
                    default_width: width,
                    default_align: align
                });
            },

            removeField(idx) {
                this.fields.splice(idx, 1);
            },

            generateSampleFromFields() {
                const sampleRow1 = {};
                const sampleRow2 = {};

                this.fields.forEach((f, i) => {
                    const key = f.field || ('col_' + (i + 1));
                    if (f.type === 'number') {
                        sampleRow1[key] = f.field === 'index' ? 1 : 90;
                        sampleRow2[key] = f.field === 'index' ? 2 : 85;
                    } else if (f.type === 'date') {
                        sampleRow1[key] = '2026-10-20';
                        sampleRow2[key] = '2026-10-21';
                    } else {
                        sampleRow1[key] = f.label ? (f.label + ' A') : 'Data Contoh 1';
                        sampleRow2[key] = f.label ? (f.label + ' B') : 'Data Contoh 2';
                    }
                });

                this.sampleData = [sampleRow1, sampleRow2];
                this.sampleDataRaw = JSON.stringify(this.sampleData, null, 2);
            },

            syncSampleFromJson() {
                try {
                    const parsed = JSON.parse(this.sampleDataRaw);
                    if (Array.isArray(parsed)) {
                        this.sampleData = parsed;
                    }
                } catch (e) {
                    // Ignore while typing
                }
            },

            formatSampleJson() {
                try {
                    const parsed = JSON.parse(this.sampleDataRaw);
                    this.sampleData = Array.isArray(parsed) ? parsed : [parsed];
                    this.sampleDataRaw = JSON.stringify(this.sampleData, null, 2);
                } catch (e) {
                    alert('Format JSON tidak valid: ' + e.message);
                }
            }
        };
    }
</script>
