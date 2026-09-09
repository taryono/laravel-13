{{ Form::model($role, ['method' => 'PUT', 'route' => ['role.update', $role->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
<div class="row">
    <div class="col-12">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="">Data ROle</a></li>
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
    {{-- Kode & Nama Akun --}}
    <div class="col-md-4">
        <label class="form-label">Kode Role <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', $account->code ?? '') }}" placeholder="Contoh: 1-1000" required>
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-8">
        <label class="form-label">Nama Role <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $account->name ?? '') }}" placeholder="Contoh: Kas Kecil" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
{{ Form::close() }}