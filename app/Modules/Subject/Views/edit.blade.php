{{ Form::model($subject, ['method' => 'PUT', 'route' => ['subject.update', $subject->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@php
    // Helper: tambahkan is-invalid bila field punya error
    $cls = fn($field, $base = 'form-control') => $base . ($errors->has($field) ? ' is-invalid' : '');
@endphp
{{-- Diaktifkan lewat JS saat mode edit --}}
<input type="hidden" name="_method" value="PUT" id="formMethod" disabled>
{{ Form::hidden('_mode', old('_mode', 'create'), ['id' => 'formMode']) }}
{{ Form::hidden('_id', old('_id'), ['id' => 'formId']) }}

<div class="row">

    <div class="col-md-6">
        {{ Form::label('school_id', 'Sekolah', ['class' => 'form-label']) }}
        {{ Form::select('school_id', $schools, null, ['class' => $cls('school_id', 'form-select'), 'placeholder' => 'Pilih sekolah']) }}
        @error('school_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('organization_id', 'Yayasan', ['class' => 'form-label']) }}
        {{ Form::select('organization_id', $organizations, null, ['class' => $cls('organization_id', 'form-select'), 'placeholder' => 'Pilih Yayasan']) }}
        @error('organization_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        {{ Form::label('code', 'Kode', ['class' => 'form-label']) }}
        {{ Form::text('code', null, ['class' => $cls('code'), 'maxlength' => 20]) }}
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-5">
        {{ Form::label('name', 'Nama', ['class' => 'form-label']) }}
        {{ Form::text('name', null, ['class' => $cls('name'), 'maxlength' => 100]) }}
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        {{ Form::label('abbreviation', 'Singkatan', ['class' => 'form-label']) }}
        {{ Form::text('abbreviation', null, ['class' => $cls('abbreviation'), 'maxlength' => 20]) }}
        @error('abbreviation')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('subject_category_id', 'Kategori', ['class' => 'form-label']) }}
        {{ Form::select('subject_category_id', $categories, null, ['class' => $cls('subject_category_id', 'form-select'), 'placeholder' => 'Pilih kategori']) }}
        @error('subject_category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('parent_id', 'Induk', ['class' => 'form-label']) }}
        {{ Form::select('parent_id', $parents, 0, ['class' => $cls('parent_id', 'form-select')]) }}
        @error('parent_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        {{ Form::label('description', 'Deskripsi', ['class' => 'form-label']) }}
        {{ Form::textarea('description', null, ['class' => $cls('description'), 'rows' => 3]) }}
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            {{ Form::hidden('is_active', 0) }}
            {{ Form::checkbox('is_active', 1, true, ['class' => 'form-check-input', 'id' => 'is_active', 'role' => 'switch']) }}
            {{ Form::label('is_active', 'Aktif', ['class' => 'form-check-label']) }}
        </div>
    </div>
</div>
{{ Form::close() }}
