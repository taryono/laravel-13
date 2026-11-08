<?php
$optionsOrg = App\Models\Organization::active()->pluck('name', 'id');
$optionsSchool = $organization_id ? App\Models\School::where('organization_id', $organization_id)->active()->pluck('name', 'id') : [];
?>
{{ Form::open(['method' => 'POST', 'route' => ['class_level.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
<div class="row">
    {{-- Organisasi --}}
    <div class="col-md-6 mb-3">
        {!! Form::label(
            'organization_id',
            'Organisasi <span class="text-danger">*</span>',
            ['class' => 'form-label'],
            false,
        ) !!}
        {!! Form::select(
            'organization_id',
            $organizations ?? [],
            old('organization_id', $classLevel->organization_id ?? null),
            [
                'class' => 'form-select' . ($errors->has('organization_id') ? ' is-invalid' : ''),
                'required',
            ],
        ) !!}
        @error('organization_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Kode --}}
    <div class="col-md-6 mb-3">
        {!! Form::label('code', 'Kode <span class="text-danger">*</span>', ['class' => 'form-label'], false) !!}
        {!! Form::text('code', old('code', $classLevel->code ?? null), [
            'class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : ''),
            'maxlength' => 20,
            'placeholder' => 'Contoh: X, XI, 1, 2',
            'required',
        ]) !!}
        <small class="text-muted">Kode unik per organisasi</small>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Nama Tingkat --}}
    <div class="col-md-6 mb-3">
        {!! Form::label('name', 'Nama Tingkat <span class="text-danger">*</span>', ['class' => 'form-label'], false) !!}
        {!! Form::text('name', old('name', $classLevel->name ?? null), [
            'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
            'maxlength' => 50,
            'placeholder' => 'Contoh: Kelas 10, Kelas 1',
            'required',
        ]) !!}
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Urutan --}}
    <div class="col-md-6 mb-3">
        {!! Form::label('level_order', 'Urutan <span class="text-danger">*</span>', ['class' => 'form-label'], false) !!}
        {!! Form::number('level_order', old('level_order', $classLevel->level_order ?? null), [
            'class' => 'form-control' . ($errors->has('level_order') ? ' is-invalid' : ''),
            'min' => 1,
            'placeholder' => 'Contoh: 1, 2, 3',
            'required',
        ]) !!}
        @error('level_order')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Keterangan --}}
    <div class="col-12 mb-3">
        {!! Form::label('description', 'Keterangan', ['class' => 'form-label']) !!}
        {!! Form::textarea('description', old('description', $classLevel->description ?? null), [
            'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''),
            'rows' => 3,
            'placeholder' => 'Keterangan tambahan (opsional)',
        ]) !!}
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Status Aktif --}}
    <div class="col-12 mb-3">
        <div class="form-check form-switch">
            {!! Form::checkbox('is_active', '1', old('is_active', $classLevel->is_active ?? true), [
                'class' => 'form-check-input',
                'id' => 'is_active',
            ]) !!}
            {!! Form::label('is_active', 'Aktif', ['class' => 'form-check-label']) !!}
        </div>
    </div>
</div>
{{ Form::close() }}
