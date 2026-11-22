{{ Form::model($subdistrict, ['method' => 'PUT', 'route' => ['subdistrict.update', $subdistrict->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}

<div class="row mb-3">
    <label class="col-sm-4 col-form-label">Kode</label>
    <div class="col-sm-8">
        {{ Form::text('code', null, ['class' => 'form-control', 'maxlength' => 10]) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-4 col-form-label">Nama Kecamatan</label>
    <div class="col-sm-8">
        {{ Form::text('name', null, ['class' => 'form-control', 'maxlength' => 45]) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-4 col-form-label">Distrik / Kabupaten</label>
    <div class="col-sm-8">
        {{ Form::select('district_id', $districts ?? [], null, ['class' => 'form-select', 'placeholder' => '-- Pilih Distrik --']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-4 col-form-label">Kode Pos</label>
    <div class="col-sm-8">
        {{ Form::text('zip', null, ['class' => 'form-control', 'maxlength' => 5]) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-4 col-form-label">Latitude</label>
    <div class="col-sm-8">
        {{ Form::text('latitude', null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-4 col-form-label">Longitude</label>
    <div class="col-sm-8">
        {{ Form::text('longitude', null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-4 col-form-label">Elevasi</label>
    <div class="col-sm-8">
        {{ Form::text('elevation', null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-4 col-form-label">Zona Waktu</label>
    <div class="col-sm-8">
        {{ Form::text('timezone', null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-4 col-form-label">Urutan</label>
    <div class="col-sm-8">
        {{ Form::number('ordering', null, ['class' => 'form-control']) }}
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
