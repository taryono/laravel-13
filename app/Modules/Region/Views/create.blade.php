{{ Form::open(['method' => 'POST', 'route' => ['region.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">Kode</label>
    <div class="col-sm-9">
        {{ Form::text('code', null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">Nama</label>
    <div class="col-sm-9">
        {{ Form::text('name', null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">Negara</label>
    <div class="col-sm-9">
        {{ Form::select('country_id', $countries, null, ['class' => 'form-select', 'placeholder' => '-- Pilih Negara --']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">Kode Pos</label>
    <div class="col-sm-9">
        {{ Form::text('postal_code', null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">Tipe</label>
    <div class="col-sm-9">
        {{ Form::text('type', null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">Teks Bebas</label>
    <div class="col-sm-9">
        {{ Form::select('is_free_text', ['0' => 'Tidak', '1' => 'Ya'], null, ['class' => 'form-select', 'placeholder' => '-- Pilih --']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">Input User</label>
    <div class="col-sm-9">
        {{ Form::select('is_user_input', ['0' => 'Tidak', '1' => 'Ya'], null, ['class' => 'form-select', 'placeholder' => '-- Pilih --']) }}
    </div>
</div>
{{ Form::close() }}
