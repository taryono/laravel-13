@extends('layouts.app')

@section('title', 'Generate CRUD')
@push('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    <!-- Di bagian HEAD atau awal content -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <link href="{{ asset('assets/css/crud_generator.css') }}" rel="stylesheet">
@endpush
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card modal-custom">
                    <div class="card-header">
                        <h3 class="card-title">🚀 Generate CRUD</h3>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                                {{ session('success') }}
                                @if (session('output'))
                                    <pre class="mt-3 p-2 bg-dark text-light" style="max-height:300px;overflow:auto;">
                                                                                                                                                                                                                                                                                                {{ session('output') }}
                                                                                                                                                                                                                                                                                            </pre>
                                @endif
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- ===== FORM GENERATE CRUD ===== -->
                        <form action="{{ route('crud_generator.generate') }}" method="POST" id="crudForm">
                            @csrf

                            <div class="form-group">
                                <label for="model_name">Nama Model <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('model_name') is-invalid @enderror"
                                    id="model_name" name="model_name" placeholder="Contoh: Product, Category, User"
                                    value="{{ old('model_name') }}" required>
                                <small class="form-text text-muted">Hanya huruf, tanpa spasi. Akan otomatis diubah ke
                                    StudlyCase.</small>
                                @error('model_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="columns">Kolom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('columns') is-invalid @enderror" id="columns"
                                    name="columns" placeholder="Contoh: id,name,email,price,stock"
                                    value="{{ old('columns', 'id,name') }}" required>
                                <small class="form-text text-muted">
                                    Pisahkan dengan koma. Kolom 'id' secara default akan ditampilkan.
                                    Kolom berakhiran '_id' otomatis jadi foreign key.
                                </small>
                                @error('columns')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group" id="parentMenuGroup">
                                <label for="parent_menu">Parent Menu</label>
                                <select class="form-control" name="parent_menu">
                                    <option value="">Tanpa Parent (Root)</option>
                                    @foreach ($parentOptions ?? [] as $menu)
                                        <option value="{{ $menu['id'] }}">{{ $menu['label'] }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Jadikan sub-menu dari menu yang dipilih.</small>
                            </div>

                            <!-- ===== ICON PICKER CRUD ===== -->
                            <x-icon-picker id="crud-icon-picker" name="icon" typeName="icon_type" value="fas fa-th-large"
                                typeValue="fa" label="Icon Menu" :required="true" />

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="create_menu" name="create_menu"
                                        value="1" {{ old('create_menu', true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="create_menu">
                                        Buat Menu untuk Super Admin
                                    </label>
                                </div>
                                <small class="form-text text-muted">Otomatis mendaftarkan menu di sidebar untuk role
                                    super-admin.</small>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-play"></i> Generate CRUD
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" formaction="{{ route('crud_generator.generateAsync') }}"
                                        class="btn btn-info btn-block">
                                        <i class="fas fa-clock"></i> Generate Background
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="mt-3">
                            <div class="alert alert-info">
                                <h5><i class="icon fas fa-info-circle"></i> Tips:</h5>
                                <ul class="mb-0">
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

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">📊 History Generate</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Model</th>
                                        <th>Status</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($history ?? [] as $item)
                                        <tr>
                                            <td>{{ $item['model'] }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $item['status'] == 'success' ? 'success' : 'danger' }}">
                                                    {{ $item['status'] }}
                                                </span>
                                            </td>
                                            <td>{{ $item['created_at']->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">
                                                Belum ada history
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">⚡ Module Ready</h3>
                    </div>
                    <div class="card-body">
                        <!-- Input Pencarian -->
                        <div class="mb-3">
                            <input type="text" class="form-control" id="search_module"
                                placeholder="Cari nama tabel / modul...">
                        </div>

                        @if (!empty($tables))
                            {{-- Beri wrapper pada daftar tombol agar mudah difilter --}}
                            <div id="table-list">
                                @foreach ($tables as $table)
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-block mb-2 table-example"
                                        data-table="{{ $table['name'] }}" data-label="{{ $table['label'] }}"
                                        data-columns="{{ implode(',', $table['columns']) }}">
                                        📋 {{ $table['label'] }}
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                <h5><i class="icon fas fa-info-circle"></i> Belum ada table yang siap</h5>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">⚡ Menu Ready</h3>
                    </div>
                    <div class="card-body">
                        <!-- Input Pencarian -->
                        <div class="mb-3">
                            <input type="text" class="form-control" id="search_menu"
                                placeholder="Cari nama tabel / modul...">
                        </div>
                        <div id="menu-list">
                            @if (!empty($filtered))
                                @foreach ($filtered as $module)
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-block mb-2 menu-example"
                                        data-table="{{ $module }}" data-label="{{ \Str::headline($module) }}"
                                        data-flyout_title="{{ $module }}" data-slug="{{ \Str::kebab($module) }}"
                                        data-route="{{ \Str::snake($module) }}.index" data-url="{{ \Str::kebab($module) }}">
                                        📋 {{ $module }}
                                    </button>
                                @endforeach
                            @else
                                <div class="alert alert-info">
                                    <h5><i class="icon fas fa-info-circle"></i> Belum ada menu yang siap</h5>
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
            $('#search_menu').on('keyup', function () {
                let value = $(this).val().toLowerCase();
                let $list = $('#menu-list').find('.menu-example');

                $list.each(function () {
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