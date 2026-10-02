<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Komponen Palette - Report Builder</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="componentsIndex()">

        {{-- Top Breadcrumb / Nav --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.report-builder.index') }}"
                    class="text-sm font-medium text-gray-500 hover:text-gray-700 flex items-center gap-1 transition">
                    ← Kembali ke Daftar Template
                </a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('admin.report-builder.builder') }}" target="_blank"
                    class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1 transition">
                    🎨 Buka Builder ↗
                </a>
            </div>
        </div>

        {{-- Header Title & Action --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <span>🧩</span> Kelola Komponen Palette
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    Atur komponen yang muncul di panel kiri builder, termasuk konfigurasi konten bawaan (*default content*).
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.report-builder.components.create') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition">
                    <span class="text-base font-bold">+</span> Tambah Komponen Baru
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

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                    📦
                </div>
                <div>
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Komponen</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                    ✅
                </div>
                <div>
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Aktif di Palette</div>
                    <div class="text-2xl font-bold text-emerald-600">{{ $stats['active'] }}</div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                    ⏸️
                </div>
                <div>
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Disembunyikan</div>
                    <div class="text-2xl font-bold text-amber-600">{{ $stats['inactive'] }}</div>
                </div>
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 p-2 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-1">
                <a href="{{ route('admin.report-builder.components.index') }}"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition {{ empty($category) || $category === 'all' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    Semua Kategori ({{ $stats['total'] }})
                </a>
                @foreach ($categories as $cat)
                    <a href="{{ route('admin.report-builder.components.index', ['category' => $cat]) }}"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-medium capitalize transition {{ $category === $cat ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>

            <div class="text-xs text-gray-500 px-2">
                Gunakan tombol toggle pada kolom status untuk menampilkan/menyembunyikan komponen langsung dari palette.
            </div>
        </div>

        {{-- Table Components --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                            <th class="py-3.5 px-4 text-center w-16">Urutan</th>
                            <th class="py-3.5 px-4">Komponen</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4">Tipe Section</th>
                            <th class="py-3.5 px-4">Deskripsi</th>
                            <th class="py-3.5 px-4 text-center">Status Palette</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($components as $comp)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="py-3.5 px-4 text-center font-mono text-gray-500 text-xs">
                                    #{{ $comp->order }}
                                </td>

                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-9 h-9 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center text-lg flex-shrink-0">
                                            {{ $comp->icon ?: '📄' }}
                                        </span>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $comp->name }}</div>
                                            <div class="text-[11px] text-gray-400 font-mono">{{ $comp->type }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $comp->category === 'blocks' ? 'bg-blue-100 text-blue-800' : ($comp->category === 'text' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                                        {{ ucfirst($comp->category) }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 font-mono text-xs text-gray-600">
                                    <code class="bg-gray-100 px-1.5 py-0.5 rounded">{{ $comp->type }}</code>
                                </td>

                                <td class="py-3.5 px-4 text-xs text-gray-500 max-w-xs truncate" title="{{ $comp->description }}">
                                    {{ $comp->description ?: '—' }}
                                </td>

                                <td class="py-3.5 px-4 text-center">
                                    <button type="button" @click="toggleStatus({{ $comp->id }})"
                                        :class="statuses[{{ $comp->id }}] ? 'bg-emerald-500' : 'bg-gray-300'"
                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <span class="sr-only">Toggle Active</span>
                                        <span :class="statuses[{{ $comp->id }}] ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                    </button>
                                </td>

                                <td class="py-3.5 px-4 text-right space-x-1.5 whitespace-nowrap">
                                    <a href="{{ route('admin.report-builder.components.edit', $comp->id) }}"
                                        class="inline-flex items-center gap-1 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-2.5 py-1.5 rounded font-medium transition">
                                        ✏️ Edit
                                    </a>

                                    <form action="{{ route('admin.report-builder.components.destroy', $comp->id) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Hapus komponen &quot;{{ $comp->name }}&quot;?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 text-xs bg-red-50 hover:bg-red-100 text-red-600 px-2.5 py-1.5 rounded font-medium transition">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-gray-500">
                                    <div class="text-3xl mb-2">🧩</div>
                                    <div class="font-medium text-gray-700">Belum ada komponen di kategori ini</div>
                                    <p class="text-xs text-gray-400 mt-1">Klik tombol "Tambah Komponen Baru" di atas untuk menambahkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($components->hasPages())
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    {{ $components->links() }}
                </div>
            @endif
        </div>

    </div>

    <script>
        function componentsIndex() {
            return {
                statuses: {
                    @foreach($components as $c)
                        {{ $c->id }}: {{ $c->is_active ? 'true' : 'false' }},
                    @endforeach
                },
                async toggleStatus(id) {
                    try {
                        const token = document.querySelector('meta[name=csrf-token]').content;
                        const res = await fetch(`{{ url('admin/report-builder/components') }}/${id}/toggle`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                            }
                        });
                        const json = await res.json();
                        if (json.success) {
                            this.statuses[id] = json.is_active;
                        }
                    } catch (err) {
                        alert('Gagal memperbarui status: ' + err.message);
                    }
                }
            }
        }
    </script>
</body>

</html>
