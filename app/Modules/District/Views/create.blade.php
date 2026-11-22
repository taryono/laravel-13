{{ Form::open(['method' => 'POST', 'route' => ['district.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}

<div class="row mb-3">
    <label class="col-sm-4 col-form-label">Kode</label>
    <div class="col-sm-8">
        {{ Form::text('code', null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-4 col-form-label">Nama Distrik/Kecamatan</label>
    <div class="col-sm-8">
        {{ Form::text('name', null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-4 col-form-label">Kota / Kabupaten</label>
    <div class="col-sm-8">
        {{ Form::select('city_id', $cities ?? [], null, ['class' => 'form-select', 'placeholder' => '-- Pilih Kota --']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-4 col-form-label">ID Terakhir</label>
    <div class="col-sm-8">
        {{ Form::number('last_id', null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-4 col-form-label">Teks Bebas</label>
    <div class="col-sm-8">
        {{ Form::select('is_free_text', ['0' => 'Tidak', '1' => 'Ya'], null, ['class' => 'form-select']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-4 col-form-label">Input Pengguna</label>
    <div class="col-sm-8">
        {{ Form::select('is_user_input', ['0' => 'Tidak', '1' => 'Ya'], null, ['class' => 'form-select']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-4 col-form-label">Waktu Verifikasi</label>
    <div class="col-sm-8">
        {{ Form::datetime('verified_at', null, ['class' => 'form-control']) }}
    </div>
</div>

{{ Form::close() }}
