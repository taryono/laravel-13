@extends('layouts.app')

@section('title', 'Generate CRUD')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <link href="{{ asset('assets/css/crud_generator.css') }}" rel="stylesheet">
    <style>
        /* Dark Mode Optimization for Crud Generator */
        body.dark-mode .modal-custom.card,
        [data-bs-theme="dark"] .modal-custom.card {
            background-color: var(--bs-card-bg, #1e222b);
            border-color: var(--bs-card-border-color, rgba(255, 255, 255, 0.1));
        }

        body.dark-mode .card-title,
        [data-bs-theme="dark"] .card-title {
            color: var(--bs-heading-color, #f8fafc);
        }

        body.dark-mode .form-label,
        [data-bs-theme="dark"] .form-label {
            color: var(--bs-body-color, #e2e8f0);
            font-weight: 500;
        }

        body.dark-mode .table,
        [data-bs-theme="dark"] .table {
            --bs-table-bg: transparent;
            --bs-table-striped-bg: rgba(255, 255, 255, 0.025);
            --bs-table-hover-bg: rgba(255, 255, 255, 0.05);
            color: var(--bs-body-color, #cbd5e1);
        }
    </style>
@endpush
@section('content')

    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card modal-custom shadow-sm">
                    <div class="card-header border-bottom">
                        <h3 class="card-title h5 mb-0">🚀 Generate CRUD</h3>
                    </div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <h5 class="alert-heading d-flex align-items-center mb-1">
                                    <i class="icon fas fa-check-circle me-2"></i> Sukses!
                                </h5>
                                <div>{{ session('success') }}</div>
                                @if (session('output'))
                                    <pre class="mt-3 p-3 bg-dark text-light rounded border border-secondary" style="max-height:300px;overflow:auto;">{{ session('output') }}</pre>
                                @endif
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h5 class="alert-heading d-flex align-items-center mb-1">
                                    <i class="icon fas fa-ban me-2"></i> Error!
                                </h5>
                                <div>{{ session('error') }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- ===== FORM GENERATE CRUD ===== -->
                        <form action="{{ route('crud_generator.generate') }}" method="POST" id="crudForm">
                            @csrf

                            <div class="mb-3">
                                <label for="model_name" class="form-label">
                                    Nama Model <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('model_name') is-invalid @enderror"
                                    id="model_name" name="model_name" placeholder="Contoh: Product, Category, User"
                                    value="{{ old('model_name') }}" required>
                                <div class="form-text">
                                    Hanya huruf, tanpa spasi. Akan otomatis diubah ke StudlyCase.
                                </div>
                                @error('model_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="columns" class="form-label">
                                    Kolom <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('columns') is-invalid @enderror"
                                    id="columns" name="columns" placeholder="Contoh: id,name,email,price,stock"
                                    value="{{ old('columns', 'id,name') }}" required>
                                <div class="form-text">
                                    Pisahkan dengan koma. Kolom 'id' secara default akan ditampilkan.
                                    Kolom berakhiran '_id' otomatis jadi foreign key.
                                </div>
                                @error('columns')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="parentMenuGroup">
                                <label for="parent_menu" class="form-label">Parent Menu</label>
                                <select class="form-select" name="parent_menu" id="parent_menu">
                                    <option value="">Tanpa Parent (Root)</option>
                                    @foreach ($parentOptions ?? [] as $menu)
                                        <option value="{{ $menu['id'] }}">{{ $menu['label'] }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Jadikan sub-menu dari menu yang dipilih.</div>
                            </div>

                            <!-- ===== ICON PICKER CRUD ===== -->
                            <x-icon-picker id="crud-icon-picker" name="icon" typeName="icon_type" value="fas fa-th-large"
                                typeValue="fa" label="Icon Menu" :required="true" />

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="create_menu" name="create_menu"
                                        value="1" {{ old('create_menu', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="create_menu">
                                        Buat Menu untuk Super Admin
                                    </label>
                                </div>
                                <div class="form-text">
                                    Otomatis mendaftarkan menu di sidebar untuk role super-admin.
                                </div>
                            </div>

                            <div class="row g-2 mt-4">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary w-100 px-2">
                                        <i class="fas fa-play me-1"></i> Generate CRUD
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" formaction="{{ route('crud_generator.generateAsync') }}"
                                        class="btn btn-info w-100 px-2 text-white">
                                        <i class="fas fa-clock me-1"></i> Generate Background
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="mt-4">
                            <div class="alert alert-info border-0 shadow-sm mb-0">
                                <h5 class="alert-heading h6 d-flex align-items-center mb-2">
                                    <i class="icon fas fa-info-circle me-2"></i> Tips Penggunaan:
                                </h5>
                                <ul class="mb-0 ps-3 small">
                                    <li>Gunakan nama model <strong>singular</strong> (Product, bukan Products)</li>
                                    <li>Kolom <strong>id</strong> akan otomatis ditambahkan</li>
                                    <li>Kolom <strong>created_at, updated_at</strong> otomatis dari migration</li>
                                    <li>Proses akan <strong>clear cache</strong> otomatis setelah selesai</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Card History Generate --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom">
                        <h3 class="card-title h6 mb-0">📊 History Generate</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Model</th>
                                        <th>Status</th>
                                        <th class="pe-3">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($history ?? [] as $item)
                                        <tr>
                                            <td class="ps-3 fw-semibold">{{ $item['model'] }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $item['status'] == 'success' ? 'success' : 'danger' }}">
                                                    {{ $item['status'] }}
                                                </span>
                                            </td>
                                            <td class="pe-3 text-secondary small">
                                                {{ $item['created_at']->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">
                                                Belum ada history
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Card Module Ready --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-bottom">
                        <h3 class="card-title h6 mb-0">⚡ Module Ready</h3>
                    </div>
                    <div class="card-body">
                        <!-- Input Pencarian -->
                        <div class="mb-3">
                            <input type="text" class="form-control form-control-sm" id="search_module"
                                placeholder="Cari nama tabel / modul...">
                        </div>

                        @if (!empty($tables))
                            <div id="table-list">
                                @foreach ($tables as $table)
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary w-100 mb-2 text-start table-example"
                                        data-table="{{ $table['name'] }}" data-label="{{ $table['label'] }}"
                                        data-columns="{{ implode(',', $table['columns']) }}">
                                        📋 {{ $table['label'] }}
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info py-2 px-3 small mb-0">
                                <i class="fas fa-info-circle me-1"></i> Belum ada table yang siap
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Card Menu Ready --}}
                <div class="card shadow-sm">
                    <div class="card-header border-bottom">
                        <h3 class="card-title h6 mb-0">⚡ Menu Ready</h3>
                    </div>
                    <div class="card-body">
                        <!-- Input Pencarian -->
                        <div class="mb-3">
                            <input type="text" class="form-control form-control-sm" id="search_menu"
                                placeholder="Cari nama tabel / modul...">
                        </div>
                        <div id="menu-list">
                            @if (!empty($filtered))
                                @foreach ($filtered as $module)
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary w-100 mb-2 text-start menu-example"
                                        data-table="{{ $module }}" data-label="{{ \Str::headline($module) }}"
                                        data-flyout_title="{{ $module }}" data-slug="{{ \Str::kebab($module) }}"
                                        data-route="{{ \Str::snake($module) }}.index"
                                        data-url="{{ \Str::kebab($module) }}">
                                        📋 {{ $module }}
                                    </button>
                                @endforeach
                            @else
                                <div class="alert alert-info py-2 px-3 small mb-0">
                                    <i class="fas fa-info-circle me-1"></i> Belum ada menu yang siap
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('CrudGenerator::modal-menu')

    <!-- ===== ICON PICKER MODAL ===== -->
    <x-icon-picker-modal id="icon-picker-modal" />

    @push('scripts')
        <script src="{{ asset('assets/js/crud_generator.js') }}?v={{ time() }}"></script>
        <script>
            // Filter untuk Menu Ready
            $('#search_menu').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                let $list = $('#menu-list').find('.menu-example');

                $list.each(function() {
                    let text = $(this).text().toLowerCase();
                    if (text.includes(value)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Filter untuk Module Ready
            $('#search_module').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                let $list = $('#table-list').find('.table-example');

                $list.each(function() {
                    let text = $(this).text().toLowerCase();
                    if (text.includes(value)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        </script>
    @endpush
@endsection
