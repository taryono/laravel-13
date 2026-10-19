<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Report Builder</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .drag-ghost {
            opacity: 0.4;
            background: #dbeafe;
        }

        .section-block {
            transition: all 0.2s;
        }

        .section-block:hover {
            background: #f9fafb;
        }

        .section-block.selected {
            outline: 2px solid #3b82f6;
            background: #eff6ff;
        }

        .palette-item {
            cursor: grab;
            user-select: none;
            transition: transform 0.1s, box-shadow 0.1s;
        }

        .palette-item:active {
            cursor: grabbing;
            transform: scale(0.98);
        }
    </style>
</head>

<body class="bg-gray-100">

    <div x-data="reportBuilder()" x-cloak class="flex h-screen">

        {{-- ===== LEFT PANEL (PALETTE & PROPERTIES) ===== --}}
        <aside class="w-80 bg-white border-r overflow-y-auto flex-shrink-0 flex flex-col justify-between">
            <div class="flex-1 overflow-y-auto">
                <div class="p-4 border-b bg-gray-50 flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-lg text-gray-800">🎨 Palette</h2>
                        <p class="text-xs text-gray-500">Klik / drag ke kanvas</p>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full font-medium"
                            x-text="(visiblePaletteComponents().length) + ' Komponen'"></span>
                        <a href="{{ route('admin.report-builder.components.index') }}" target="_blank"
                            class="text-[11px] text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-0.5"
                            title="Buka form kelola komponen & custom content">
                            ⚙️ Kelola
                        </a>
                    </div>
                </div>

                {{-- Dynamic Component Categories from Backend/Database --}}
                <template x-for="(items, catKey) in categories()" :key="catKey">
                    <div class="p-3 border-b">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-xs font-bold text-gray-600 uppercase tracking-wider"
                                x-text="categoryTitle(catKey)"></h3>
                            <span class="text-[10px] text-gray-400" x-text="items.length + ' item'"></span>
                        </div>
                        <div :class="catKey === 'blocks' ? 'grid grid-cols-2 gap-2' : 'space-y-1.5'">
                            <template x-for="item in items" :key="item.id || item.type">
                                <div :class="catKey === 'blocks' 
                                    ? 'palette-item bg-blue-50/70 border border-blue-200 rounded p-2 text-center text-xs hover:bg-blue-100 hover:shadow-sm' 
                                    : 'palette-item bg-green-50/70 border border-green-200 rounded p-2 text-xs hover:bg-green-100 hover:shadow-sm flex items-center gap-2'"
                                    draggable="true" 
                                    @dragstart="startDragComponent($event, item)"
                                    @click="addComponent(item)"
                                    :title="item.description || ('Tambahkan ' + item.name)">
                                    <div :class="catKey === 'blocks' ? 'text-lg mb-0.5' : 'text-base'" x-text="item.icon || '📄'"></div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-gray-700 truncate" x-text="item.name"></div>
                                        <div class="flex items-center gap-1 mt-0.5" :class="catKey === 'blocks' ? 'justify-center' : 'justify-start'">
                                            <template x-if="item.curriculum">
                                                <span class="text-[9px] bg-purple-100 text-purple-700 font-mono px-1 rounded" x-text="item.curriculum.code"></span>
                                            </template>
                                            <template x-if="item.academic_year">
                                                <span class="text-[9px] bg-amber-100 text-amber-700 font-mono px-1 rounded" x-text="item.academic_year.start_year + '/' + item.academic_year.end_year"></span>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Media Library --}}
                <div class="p-3 border-b">
                    <h3 class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">🖼️ Media</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <button @click="triggerUpload('logo')"
                            class="bg-purple-50 border border-purple-200 rounded p-2 text-xs hover:bg-purple-100 font-medium text-purple-800 flex items-center justify-center gap-1">
                            <span>+</span> Logo
                        </button>
                        <button @click="triggerUpload('signature')"
                            class="bg-purple-50 border border-purple-200 rounded p-2 text-xs hover:bg-purple-100 font-medium text-purple-800 flex items-center justify-center gap-1">
                            <span>+</span> TTD
                        </button>
                    </div>
                    <input type="file" x-ref="fileInput" class="hidden" @change="handleUpload($event)" accept="image/*">
                    <div class="mt-2 grid grid-cols-3 gap-1.5">
                        <template x-for="asset in assets" :key="asset.id">
                            <div class="group relative border rounded p-1 bg-gray-50 hover:bg-white hover:shadow-sm transition">
                                <img :src="asset.url" :alt="asset.name"
                                    class="w-full h-12 object-contain cursor-pointer"
                                    draggable="true" @dragstart="startDragAsset($event, asset)"
                                    @click="insertAssetAsImage(asset)" :title="asset.name">
                                <div class="text-[9px] text-gray-500 truncate text-center mt-0.5" x-text="asset.name"></div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Selected Element Properties --}}
                <div class="p-3 bg-amber-50/70 border-t border-amber-200" x-show="selectedElement">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xs font-bold text-amber-900 uppercase tracking-wider">⚙️ Properties</h3>
                        <div class="flex items-center gap-1">
                            <button @click="moveSection(-1)" title="Pindah ke Atas"
                                class="px-1.5 py-0.5 bg-white border border-gray-300 rounded text-xs hover:bg-gray-100">↑</button>
                            <button @click="moveSection(1)" title="Pindah ke Bawah"
                                class="px-1.5 py-0.5 bg-white border border-gray-300 rounded text-xs hover:bg-gray-100">↓</button>
                            <span class="text-[10px] text-gray-500 font-mono" x-text="'#' + (selectedElement?.order || 0)"></span>
                        </div>
                    </div>

                    <template x-if="selectedElement">
                        <div class="space-y-3 text-xs">

                            {{-- ============================== --}}
                            {{-- TEXT SECTION                   --}}
                            {{-- ============================== --}}
                            <template x-if="selectedElement.type === 'text'">
                                <div class="space-y-2">
                                    <div>
                                        <label class="block text-gray-600 font-semibold mb-1">Text Content</label>
                                        <textarea x-model="selectedElement.content" @input.debounce.250ms="refreshCanvas()" rows="3"
                                            class="w-full border rounded p-1.5 text-xs focus:ring-1 focus:ring-blue-400 outline-none" placeholder="Tulis teks atau binding..."></textarea>
                                        <p class="text-[10px] text-gray-400 mt-1">
                                            Gunakan <code class="bg-gray-100 px-1 rounded">[[ field ]]</code> untuk dynamic binding.
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-gray-600 mb-1">Quick Binding</label>
                                        <select @change="insertBinding($event.target.value); $event.target.value = ''"
                                            class="w-full border rounded p-1 text-xs bg-white">
                                            <option value="">-- Sisipkan Binding --</option>
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
                            </template>

                            {{-- ============================== --}}
                            {{-- IMAGE PROPERTIES               --}}
                            {{-- ============================== --}}
                            <template x-if="selectedElement.type === 'image'">
                                <div class="space-y-2">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-gray-600 mb-1">Lebar (px)</label>
                                            <input type="number" x-model.number="selectedElement.width"
                                                @input="refreshCanvas()" class="w-full border rounded p-1 text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-gray-600 mb-1">Tinggi (px)</label>
                                            <input type="number" x-model.number="selectedElement.height"
                                                @input="refreshCanvas()" class="w-full border rounded p-1 text-xs">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-gray-600 mb-1">Alignment</label>
                                        <div class="flex gap-1">
                                            <template x-for="align in ['left', 'center', 'right']" :key="align">
                                                <button @click="selectedElement.style.textAlign = align; refreshCanvas()"
                                                    :class="{ 'bg-blue-600 text-white': selectedElement.style.textAlign === align, 'bg-white text-gray-700': selectedElement.style.textAlign !== align }"
                                                    class="flex-1 border rounded py-1 text-xs capitalize" x-text="align"></button>
                                            </template>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-gray-600 mb-1">Margin Bawah (px)</label>
                                        <input type="number" x-model.number="selectedElement.style.marginBottom"
                                            @input="refreshCanvas()" class="w-full border rounded p-1 text-xs">
                                    </div>
                                </div>
                            </template>

                            {{-- ============================== --}}
                            {{-- KEYVALUE (IDENTITY) PROPERTIES --}}
                            {{-- ============================== --}}
                            <template x-if="selectedElement.type === 'keyvalue' || selectedElement.type === 'identity'">
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <label class="block text-gray-600 font-semibold">Baris Identitas</label>
                                        <button @click="addField()" class="text-xs bg-blue-50 text-blue-600 border border-blue-200 px-2 py-0.5 rounded hover:bg-blue-100">+ Tambah</button>
                                    </div>
                                    <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                                        <template x-for="(f, idx) in (selectedElement.fields || [])" :key="idx">
                                            <div class="p-1.5 bg-white border rounded space-y-1 relative">
                                                <div class="flex items-center gap-1">
                                                    <input type="text" x-model="f.label" @input="refreshCanvas()"
                                                        placeholder="Label (e.g. Nama)" class="flex-1 border rounded px-1.5 py-0.5 text-xs">
                                                    <button @click="removeField(idx)" class="text-red-500 hover:text-red-700 px-1 font-bold" title="Hapus field">✕</button>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <input type="text" x-model="f.binding" @input="refreshCanvas()"
                                                        placeholder="Binding (e.g. student.full_name)" class="flex-1 border rounded px-1.5 py-0.5 text-xs text-blue-600 font-mono">
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            {{-- ============================== --}}
                            {{-- TABLE PROPERTIES               --}}
                            {{-- ============================== --}}
                            <template x-if="selectedElement.type === 'table'">
                                <div class="space-y-2">
                                    <div>
                                        <label class="block text-gray-600 mb-1">Judul Tabel</label>
                                        <input type="text" x-model="selectedElement.title" @input="refreshCanvas()"
                                            class="w-full border rounded p-1 text-xs">
                                    </div>
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <label class="block text-gray-600 text-xs font-medium">Data Source</label>
                                            <a href="{{ route('admin.report-builder.data-sources.index') }}" target="_blank" class="text-[10px] text-blue-600 hover:text-blue-800 flex items-center gap-0.5 font-medium">
                                                <span>⚙️ Kelola</span>
                                            </a>
                                        </div>
                                        <select x-model="selectedElement.dataSource" @change="refreshCanvas()"
                                            class="w-full border rounded p-1 text-xs bg-white focus:ring-1 focus:ring-blue-500">
                                            @php
                                                $builderDataSources = $dataSources ?? \Models\ReportDataSource::active()->orderBy('order')->orderBy('name')->get();
                                            @endphp
                                            @forelse($builderDataSources as $ds)
                                                <option value="{{ $ds->key }}">{{ $ds->name }} ({{ $ds->category_label ?? ucfirst($ds->category) }})</option>
                                            @empty
                                                <option value="academic_scores">Academic Scores (Nilai Akademik)</option>
                                                <option value="sorogan">Sorogan</option>
                                                <option value="memorization">Memorization (Tahfidz)</option>
                                                <option value="pesantren_scores">Pesantren Scores</option>
                                            @endforelse
                                        </select>
                                    </div>

                                    <div class="border-t pt-2">
                                        <div class="flex justify-between items-center mb-1">
                                            <label class="block text-gray-600 font-semibold">Kolom Tabel</label>
                                            <button @click="addColumn()" class="text-xs bg-blue-50 text-blue-600 border border-blue-200 px-2 py-0.5 rounded hover:bg-blue-100">+ Kolom</button>
                                        </div>
                                        <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                                            <template x-for="(col, cIdx) in (selectedElement.columns || [])" :key="cIdx">
                                                <div class="p-1.5 bg-white border rounded space-y-1">
                                                    <div class="flex items-center gap-1">
                                                        <input type="text" x-model="col.header" @input="refreshCanvas()"
                                                            placeholder="Header" class="w-1/2 border rounded px-1.5 py-0.5 text-xs">
                                                        <input type="text" x-model="col.field" @input="refreshCanvas()"
                                                            placeholder="Field" class="w-1/2 border rounded px-1.5 py-0.5 text-xs font-mono text-blue-600">
                                                        <button @click="removeColumn(cIdx)" class="text-red-500 hover:text-red-700 px-1 font-bold">✕</button>
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <input type="text" x-model="col.width" @input="refreshCanvas()"
                                                            placeholder="Width (e.g. 20%)" class="w-1/2 border rounded px-1.5 py-0.5 text-xs">
                                                        <select x-model="col.align" @change="refreshCanvas()" class="w-1/2 border rounded p-0.5 text-xs bg-white">
                                                            <option value="left">Left</option>
                                                            <option value="center">Center</option>
                                                            <option value="right">Right</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="border-t pt-2 space-y-1.5">
                                        <label class="flex items-center gap-1.5 cursor-pointer">
                                            <input type="checkbox" x-model="selectedElement.showFooter" @change="refreshCanvas()" class="rounded">
                                            <span class="text-gray-700 font-medium">Tampilkan Footer / Rata-rata</span>
                                        </label>
                                        <template x-if="selectedElement.showFooter">
                                            <div class="space-y-1.5 pl-4 pt-1">
                                                <div class="grid grid-cols-2 gap-1.5">
                                                    <div>
                                                        <label class="text-[10px] text-gray-500 block">Label</label>
                                                        <input type="text" x-model="selectedElement.footerLabel" @input="refreshCanvas()"
                                                            placeholder="Average" class="w-full border rounded p-1 text-xs">
                                                    </div>
                                                    <div>
                                                        <label class="text-[10px] text-gray-500 block">Field Binding</label>
                                                        <input type="text" x-model="selectedElement.footerField" @input="refreshCanvas()"
                                                            placeholder="average_score" class="w-full border rounded p-1 text-xs font-mono">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="text-[10px] text-gray-500 block">Sejajarkan Nilai di Kolom</label>
                                                    <select x-model.number="selectedElement.footerTargetIndex" @change="refreshCanvas()" class="w-full border rounded p-1 text-xs bg-white">
                                                        <template x-for="(col, idx) in (selectedElement.columns || [])" :key="idx">
                                                            <option :value="idx" x-text="'Kolom ' + (idx + 1) + ': ' + (col.header || col.field)"></option>
                                                        </template>
                                                    </select>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            {{-- ============================== --}}
                            {{-- TEXT-BLOCK (NOTES) PROPERTIES  --}}
                            {{-- ============================== --}}
                            <template x-if="selectedElement.type === 'text-block'">
                                <div class="space-y-2">
                                    <div>
                                        <label class="block text-gray-600 mb-1">Judul Catatan</label>
                                        <input type="text" x-model="selectedElement.title" @input="refreshCanvas()"
                                            class="w-full border rounded p-1 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-gray-600 mb-1">Data Binding</label>
                                        <select x-model="selectedElement.binding" @change="refreshCanvas()"
                                            class="w-full border rounded p-1 text-xs bg-white">
                                            <option value="">-- Pilih Binding --</option>
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
                            </template>

                            {{-- ============================== --}}
                            {{-- SIGNATURE PROPERTIES           --}}
                            {{-- ============================== --}}
                            <template x-if="selectedElement.type === 'signature'">
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <label class="block text-gray-600 font-semibold">Kolom Tanda Tangan</label>
                                        <button @click="addSignatureColumn()" class="text-xs bg-blue-50 text-blue-600 border border-blue-200 px-2 py-0.5 rounded hover:bg-blue-100">+ TTD</button>
                                    </div>
                                    <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                                        <template x-for="(sig, sIdx) in (selectedElement.columns || [])" :key="sIdx">
                                            <div class="p-1.5 bg-white border rounded space-y-1">
                                                <div class="flex items-center gap-1">
                                                    <input type="text" x-model="sig.label" @input="refreshCanvas()"
                                                        placeholder="Label (e.g. Wali Kelas)" class="w-1/2 border rounded px-1.5 py-0.5 text-xs">
                                                    <select x-model="sig.role" @change="refreshCanvas()" class="w-1/2 border rounded p-0.5 text-xs bg-white font-mono">
                                                        <option value="homeroom_teacher">homeroom_teacher</option>
                                                        <option value="headmaster">headmaster</option>
                                                        <option value="official">official</option>
                                                        <option value="parent">parent</option>
                                                    </select>
                                                    <button @click="removeSignatureColumn(sIdx)" class="text-red-500 hover:text-red-700 px-1 font-bold">✕</button>
                                                </div>
                                                <div class="flex items-center gap-3 pt-1 text-[11px]">
                                                    <label class="flex items-center gap-1 cursor-pointer">
                                                        <input type="checkbox" x-model="sig.showImage" @change="refreshCanvas()" class="rounded">
                                                        <span>Gambar TTD</span>
                                                    </label>
                                                    <label class="flex items-center gap-1 cursor-pointer">
                                                        <input type="checkbox" x-model="sig.showEmployeeNumber" @change="refreshCanvas()" class="rounded">
                                                        <span>Tampilkan NIP</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            {{-- ============================== --}}
                            {{-- STYLE EDITOR (Untuk Tipe Teks) --}}
                            {{-- ============================== --}}
                            <template x-if="hasTextStyle(selectedElement)">
                                <div class="space-y-2 border-t border-amber-200 pt-2 mt-2">
                                    <h4 class="text-xs font-bold text-gray-600 uppercase tracking-wider">Style</h4>

                                    <div>
                                        <label class="block text-gray-600 mb-1">Font Family</label>
                                        <select x-model="selectedElement.style.fontFamily" @change="refreshCanvas()"
                                            class="w-full border rounded p-1 text-xs bg-white">
                                            <option>Times New Roman</option>
                                            <option>Arial</option>
                                            <option>Calibri</option>
                                            <option>Cambria</option>
                                            <option>Courier New</option>
                                            <option>Georgia</option>
                                            <option>Verdana</option>
                                        </select>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-gray-600 mb-1">Size (pt)</label>
                                            <input type="number" x-model.number="selectedElement.style.fontSize"
                                                @input="refreshCanvas()" class="w-full border rounded p-1 text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-gray-600 mb-1">Color</label>
                                            <input type="color" x-model="selectedElement.style.color"
                                                @input="refreshCanvas()" class="w-full h-7 border rounded cursor-pointer p-0.5">
                                        </div>
                                    </div>

                                    <div class="flex gap-1">
                                        <button @click="toggleBold()"
                                            :class="{ 'bg-blue-600 text-white': selectedElement.style.fontWeight==='bold', 'bg-white text-gray-700': selectedElement.style.fontWeight!=='bold' }"
                                            class="flex-1 border rounded py-1 font-bold text-xs">B</button>
                                        <button @click="toggleItalic()"
                                            :class="{ 'bg-blue-600 text-white': selectedElement.style.fontStyle==='italic', 'bg-white text-gray-700': selectedElement.style.fontStyle!=='italic' }"
                                            class="flex-1 border rounded py-1 italic text-xs">I</button>
                                        <button @click="toggleUnderline()"
                                            :class="{ 'bg-blue-600 text-white': selectedElement.style.textDecoration==='underline', 'bg-white text-gray-700': selectedElement.style.textDecoration!=='underline' }"
                                            class="flex-1 border rounded py-1 underline text-xs">U</button>
                                    </div>

                                    <div>
                                        <label class="block text-gray-600 mb-1">Alignment</label>
                                        <div class="flex gap-1">
                                            <template x-for="a in ['left','center','right','justify']" :key="a">
                                                <button @click="selectedElement.style.textAlign = a; refreshCanvas()"
                                                    :class="{ 'bg-blue-600 text-white': selectedElement.style.textAlign === a, 'bg-white text-gray-700': selectedElement.style.textAlign !== a }"
                                                    class="flex-1 border rounded py-1 text-xs"
                                                    x-text="a.charAt(0).toUpperCase()"></button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            {{-- DELETE BUTTON --}}
                            <div class="pt-2 border-t border-amber-200">
                                <button @click="deleteSelected()"
                                    class="w-full bg-red-50 border border-red-200 text-red-600 rounded py-1.5 text-xs font-semibold hover:bg-red-100 flex items-center justify-center gap-1">
                                    <span>🗑️</span> Hapus Section
                                </button>
                            </div>

                        </div>
                    </template>
                </div>
            </div>

            {{-- Sticky bottom actions --}}
            <div class="p-3 border-t bg-white flex-shrink-0 space-y-2">
                <button @click="showPreview()"
                    class="w-full bg-amber-500 text-white px-4 py-2 rounded text-sm hover:bg-amber-600 font-medium transition flex items-center justify-center gap-1.5 shadow-sm">
                    <span>👁️</span> Preview Laporan
                </button>
                <button @click="save()" :disabled="saving"
                    class="w-full bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 font-medium transition disabled:opacity-50 flex items-center justify-center gap-1.5 shadow-sm">
                    <span x-show="!saving">💾 Simpan Template</span>
                    <span x-show="saving">⏳ Menyimpan...</span>
                </button>
            </div>
        </aside>

        {{-- ===== CANVAS & MAIN CONTENT ===== --}}
        <main class="flex-1 flex flex-col min-w-0">
            {{-- Top Toolbar --}}
            <div class="bg-white border-b p-3 flex flex-wrap items-center justify-between gap-3 shadow-sm z-10">
                <div class="flex flex-wrap items-center gap-2">
                    <input type="text" x-model="form.name" placeholder="Nama Template"
                        class="border rounded px-2.5 py-1 text-sm w-48 lg:w-56 font-medium focus:ring-1 focus:ring-blue-400 outline-none">
                    
                    <select x-model="form.type" class="border rounded px-2 py-1 text-sm bg-white">
                        <option value="academic">Academic</option>
                        <option value="religious">Religious</option>
                        <option value="memorization">Memorization</option>
                        <option value="pesantren">Pesantren</option>
                        <option value="combined">Combined</option>
                    </select>

                    <select x-model="form.curriculum_id" class="border rounded px-2 py-1 text-sm bg-white text-gray-700 font-medium" title="Asosiasi & Filter Kurikulum">
                        <option value="">🌐 Semua Kurikulum (Universal)</option>
                        @foreach ($curriculums ?? [] as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>
                        @endforeach
                    </select>

                    <select x-model="form.academic_year_id" class="border rounded px-2 py-1 text-sm bg-white text-gray-700 font-medium" title="Asosiasi & Filter Tahun Ajaran">
                        <option value="">📅 Semua Thn Ajaran</option>
                        @foreach ($academicYears ?? [] as $ay)
                            <option value="{{ $ay->id }}">{{ $ay->formatted_name ?? ($ay->start_year . '/' . $ay->end_year) }}</option>
                        @endforeach
                    </select>

                    <select x-model="form.paper_size" @change="onPaperSizeChange()"
                        class="border rounded px-2 py-1 text-sm bg-white">
                        @foreach (config('report.paper_sizes') as $key => $preset)
                            <option value="{{ $key }}">{{ $preset['label'] }}</option>
                        @endforeach
                    </select>

                    {{-- Custom size inputs --}}
                    <template x-if="form.paper_size === 'Custom'">
                        <div class="flex items-center gap-1 bg-gray-50 border rounded px-1.5 py-0.5">
                            <input type="number" x-model.number="form.custom_paper_width" @input="refreshCanvas()"
                                placeholder="W" min="50" max="1000"
                                class="border rounded px-1 py-0.5 text-xs w-14 text-center">
                            <span class="text-xs text-gray-500">×</span>
                            <input type="number" x-model.number="form.custom_paper_height" @input="refreshCanvas()"
                                placeholder="H" min="50" max="1000"
                                class="border rounded px-1 py-0.5 text-xs w-14 text-center">
                            <span class="text-[10px] text-gray-500">mm</span>
                        </div>
                    </template>

                    <select x-model="form.orientation" @change="refreshCanvas()"
                        class="border rounded px-2 py-1 text-sm bg-white">
                        <option value="portrait">Portrait</option>
                        <option value="landscape">Landscape</option>
                    </select>

                    {{-- Margin Controls (Top, Right, Bottom, Left in cm) --}}
                    <div class="flex items-center gap-1 border rounded px-2 py-1 text-xs bg-gray-50" title="Margin Halaman: Atas, Kanan, Bawah, Kiri (cm)">
                        <span class="text-gray-500 font-medium">Margin:</span>
                        <input type="number" step="0.1" min="0" max="10" x-model.number="form.margin_top" @input="refreshCanvas()"
                            class="w-10 border rounded px-1 text-center text-xs" title="Top (cm)">
                        <input type="number" step="0.1" min="0" max="10" x-model.number="form.margin_right" @input="refreshCanvas()"
                            class="w-10 border rounded px-1 text-center text-xs" title="Right (cm)">
                        <input type="number" step="0.1" min="0" max="10" x-model.number="form.margin_bottom" @input="refreshCanvas()"
                            class="w-10 border rounded px-1 text-center text-xs" title="Bottom (cm)">
                        <input type="number" step="0.1" min="0" max="10" x-model.number="form.margin_left" @input="refreshCanvas()"
                            class="w-10 border rounded px-1 text-center text-xs" title="Left (cm)">
                        <span class="text-[10px] text-gray-400">cm</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button @click="showPreview()"
                        class="bg-amber-500 text-white px-3.5 py-1.5 rounded text-sm hover:bg-amber-600 transition flex items-center gap-1 font-medium shadow-sm">
                        <span>👁️</span> Preview
                    </button>
                    <button @click="save()" :disabled="saving"
                        class="bg-blue-600 text-white px-4 py-1.5 rounded text-sm hover:bg-blue-700 transition disabled:opacity-50 flex items-center gap-1 font-medium shadow-sm">
                        <span x-show="!saving">💾 Simpan</span>
                        <span x-show="saving">⏳ Menyimpan...</span>
                    </button>
                </div>
            </div>

            {{-- Canvas Area --}}
            <div class="flex-1 overflow-auto bg-gray-200 p-8 flex justify-center" @click="deselect()">
                <div id="canvas" class="bg-white shadow-xl transition-all" :style="canvasStyle()" @click.stop
                    @dragover.prevent @drop="handleDrop($event)">
                </div>
            </div>
        </main>

        {{-- ===== PREVIEW MODAL ===== --}}
        <div x-show="previewOpen" x-cloak
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl h-[92vh] flex flex-col overflow-hidden">
                <div class="p-3.5 border-b bg-gray-50 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">👁️</span>
                        <h3 class="font-bold text-gray-800">Preview Laporan</h3>
                        <span class="text-xs text-gray-500" x-text="'(' + form.paper_size + ' - ' + form.orientation + ')'"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <template x-if="form.id">
                            <a :href="'{{ url('admin/report-builder') }}/' + form.id + '/preview-pdf'" target="_blank"
                                class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded font-medium transition flex items-center gap-1 shadow-sm">
                                <span>📄</span> Cetak PDF
                            </a>
                        </template>
                        <button @click="reloadPreview()" class="text-xs bg-gray-200 hover:bg-gray-300 px-3 py-1.5 rounded font-medium transition flex items-center gap-1">
                            <span>🔄</span> Refresh
                        </button>
                        <button @click="previewOpen = false" class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded font-medium transition flex items-center gap-1">
                            <span>✕</span> Tutup
                        </button>
                    </div>
                </div>
                <iframe x-ref="previewFrame" class="flex-1 w-full border-0 bg-white"></iframe>
            </div>
        </div>

    </div>

    <script>
        function reportBuilder() {
            return {
                // ============================================================
                // STATE
                // ============================================================
                configuration: @json($configuration ?? ['sections' => []]),
                form: {
                    id: {{ $template?->id ?? 'null' }},
                    name: @json($template?->name ?? 'New Template'),
                    type: @json($template?->type ?? 'academic'),
                    curriculum_id: @json($currentCurriculumId ? (string)$currentCurriculumId : ''),
                    academic_year_id: @json($currentAcademicYearId ? (string)$currentAcademicYearId : ''),
                    paper_size: @json($template?->paper_size ?? 'A4'),
                    custom_paper_width: {{ $template?->custom_paper_width ?? 210 }},
                    custom_paper_height: {{ $template?->custom_paper_height ?? 297 }},
                    orientation: @json($template?->orientation ?? 'portrait'),
                    margin_top: {{ $template?->margin_top ?? 2 }},
                    margin_bottom: {{ $template?->margin_bottom ?? 2 }},
                    margin_left: {{ $template?->margin_left ?? 2.5 }},
                    margin_right: {{ $template?->margin_right ?? 2 }},
                },
                bindings: @json($bindings ?? []),
                assets: @json($assets ?? []),
                paperSizes: @json($paperSizes ?? []),
                paletteComponents: @json($paletteComponents ?? []),
                selectedElement: null,
                previewOpen: false,
                saving: false,
                uploadType: 'logo',

                // ============================================================
                // INIT (Otomatis dipanggil oleh Alpine.js)
                // ============================================================
                init() {
                    if (!this.configuration) this.configuration = { sections: [] };
                    if (!this.configuration.sections) this.configuration.sections = [];
                    this.renderCanvas();
                },

                // ============================================================
                // PALETTE FILTERING & CATEGORIES FROM DATABASE
                // ============================================================
                visiblePaletteComponents() {
                    const selCurr = this.form.curriculum_id ? String(this.form.curriculum_id) : '';
                    const selAy = this.form.academic_year_id ? String(this.form.academic_year_id) : '';

                    return (this.paletteComponents || []).filter(comp => {
                        // Jika komponen spesifik kurikulum tertentu, dan kurikulum dipilih di toolbar, cocokkan
                        if (selCurr && comp.curriculum_id) {
                            if (String(comp.curriculum_id) !== selCurr) return false;
                        }
                        // Jika komponen spesifik tahun ajaran tertentu, dan tahun ajaran dipilih di toolbar, cocokkan
                        if (selAy && comp.academic_year_id) {
                            if (String(comp.academic_year_id) !== selAy) return false;
                        }
                        return true;
                    });
                },

                categories() {
                    const cats = {};
                    (this.visiblePaletteComponents() || []).forEach(comp => {
                        const c = comp.category || 'blocks';
                        if (!cats[c]) cats[c] = [];
                        cats[c].push(comp);
                    });
                    return cats;
                },

                categoryTitle(cat) {
                    const titles = {
                        'blocks': '📦 Blocks',
                        'text': '✏️ Text Elements',
                        'general': '🧩 Komponen',
                    };
                    return titles[cat] || (cat.charAt(0).toUpperCase() + cat.slice(1));
                },

                // ============================================================
                // CANVAS STYLE
                // ============================================================
                canvasStyle() {
                    let w, h;

                    if (this.form.paper_size === 'Custom') {
                        w = Number(this.form.custom_paper_width) || 210;
                        h = Number(this.form.custom_paper_height) || 297;
                    } else {
                        const sizes = this.paperSizes[this.form.paper_size] ||
                            this.paperSizes.A4 || { width: 210, height: 297 };
                        w = sizes.width;
                        h = sizes.height;
                    }

                    const isPortrait = this.form.orientation === 'portrait';
                    const finalW = isPortrait ? w : h;
                    const finalH = isPortrait ? h : w;

                    const mTop = Number(this.form.margin_top) || 0;
                    const mRight = Number(this.form.margin_right) || 0;
                    const mBottom = Number(this.form.margin_bottom) || 0;
                    const mLeft = Number(this.form.margin_left) || 0;

                    return {
                        width: finalW + 'mm',
                        minHeight: finalH + 'mm',
                        padding: `${mTop}cm ${mRight}cm ${mBottom}cm ${mLeft}cm`,
                        boxSizing: 'border-box',
                    };
                },

                onPaperSizeChange() {
                    if (this.form.paper_size !== 'Custom') {
                        const preset = this.paperSizes[this.form.paper_size];
                        if (preset) {
                            this.form.custom_paper_width = preset.width;
                            this.form.custom_paper_height = preset.height;
                        }
                    }
                    this.refreshCanvas();
                },

                syncPageConfig() {
                    if (!this.configuration) this.configuration = { sections: [] };
                    if (!this.configuration.page) this.configuration.page = {};
                    this.configuration.page.size = this.form.paper_size;
                    this.configuration.page.orientation = this.form.orientation;
                    this.configuration.page.margin = {
                        top: Number(this.form.margin_top) || 2,
                        right: Number(this.form.margin_right) || 2,
                        bottom: Number(this.form.margin_bottom) || 2,
                        left: Number(this.form.margin_left) || 2.5,
                    };
                    if (this.form.paper_size === 'Custom') {
                        this.configuration.page.width = Number(this.form.custom_paper_width) || 210;
                        this.configuration.page.height = Number(this.form.custom_paper_height) || 297;
                    } else {
                        const preset = this.paperSizes[this.form.paper_size];
                        if (preset && preset.width && preset.height) {
                            this.configuration.page.width = Number(preset.width);
                            this.configuration.page.height = Number(preset.height);
                        } else {
                            delete this.configuration.page.width;
                            delete this.configuration.page.height;
                        }
                    }
                },

                // ============================================================
                // DRAG & DROP
                // ============================================================
                startDragComponent(evt, item) {
                    evt.dataTransfer.setData('kind', 'component');
                    evt.dataTransfer.setData('type', item.type);
                    evt.dataTransfer.setData('component', JSON.stringify(item));
                    evt.dataTransfer.effectAllowed = 'copy';
                },

                startDragAsset(evt, asset) {
                    evt.dataTransfer.setData('kind', 'asset');
                    evt.dataTransfer.setData('asset', JSON.stringify(asset));
                    evt.dataTransfer.effectAllowed = 'copy';
                },

                handleDrop(evt) {
                    evt.preventDefault();
                    const kind = evt.dataTransfer.getData('kind');
                    const type = evt.dataTransfer.getData('type');
                    const compRaw = evt.dataTransfer.getData('component');
                    const assetRaw = evt.dataTransfer.getData('asset');

                    if (kind === 'component' && compRaw) {
                        try {
                            const comp = JSON.parse(compRaw);
                            this.addSection(comp.type, comp.default_config);
                        } catch (e) {
                            if (type) this.addSection(type);
                        }
                    } else if (type) {
                        this.addSection(type);
                    } else if (kind === 'asset' && assetRaw) {
                        try {
                            this.insertAssetAsImage(JSON.parse(assetRaw));
                        } catch (e) {
                            console.error('Invalid asset dropped', e);
                        }
                    }
                },

                // ============================================================
                // SECTION FACTORY (DATABASE DRIVEN WITH FALLBACK)
                // ============================================================
                addComponent(component) {
                    this.addSection(component.type, component.default_config);
                },

                addSection(type, customDefault = null) {
                    const id = 'sec_' + Date.now() + '_' + Math.random().toString(36).slice(2, 6);
                    const order = (this.configuration.sections.length ?
                        Math.max(...this.configuration.sections.map(s => s.order || 0)) : 0) + 1;

                    let section = null;

                    if (customDefault && typeof customDefault === 'object') {
                        section = JSON.parse(JSON.stringify(customDefault));
                        section.id = id;
                        section.order = order;
                        if (!section.style) section.style = {};
                    } else if (type === 'header') {
                        section = {
                            id,
                            type: 'text',
                            order,
                            content: '[[settings.pesantren_name]]',
                            style: {
                                fontSize: 16,
                                fontWeight: 'bold',
                                textAlign: 'center',
                                color: '#000000',
                                fontFamily: 'Times New Roman',
                            },
                        };
                    } else if (type === 'footer') {
                        section = {
                            id,
                            type: 'text',
                            order,
                            content: 'Printed on [[ print_date ]] | Pondok Pesantren',
                            style: {
                                fontSize: 9,
                                textAlign: 'center',
                                color: '#666666',
                                fontFamily: 'Times New Roman',
                            },
                        };
                    } else if (type === 'text') {
                        section = {
                            id,
                            type: 'text',
                            order,
                            content: 'Custom text here',
                            style: {
                                fontSize: 12,
                                textAlign: 'left',
                                color: '#000000',
                                fontFamily: 'Times New Roman',
                            },
                        };
                    } else if (type === 'identity' || type === 'keyvalue') {
                        section = {
                            id,
                            type: 'keyvalue',
                            order,
                            fields: [
                                { label: 'Student Name', binding: 'student.full_name' },
                                { label: 'Student Number', binding: 'student.student_number' },
                                { label: 'Classroom', binding: 'classroom.name' },
                                { label: 'Dormitory', binding: 'dormitory.name' },
                            ],
                            style: {
                                fontSize: 12,
                                textAlign: 'left',
                                color: '#000000',
                                fontFamily: 'Times New Roman',
                            },
                        };
                    } else if (type === 'table') {
                        section = {
                            id,
                            type: 'table',
                            order,
                            title: 'A. ACADEMIC SCORES',
                            dataSource: 'academic_scores',
                            columns: [
                                { header: 'No', field: 'index', width: '5%', align: 'center' },
                                { header: 'Subject', field: 'subject.name', width: '45%', align: 'left' },
                                { header: 'Score', field: 'numeric_score', width: '20%', align: 'center' },
                                { header: 'Predicate', field: 'predicate', width: '30%', align: 'center' },
                            ],
                            style: {
                                fontSize: 10,
                                textAlign: 'left',
                                color: '#000000',
                                fontFamily: 'Times New Roman',
                            },
                            showFooter: false,
                            footerField: 'average_score',
                            footerLabel: 'Average',
                        };
                    } else if (type === 'text-block') {
                        section = {
                            id,
                            type: 'text-block',
                            order,
                            title: 'Homeroom Teacher Notes',
                            binding: 'report_card.homeroom_notes',
                            style: {
                                fontSize: 12,
                                fontStyle: 'italic',
                                textAlign: 'left',
                                color: '#000000',
                                fontFamily: 'Times New Roman',
                            },
                        };
                    } else if (type === 'signature') {
                        section = {
                            id,
                            type: 'signature',
                            order,
                            columns: [
                                {
                                    role: 'homeroom_teacher',
                                    label: 'Homeroom Teacher',
                                    source: 'automatic',
                                    showImage: true,
                                    showEmployeeNumber: true
                                },
                                {
                                    role: 'headmaster',
                                    label: 'Headmaster',
                                    source: 'automatic',
                                    showImage: true,
                                    showEmployeeNumber: false
                                },
                            ],
                            style: {
                                fontSize: 11,
                                textAlign: 'center',
                                color: '#000000',
                                fontFamily: 'Times New Roman',
                            },
                        };
                    } else if (type === 'divider') {
                        section = {
                            id,
                            type: 'divider',
                            order,
                            style: {},
                        };
                    } else if (type === 'pagebreak') {
                        section = {
                            id,
                            type: 'pagebreak',
                            order,
                            style: {},
                        };
                    }

                    if (section) {
                        this.configuration.sections.push(section);
                        this.renderCanvas();
                        this.selectSection(id);
                    }
                },

                insertAssetAsImage(asset) {
                    const id = 'sec_' + Date.now() + '_' + Math.random().toString(36).slice(2, 6);
                    const order = (this.configuration.sections.length ?
                        Math.max(...this.configuration.sections.map(s => s.order || 0)) : 0) + 1;

                    const sec = {
                        id,
                        type: 'image',
                        order,
                        assetId: asset.id,
                        assetUrl: asset.url,
                        width: 100,
                        height: 100,
                        style: {
                            textAlign: 'center',
                            marginBottom: 10
                        },
                    };

                    this.configuration.sections.push(sec);
                    this.renderCanvas();
                    this.selectSection(id);
                },

                // ============================================================
                // CANVAS RENDER
                // ============================================================
                renderCanvas() {
                    const canvas = document.getElementById('canvas');
                    if (!canvas) return;

                    const selectedId = this.selectedElement?.id;
                    canvas.innerHTML = '';

                    const sections = [...this.configuration.sections].sort((a, b) => (a.order || 0) - (b.order || 0));

                    sections.forEach(sec => {
                        const el = document.createElement('div');
                        el.className =
                            'section-block border border-dashed border-gray-300 p-3 mb-3 cursor-pointer rounded transition-all';
                        el.dataset.id = sec.id;
                        if (sec.id === selectedId) el.classList.add('selected');
                        el.innerHTML = this.renderSectionHtml(sec) ||
                            '<div class="text-xs text-gray-400 italic">(empty)</div>';

                        el.addEventListener('click', (e) => {
                            e.stopPropagation();
                            this.selectSection(sec.id);
                        });

                        canvas.appendChild(el);
                    });

                    // Init Sortable
                    if (window._sortable) window._sortable.destroy();
                    window._sortable = Sortable.create(canvas, {
                        animation: 150,
                        ghostClass: 'drag-ghost',
                        onEnd: () => {
                            const ids = Array.from(canvas.children).map(c => c.dataset.id);
                            ids.forEach((id, idx) => {
                                const s = this.configuration.sections.find(x => x.id === id);
                                if (s) {
                                    s.order = idx + 1;
                                    if (this.selectedElement?.id === id) {
                                        this.selectedElement.order = idx + 1;
                                    }
                                }
                            });
                        }
                    });
                },

                renderSectionHtml(sec) {
                    switch (sec.type) {
                        case 'text':
                            return this.renderTextElHtml(sec);

                        case 'image':
                            return `<div style="text-align:${sec.style?.textAlign || 'center'};margin-bottom:${sec.style?.marginBottom || 10}px;">
                                <img src="${sec.assetUrl || ''}"
                                    style="width:${sec.width || 80}px;height:${sec.height || 80}px;object-fit:contain;display:inline-block;"
                                    alt="Image">
                            </div>`;

                        case 'keyvalue':
                        case 'identity':
                            return `<div style="font-size:${sec.style?.fontSize || 12}pt;font-family:${sec.style?.fontFamily || 'inherit'};border:1px solid #e5e7eb;padding:8px;border-radius:4px;background:#fafafa;">
                                ${(sec.fields || []).map(f =>
                                    `<div style="margin-bottom:3px;display:flex;gap:4px;"><strong style="min-width:130px;">${this.escapeHtml(f.label)}:</strong> <span style="color:#2563eb;font-weight:500;">[[ ${this.escapeHtml(f.binding)} ]]</span></div>`
                                ).join('')}
                            </div>`;

                        case 'table':
                            return `<div style="font-weight:bold;margin-bottom:4px;font-size:${sec.style?.fontSize || 10}pt;font-family:${sec.style?.fontFamily || 'inherit'};">${this.escapeHtml(sec.title || '')}</div>
                            <table style="width:100%;border-collapse:collapse;font-size:${sec.style?.fontSize || 10}pt;font-family:${sec.style?.fontFamily || 'inherit'};">
                                <thead>
                                    <tr>${(sec.columns || []).map(c =>
                                        `<th style="border:1px solid #000;padding:4px;background:#e5e7eb;width:${c.width || 'auto'};text-align:${c.align || 'left'};">${this.escapeHtml(c.header)}</th>`
                                    ).join('')}</tr>
                                </thead>
                                <tbody>
                                    <tr>${(sec.columns || []).map(c =>
                                        `<td style="border:1px solid #000;padding:4px;text-align:${c.align || 'left'};color:#6b7280;font-style:italic;">[[ ${this.escapeHtml(c.field)} ]]</td>`
                                    ).join('')}</tr>
                                </tbody>
                                ${(() => {
                                    if (!sec.showFooter) return '';
                                    const cols = sec.columns || [];
                                    const colCount = cols.length;
                                    if (colCount === 0) return '';
                                    let targetIdx = sec.footerTargetIndex !== undefined ? parseInt(sec.footerTargetIndex) : null;
                                    if (targetIdx === null || isNaN(targetIdx) || targetIdx >= colCount) {
                                        const found = cols.findIndex(c => ['numeric_score', 'final_score', 'score'].includes(c.field));
                                        targetIdx = found !== -1 ? found : Math.max(0, colCount - 2);
                                    }
                                    const beforeSpan = targetIdx;
                                    const afterSpan = colCount - targetIdx - 1;
                                    let html = '<tfoot><tr>';
                                    if (beforeSpan > 0) {
                                        html += `<td ${beforeSpan > 1 ? `colspan="${beforeSpan}"` : ''} style="border:1px solid #000;padding:4px;text-align:right;background:#f5f5f5;font-weight:bold;">${this.escapeHtml(sec.footerLabel || 'Average')}</td>`;
                                    }
                                    html += `<td style="border:1px solid #000;padding:4px;text-align:center;background:#f5f5f5;font-weight:bold;color:#2563eb;">[[ ${this.escapeHtml(sec.footerField || 'average_score')} ]]</td>`;
                                    if (afterSpan > 0) {
                                        html += `<td ${afterSpan > 1 ? `colspan="${afterSpan}"` : ''} style="border:1px solid #000;background:#f5f5f5;"></td>`;
                                    }
                                    html += '</tr></tfoot>';
                                    return html;
                                })()}
                            </table>`;

                        case 'text-block':
                            return `<div style="font-weight:bold;font-size:${sec.style?.fontSize || 12}pt;font-family:${sec.style?.fontFamily || 'inherit'};margin-bottom:4px;">${this.escapeHtml(sec.title || '')}</div>
                            <div style="border:1px solid #cbd5e1;padding:8px;font-style:italic;min-height:50px;color:#2563eb;font-size:${sec.style?.fontSize || 12}pt;font-family:${sec.style?.fontFamily || 'inherit'};border-radius:4px;background:#f8fafc;">
                                [[ ${this.escapeHtml(sec.binding || 'content')} ]]
                            </div>`;

                        case 'signature':
                            const cols = sec.columns || [];
                            const colWidth = cols.length > 0 ? Math.floor(100 / cols.length) : 50;
                            return `<div style="display:flex;justify-content:space-around;font-size:${sec.style?.fontSize || 11}pt;font-family:${sec.style?.fontFamily || 'inherit'};margin-top:15px;">
                                ${cols.map(c => `<div style="text-align:center;width:${colWidth}%;padding:0 8px;">
                                    <div>${this.escapeHtml(c.label || '')},</div>
                                    <div style="height:50px;"></div>
                                    <div style="font-weight:bold;text-decoration:underline;color:#2563eb;">[[ signatures.${this.escapeHtml(c.role)}.name ]]</div>
                                    ${c.showEmployeeNumber ? `<div style="font-size:9pt;color:#666;">NIP: [[ signatures.${this.escapeHtml(c.role)}.employee_number ]]</div>` : ''}
                                </div>`).join('')}
                            </div>`;

                        case 'divider':
                            return '<hr style="border:none;border-top:1px solid #ccc;margin:12px 0;">';

                        case 'pagebreak':
                            return '<div style="border:1px dashed #94a3b8;padding:6px;text-align:center;font-size:11px;color:#64748b;background:#f1f5f9;border-radius:4px;">— Page Break —</div>';

                        default:
                            return `<div class="p-2 bg-gray-50 border rounded text-xs text-gray-500 italic">[${this.escapeHtml(sec.type)}] ${this.escapeHtml(sec.title || sec.content || 'Custom Section')}</div>`;
                    }
                },

                renderTextElHtml(el) {
                    const s = el.style || {};
                    const style = [
                        s.fontSize ? `font-size:${s.fontSize}pt` : '',
                        s.fontFamily ? `font-family:${s.fontFamily}` : '',
                        s.fontWeight ? `font-weight:${s.fontWeight}` : '',
                        s.fontStyle ? `font-style:${s.fontStyle}` : '',
                        s.textDecoration ? `text-decoration:${s.textDecoration}` : '',
                        s.color ? `color:${s.color}` : '',
                        s.textAlign ? `text-align:${s.textAlign}` : 'text-align:left',
                        s.marginTop ? `margin-top:${s.marginTop}px` : '',
                        s.marginBottom ? `margin-bottom:${s.marginBottom}px` : '',
                        'white-space:pre-wrap',
                        'word-break:break-word',
                    ].filter(Boolean).join(';');

                    const content = el.content ?
                        this.escapeHtml(el.content) :
                        '<span class="text-xs text-gray-400 italic">(Empty text)</span>';
                    return `<div style="${style}">${content}</div>`;
                },

                escapeHtml(str) {
                    if (str === null || str === undefined) return '';
                    return String(str)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                },

                // ============================================================
                // SELECTION
                // ============================================================
                selectSection(id) {
                    const sec = this.configuration.sections.find(s => s.id === id);
                    if (!sec) return;

                    // Pastikan style object selalu lengkap agar tidak melempar undefined error
                    if (!sec.style) sec.style = {};
                    if (!sec.style.fontFamily) sec.style.fontFamily = 'Times New Roman';
                    if (!sec.style.fontSize) sec.style.fontSize = 12;
                    if (!sec.style.color) sec.style.color = '#000000';
                    if (!sec.style.textAlign) sec.style.textAlign = 'left';

                    this.selectedElement = sec;

                    document.querySelectorAll('.section-block').forEach(e => e.classList.remove('selected'));
                    const el = document.querySelector(`[data-id="${id}"]`);
                    if (el) el.classList.add('selected');
                },

                deselect() {
                    this.selectedElement = null;
                    document.querySelectorAll('.section-block').forEach(e => e.classList.remove('selected'));
                },

                // ============================================================
                // STYLE HELPERS
                // ============================================================
                hasTextStyle(el) {
                    if (!el) return false;
                    return ['text', 'text-block', 'keyvalue', 'identity', 'table', 'signature'].includes(el.type);
                },

                ensureStyle() {
                    if (!this.selectedElement.style) {
                        this.selectedElement.style = {};
                    }
                    return this.selectedElement.style;
                },

                toggleBold() {
                    if (!this.selectedElement) return;
                    const s = this.ensureStyle();
                    s.fontWeight = s.fontWeight === 'bold' ? 'normal' : 'bold';
                    this.refreshCanvas();
                },

                toggleItalic() {
                    if (!this.selectedElement) return;
                    const s = this.ensureStyle();
                    s.fontStyle = s.fontStyle === 'italic' ? 'normal' : 'italic';
                    this.refreshCanvas();
                },

                toggleUnderline() {
                    if (!this.selectedElement) return;
                    const s = this.ensureStyle();
                    s.textDecoration = s.textDecoration === 'underline' ? 'none' : 'underline';
                    this.refreshCanvas();
                },

                // ============================================================
                // BINDING INSERTER
                // ============================================================
                insertBinding(binding) {
                    if (!binding || !this.selectedElement) return;
                    const placeholder = `[[ ${binding} ]]`;

                    if (this.selectedElement.type === 'text') {
                        this.selectedElement.content = (this.selectedElement.content || '') + placeholder;
                    } else if (this.selectedElement.type === 'text-block') {
                        this.selectedElement.binding = binding;
                    }

                    this.refreshCanvas();
                },

                // ============================================================
                // FIELD MANAGEMENT (keyvalue / identity)
                // ============================================================
                addField() {
                    if (!this.selectedElement) return;
                    if (!['keyvalue', 'identity'].includes(this.selectedElement.type)) return;
                    if (!this.selectedElement.fields) this.selectedElement.fields = [];
                    this.selectedElement.fields.push({
                        label: 'New Field',
                        binding: 'student.full_name'
                    });
                    this.refreshCanvas();
                },

                removeField(idx) {
                    if (!this.selectedElement) return;
                    if (!['keyvalue', 'identity'].includes(this.selectedElement.type)) return;
                    this.selectedElement.fields.splice(idx, 1);
                    this.refreshCanvas();
                },

                // ============================================================
                // COLUMN MANAGEMENT (table)
                // ============================================================
                addColumn() {
                    if (this.selectedElement?.type !== 'table') return;
                    if (!this.selectedElement.columns) this.selectedElement.columns = [];
                    this.selectedElement.columns.push({
                        header: 'New Column',
                        field: '',
                        width: '15%',
                        align: 'center',
                    });
                    this.refreshCanvas();
                },

                removeColumn(idx) {
                    if (this.selectedElement?.type !== 'table') return;
                    this.selectedElement.columns.splice(idx, 1);
                    this.refreshCanvas();
                },

                // ============================================================
                // SIGNATURE COLUMN MANAGEMENT
                // ============================================================
                addSignatureColumn() {
                    if (this.selectedElement?.type !== 'signature') return;
                    if (!this.selectedElement.columns) this.selectedElement.columns = [];
                    this.selectedElement.columns.push({
                        role: 'official',
                        label: 'Official',
                        source: 'automatic',
                        showImage: false,
                        showEmployeeNumber: false,
                    });
                    this.refreshCanvas();
                },

                removeSignatureColumn(idx) {
                    if (this.selectedElement?.type !== 'signature') return;
                    this.selectedElement.columns.splice(idx, 1);
                    this.refreshCanvas();
                },

                // ============================================================
                // ORDER MANAGEMENT
                // ============================================================
                moveSection(direction) {
                    if (!this.selectedElement) return;
                    const sections = this.configuration.sections;
                    const currentIndex = sections.findIndex(s => s.id === this.selectedElement.id);
                    if (currentIndex === -1) return;

                    const targetIndex = currentIndex + direction;
                    if (targetIndex < 0 || targetIndex >= sections.length) return;

                    // Swap
                    const temp = sections[currentIndex];
                    sections[currentIndex] = sections[targetIndex];
                    sections[targetIndex] = temp;

                    // Re-assign order numbers
                    sections.forEach((s, idx) => s.order = idx + 1);

                    this.renderCanvas();
                    this.selectSection(temp.id);
                },

                // ============================================================
                // DELETE SECTION
                // ============================================================
                deleteSelected() {
                    if (!this.selectedElement) return;
                    if (!confirm('Hapus section ini dari template?')) return;

                    this.configuration.sections = this.configuration.sections
                        .filter(s => s.id !== this.selectedElement.id);

                    this.configuration.sections
                        .sort((a, b) => (a.order || 0) - (b.order || 0))
                        .forEach((s, idx) => s.order = idx + 1);

                    this.selectedElement = null;
                    this.renderCanvas();
                },

                refreshCanvas() {
                    this.renderCanvas();
                },

                // ============================================================
                // ASSET UPLOAD
                // ============================================================
                triggerUpload(type) {
                    this.uploadType = type;
                    this.$refs.fileInput.click();
                },

                async handleUpload(evt) {
                    const file = evt.target.files[0];
                    if (!file) return;

                    const fd = new FormData();
                    fd.append('file', file);
                    fd.append('type', this.uploadType);
                    if (this.form.id) fd.append('template_id', this.form.id);

                    try {
                        const res = await fetch('{{ route('admin.report-builder.upload-asset') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                            },
                            body: fd,
                        });
                        const json = await res.json();
                        if (json.success) {
                            this.assets.push(json.asset);
                        } else {
                            alert('Upload gagal: ' + (json.message || 'Unknown error'));
                        }
                    } catch (err) {
                        alert('Upload error: ' + err.message);
                    }
                    evt.target.value = '';
                },

                // ============================================================
                // PREVIEW MODAL
                // ============================================================
                async showPreview() {
                    this.previewOpen = true;
                    await this.$nextTick();
                    await this.reloadPreview();
                },

                async reloadPreview() {
                    this.syncPageConfig();
                    try {
                        const res = await fetch('{{ route('admin.report-builder.preview') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'text/html',
                            },
                            body: JSON.stringify({
                                configuration: this.configuration,
                                paper_size: this.form.paper_size,
                                custom_paper_width: this.form.custom_paper_width,
                                custom_paper_height: this.form.custom_paper_height,
                                orientation: this.form.orientation,
                                margin_top: this.form.margin_top,
                                margin_bottom: this.form.margin_bottom,
                                margin_left: this.form.margin_left,
                                margin_right: this.form.margin_right,
                            }),
                        });

                        if (!res.ok) {
                            const txt = await res.text();
                            console.error('Preview error:', txt);
                            alert('Preview error: HTTP ' + res.status);
                            return;
                        }

                        const html = await res.text();
                        if (this.$refs.previewFrame) {
                            this.$refs.previewFrame.srcdoc = html;
                        }
                    } catch (err) {
                        console.error('Preview error:', err);
                        alert('Preview error: ' + err.message);
                    }
                },

                // ============================================================
                // SAVE TEMPLATE
                // ============================================================
                async save() {
                    if (!this.form.name.trim()) {
                        alert('Silakan masukkan nama template terlebih dahulu.');
                        return;
                    }

                    this.saving = true;
                    this.syncPageConfig();

                    try {
                        const payload = {
                            ...this.form,
                            configuration: this.configuration,
                        };

                        const res = await fetch('{{ route('admin.report-builder.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(payload),
                        });

                        const json = await res.json();

                        if (json.success) {
                            alert('✅ ' + json.message);
                            if (json.template_id && !this.form.id) {
                                window.location.href = '{{ url('admin/report-builder/builder') }}/' + json.template_id;
                            } else {
                                this.form.id = json.template_id || this.form.id;
                            }
                        } else {
                            alert('❌ ' + (json.message || 'Gagal menyimpan template'));
                        }
                    } catch (err) {
                        alert('Save error: ' + err.message);
                    } finally {
                        this.saving = false;
                    }
                },
            }
        }
    </script>

</body>

</html>
