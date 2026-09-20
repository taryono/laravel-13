{{ Form::model($role, ['method' => 'PUT', 'route' => ['role.update', $role->id], 'class' => 'form-horizontal']) }}

{{-- Hapus duplikat breadcrumb & header --}}
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Data Role</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ubah Data</li>
            </ol>
        </nav>
        <h1 class="h3 mb-4">Ubah Data Role</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Kode <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', $role->code) }}" placeholder="CONTOH_KODE" readonly>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Huruf besar, tanpa spasi</small>
    </div>
    <div class="col-md-8 mb-3">
        <label class="form-label">Nama Role <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $role->name) }}" placeholder="Contoh: kas-kecil" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Display Name <span class="text-danger">*</span></label>
        <input type="text" name="display_name" class="form-control @error('display_name') is-invalid @enderror"
            value="{{ old('display_name', $role->display_name) }}" placeholder="Contoh: Kas Kecil" required>
        @error('display_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Description</label>
        <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
            value="{{ old('description', $role->description) }}" placeholder="Deskripsi singkat">
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                @checked(old('is_active', $role->is_active))>
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_system" id="is_system" value="1"
                @checked(old('is_system', $role->is_system))>
            <label class="form-check-label" for="is_system">Role Sistem</label>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-3">
        <label class="form-label">Parent Role</label>
        <select name="parent_id" id="parent_id" class="form-select" @if ($parentOptions->isEmpty()) disabled @endif>
            <option value="">— Tidak Ada Induk —</option>
            @foreach ($parentOptions as $opt)
                <option value="{{ $opt['id'] }}" @selected(old('parent_id', $role->parent_id ?? null) == $opt['id'])>
                    {!! str_repeat('&nbsp;&nbsp;&nbsp;', $opt['level']) !!}{{ $opt['display_name'] }}
                </option>
            @endforeach
        </select>
        @error('parent_id')
            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
        @enderror
        <small class="text-muted mt-1 d-block">Peran sistem tidak boleh memiliki induk</small>
    </div>
</div>
{{ Form::close() }}
