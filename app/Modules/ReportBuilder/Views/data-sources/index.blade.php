<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Sumber Data (Data Sources)_Report Builder</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="dataSourcesIndex()">

        {{-- Top Breadcrumb / Nav --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.report-builder.index') }}"
                    class="text-sm font-medium text-gray-500 hover:text-gray-700 flex items-center gap-1 transition">
                    ← Kembali ke Daftar Template
                </a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('admin.report-builder.builder') }}" target="_blank"
                    class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1 transition">
                    🎨 Buka Builder ↗
                </a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('admin.report-builder.components.index') }}"
                    class="text-sm font-medium text-gray-600 hover:text-gray-900 flex items-center gap-1 transition">
                    🧩 Kelola Komponen Palette
                </a>
            </div>
        </div>

        {{-- Header Title & Action --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <span>📊</span> Kelola Sumber Data (Data Sources)
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    Atur daftar sumber data tabel rapor, skema field yang tersedia, dan data sampel untuk live preview builder.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.report-builder.data-sources.create') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition">
                    <span class="text-base font-bold">+</span> Tambah Sumber Data Baru
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2 text-green-800 text-sm font-medium">
                    <span>✅</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 text-sm font-bold">✕</button>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2 text-red-800 text-sm font-medium">
                    <span>⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 text-sm font-bold">✕</button>
            </div>
        @endif

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                    📊
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Total Sumber Data</div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-green-50 text-green-600 flex items-center justify-center text-xl font-bold">
                    ✅
                </div>
                <div>
                    <div class="text-2xl font-bold text-green-700">{{ $stats['active'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Status Aktif</div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold">
                    📁
                </div>
                <div>
                    <div class="text-2xl font-bold text-purple-700">{{ $stats['categories'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Kategori Tersedia</div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                    🛡️
                </div>
                <div>
                    <div class="text-2xl font-bold text-amber-700">{{ $stats['system'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Bawaan Sistem</div>
                </div>
            </div>
        </div>

        {{-- Filters & Search Bar --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-1.5 w-full sm:w-auto">
                <a href="{{ route('admin.report-builder.data-sources.index') }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ empty($category) || $category === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Semua ({{ $stats['total'] }})
                </a>
                @foreach ($categories as $cat)
                    <a href="{{ route('admin.report-builder.data-sources.index', ['category' => $cat]) }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-medium transition capitalize {{ $category === $cat ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>

            <form action="{{ route('admin.report-builder.data-sources.index') }}" method="GET" class="w-full sm:w-72">
                @if ($category)
                    <input type="hidden" name="category" value="{{ $category }}">
                @endif
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari sumber data..."
                        class="w-full pl-9 pr-4 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <span class="absolute left-3 top-2 text-gray-400 text-xs">🔍</span>
                </div>
            </form>
        </div>

        {{-- Data Sources Table / Cards --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-[11px] font-bold text-gray-600 uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">Urutan</th>
                            <th class="py-3.5 px-4">Nama & Kunci Sumber Data</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4">Field / Kolom Tersedia</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs">
                        @forelse ($dataSources as $source)
                            <tr class="hover:bg-gray-50/80 transition group">
                                <td class="py-3.5 px-4 text-center font-mono text-gray-500 font-bold">
                                    {{ $source->order }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-gray-900 text-sm flex items-center gap-1.5">
                                        <span>{{ $source->name }}</span>
                                        @if ($source->is_system)
                                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-amber-100 text-amber-700 font-semibold" title="Bawaan Sistem">System</span>
                                        @endif
                                    </div>
                                    <div class="font-mono text-blue-600 text-[11px] mt-0.5 flex items-center gap-1">
                                        <span>key:</span>
                                        <code class="bg-blue-50 px-1.5 py-0.2 rounded border border-blue-100">{{ $source->key }}</code>
                                    </div>
                                    @if ($source->description)
                                        <p class="text-gray-500 text-[11px] mt-1 max-w-md line-clamp-2">
                                            {{ $source->description }}
                                        </p>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium capitalize
                                        {{ $source->category === 'academic' ? 'bg-blue-100 text-blue-800' :
                                           ($source->category === 'pesantren' ? 'bg-emerald-100 text-emerald-800' :
                                           ($source->category === 'tahfidz' ? 'bg-amber-100 text-amber-800' :
                                           ($source->category === 'attendance' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800'))) }}">
                                        {{ $source->category_label }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-wrap items-center gap-1 max-w-sm">
                                        @php $fieldList = is_array($source->fields) ? $source->fields : []; @endphp
                                        @forelse (array_slice($fieldList, 0, 5) as $f)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] bg-gray-100 text-gray-700 font-mono border">
                                                {{ is_array($f) ? ($f['field'] ?? $f['name'] ?? 'field') : $f }}
                                            </span>
                                        @empty
                                            <span class="text-gray-400 text-[11px] italic">Belum ada field terdaftar</span>
                                        @endforelse

                                        @if (count($fieldList) > 5)
                                            <span class="text-[10px] text-gray-500 font-semibold bg-gray-50 px-1 rounded">
                                                +{{ count($fieldList)_5 }} lainnya
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <button type="button" @click="toggleStatus({{ $source->id }})"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold transition"
                                        :class="(statusMap[{{ $source->id }}] ?? {{ $source->is_active ? 'true' : 'false' }}) ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'">
                                        <span class="w-1.5 h-1.5 rounded-full"
                                            :class="(statusMap[{{ $source->id }}] ?? {{ $source->is_active ? 'true' : 'false' }}) ? 'bg-green-500' : 'bg-gray-400'"></span>
                                        <span x-text="(statusMap[{{ $source->id }}] ?? {{ $source->is_active ? 'true' : 'false' }}) ? 'Aktif' : 'Nonaktif'"></span>
                                    </button>
                                </td>
                                <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                                    <button type="button" @click="previewSource(@js($source))"
                                        class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded text-xs font-medium transition"
                                        title="Preview Schema & Sampel Data">
                                        👁️ Preview
                                    </button>

                                    <a href="{{ route('admin.report-builder.data-sources.edit', $source->id) }}"
                                        class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-2.5 py-1 rounded text-xs font-medium transition">
                                        ✏️ Edit
                                    </a>

                                    <form action="{{ route('admin.report-builder.data-sources.destroy', $source->id) }}"
                                        method="POST" class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus sumber data {{ $source->name }}? Komponen yang menggunakan dataSource ini mungkin perlu disesuaikan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded text-xs font-medium transition">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-400">
                                    <div class="text-4xl mb-2">📊</div>
                                    <p class="text-sm font-medium">Belum ada Sumber Data yang terdaftar.</p>
                                    <a href="{{ route('admin.report-builder.data-sources.create') }}"
                                        class="mt-3 inline-flex items-center gap-1.5 text-xs text-blue-600 hover:underline font-semibold">
                                        + Tambah Sumber Data Sekarang
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Preview Modal --}}
        <div x-show="showPreviewModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/60 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl space-y-4" @click.away="showPreviewModal = false">
                <div class="flex items-center justify-between border-b pb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">📊</span>
                        <div>
                            <h3 class="font-bold text-gray-900 text-base" x-text="previewData.name"></h3>
                            <code class="text-xs text-blue-600 font-mono" x-text="'key: ' + previewData.key"></code>
                        </div>
                    </div>
                    <button type="button" @click="showPreviewModal = false" class="text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-gray-700 uppercase mb-2">Daftar Field / Kolom Tersedia:</h4>
                    <div class="border rounded-lg overflow-hidden">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-gray-50 border-b text-[10px] font-bold text-gray-500 uppercase">
                                <tr>
                                    <th class="p-2">Field Path</th>
                                    <th class="p-2">Label Header</th>
                                    <th class="p-2">Tipe Data</th>
                                    <th class="p-2">Lebar Bawaan</th>
                                    <th class="p-2 text-center">Rata</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="(f, idx) in (previewData.fields || [])" :key="idx">
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-2 font-mono text-blue-600" x-text="f.field || f"></td>
                                        <td class="p-2 text-gray-800" x-text="f.label || '-'"></td>
                                        <td class="p-2 text-gray-500 capitalize" x-text="f.type || 'string'"></td>
                                        <td class="p-2 text-gray-500 font-mono" x-text="f.default_width || 'auto'"></td>
                                        <td class="p-2 text-center text-gray-500 capitalize" x-text="f.default_align || 'left'"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-gray-700 uppercase mb-1">Contoh Data Sampel (JSON):</h4>
                    <pre class="bg-gray-900 text-green-400 p-3 rounded-lg text-xs font-mono max-h-48 overflow-y-auto" x-text="JSON.stringify(previewData.sample_data, null, 2)"></pre>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="button" @click="showPreviewModal = false"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg text-xs font-semibold transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function dataSourcesIndex() {
            return {
                statusMap: {},
                showPreviewModal: false,
                previewData: {},

                previewSource(source) {
                    this.previewData = source;
                    this.showPreviewModal = true;
                },

                async toggleStatus(id) {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    try {
                        const res = await fetch(`{{ url('admin/report-builder/data-sources') }}/${id}/toggle`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.statusMap[id] = data.is_active;
                        }
                    } catch (e) {
                        alert('Gagal memperbarui status: ' + e.message);
                    }
                }
            };
        }
    </script>

</body>

</html>
