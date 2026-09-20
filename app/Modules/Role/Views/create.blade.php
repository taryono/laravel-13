{{ Form::open(['method' => 'POST', 'route' => ['role.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
<div class="row">
    <div class="col-12">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Data ROle</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Data</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Tambah Data Role</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Kode <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', $role->code ?? '') }}" placeholder="CONTOH_KODE"
            {{ isset($role) && $role->code ? 'readonly' : '' }}>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Huruf besar, tanpa spasi</small>
    </div>
    <div class="col-md-8">
        <label class="form-label">Nama Role <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $account->name ?? '') }}" placeholder="Contoh: Kas Kecil" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <label class="form-label">Display Name <span class="text-danger">*</span></label>
        <input type="text" name="display_name" class="form-control @error('display_name') is-invalid @enderror"
            value="{{ old('display_name', $account->display_name ?? '') }}" placeholder="Contoh: Kas Kecil" required>
        @error('display_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Description</label>
        <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
            value="{{ old('description', $account->description ?? '') }}" placeholder="Contoh: Kas Kecil">
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active">
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_system" id="is_system">
            <label class="form-check-label" for="is_system">Role Sistem </label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>Parent Role</label>
            <select name="parent_id" id="parent_id" class="form-select @error('parent_id') is-invalid @enderror"
                @if ($parentOptions->isEmpty()) disabled @endif>
                <option value="">— Tidak Ada Induk —</option>
                @foreach ($parentOptions as $opt)
                    <option value="{{ $opt['id'] }}" @selected(old('parent_id', $role->parent_id ?? null) == $opt['id'])>
                        {!! str_repeat('&nbsp;&nbsp;&nbsp;', $opt['level']) !!}{{ $opt['display_name'] }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{ Form::close() }}
