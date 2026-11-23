{{ Form::open(['method' => 'POST', 'route' => ['country.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">Kode ISO <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        {{ Form::text('iso', null, ['class' => 'form-control', 'maxlength' => 2, 'required']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">Nama Negara <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        {{ Form::text('name', null, ['class' => 'form-control', 'maxlength' => 80, 'required']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        {{ Form::text('printable_name', null, ['class' => 'form-control', 'maxlength' => 80, 'required']) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">Kode ISO3</label>
    <div class="col-sm-9">
        {{ Form::text('iso3', null, ['class' => 'form-control', 'maxlength' => 3]) }}
    </div>
</div>

<div class="row mb-3">
    <label class="col-sm-3 col-form-label">Kode Numerik</label>
    <div class="col-sm-9">
        {{ Form::number('numcode', null, ['class' => 'form-control']) }}
    </div>
</div>

{{ Form::close() }}
