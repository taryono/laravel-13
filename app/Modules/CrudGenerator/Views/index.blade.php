@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">🚀 Generate CRUD</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                            {{ session('success') }}
                            @if(session('output'))
                                <pre class="mt-3 p-2 bg-dark text-light" style="max-height:300px;overflow:auto;">
                                    {{ session('output') }}
                                </pre>
                            @endif
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <h5><i class="icon fas fa-ban"></i> Error!</h5>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('crud-generator.generate') }}" method="POST" id="crudForm">
                        @csrf

                        <div class="form-group">
                            <label for="model_name">Nama Model <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('model_name') is-invalid @enderror" 
                                   id="model_name" 
                                   name="model_name" 
                                   placeholder="Contoh: Product, Category, User"
                                   value="{{ old('model_name') }}"
                                   required>
                            <small class="form-text text-muted">Hanya huruf, tanpa spasi. Akan otomatis diubah ke StudlyCase.</small>
                            @error('model_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="columns">Kolom <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('columns') is-invalid @enderror" 
                                   id="columns" 
                                   name="columns" 
                                   placeholder="Contoh: id,name,email,price,stock"
                                   value="{{ old('columns', 'id,name') }}"
                                   required>
                            <small class="form-text text-muted">
                                Pisahkan dengan koma. Kolom 'id' secara default akan ditampilkan.
                                Kolom berakhiran '_id' otomatis jadi foreign key.
                            </small>
                            @error('columns')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="icon">Icon Menu</label>
                            <select class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon">
                                <option value="list_alt">📋 Default</option>
                                @foreach($icons as $value => $label)
                                    <option value="{{ $value }}" {{ old('icon') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Ikon untuk menu di sidebar (Material Icons).</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="create_menu" 
                                       name="create_menu" 
                                       value="1"
                                       {{ old('create_menu', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="create_menu">
                                    Buat Menu untuk Super Admin
                                </label>
                            </div>
                            <small class="form-text text-muted">Otomatis mendaftarkan menu di sidebar untuk role super-admin.</small>
                        </div>

                        <div class="form-group" id="parentMenuGroup" style="display:none;">
                            <label for="parent_menu">Parent Menu</label>
                            <select class="form-control" id="parent_menu" name="parent_menu">
                                <option value="">Tanpa Parent (Root)</option>
                                @foreach($existingMenus ?? [] as $menu)
                                    <option value="{{ $menu->slug }}">{{ $menu->title }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Jadikan sub-menu dari menu yang dipilih.</small>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-play"></i> Generate CRUD
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" 
                                        formaction="{{ route('crud-generator.generateAsync') }}" 
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
            <div class="card">
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
                                            <span class="badge badge-{{ $item['status'] == 'success' ? 'success' : 'danger' }}">
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

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">⚡ Quick Examples</h3>
                </div>
                <div class="card-body">
                    <button class="btn btn-sm btn-outline-primary btn-block mb-2" onclick="fillExample('Product', 'id,name,price,stock,description')">
                        📦 Product
                    </button>
                    <button class="btn btn-sm btn-outline-primary btn-block mb-2" onclick="fillExample('Category', 'id,name,slug,description')">
                        📂 Category
                    </button>
                    <button class="btn btn-sm btn-outline-primary btn-block mb-2" onclick="fillExample('User', 'id,name,email,role_id,is_active')">
                        👤 User
                    </button>
                    <button class="btn btn-sm btn-outline-primary btn-block" onclick="fillExample('Order', 'id,user_id,total,status,payment_id')">
                        🛒 Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function fillExample(model, columns) {
    document.getElementById('model_name').value = model;
    document.getElementById('columns').value = columns;
    document.getElementById('crudForm').scrollIntoView({ behavior: 'smooth' });
}

// Toggle parent menu
document.getElementById('create_menu').addEventListener('change', function() {
    document.getElementById('parentMenuGroup').style.display = this.checked ? 'block' : 'none';
});

// Preview command
document.getElementById('crudForm').addEventListener('submit', function(e) {
    const model = document.getElementById('model_name').value;
    const columns = document.getElementById('columns').value;
    const createMenu = document.getElementById('create_menu').checked;
    
    let command = `php artisan make:crud-yajra ${model} --columns=${columns}`;
    if (!createMenu) command += ' --no-menu';
    
    if (!confirm(`Execute command:\n\n${command}\n\nLanjutkan?`)) {
        e.preventDefault();
    }
});
</script> 
@endpush
@endsection