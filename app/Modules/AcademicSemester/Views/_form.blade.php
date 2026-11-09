@php
    $model = $academic_semester ?? null;
    $selectedOrg = old('school_id', $model->school_id ?? null);
    $selectedSchool = old('school_id', $model->school_id ?? null);
    $selectedYear = old('academic_year_id', $model->academic_year_id ?? null);
    $code = old('code', $model->code ?? null);
    $name = old('name', $model->name ?? null);
    $type = old('type', $model->type ?? 'ganjil');
    $startDate = old(
        'start_date',
        isset($model->start_date) ? \Carbon\Carbon::parse($model->start_date)->format('Y-m-d') : null,
    );
    $endDate = old(
        'end_date',
        isset($model->end_date) ? \Carbon\Carbon::parse($model->end_date)->format('Y-m-d') : null,
    );
    $krsStart = old(
        'krs_start_date',
        isset($model->krs_start_date) ? \Carbon\Carbon::parse($model->krs_start_date)->format('Y-m-d') : null,
    );
    $krsEnd = old(
        'krs_end_date',
        isset($model->krs_end_date) ? \Carbon\Carbon::parse($model->krs_end_date)->format('Y-m-d') : null,
    );
    $isActive = old('is_active', $model->is_active ?? true);
    $isCurrent = old('is_current', $model->is_current ?? false);
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('school_id', 'Unit *', ['class' => 'control-label']) }}
            {{ Form::select('school_id', $schools ?? [], $selectedSchool, [
                'class' => 'form-control select2' . ($errors->has('school_id') ? ' is-invalid' : ''),
                'placeholder' => '-- Pilih Unit --',
                'required' => true,
            ]) }}
            @error('school_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('academic_year_id', 'Tahun Ajaran *', ['class' => 'control-label']) }}
            {{ Form::select('academic_year_id', $academic_years ?? [], $selectedYear, [
                'class' => 'form-control select2' . ($errors->has('academic_year_id') ? ' is-invalid' : ''),
                'placeholder' => '-- Pilih Tahun Ajaran --',
                'required' => true,
            ]) }}
            @error('academic_year_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('code', 'Kode Semester *', ['class' => 'control-label']) }}
            {{ Form::text('code', $code, [
                'class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : ''),
                'placeholder' => 'Contoh: GANJIL-2024',
                'maxlength' => 30,
                'required' => true,
            ]) }}
            @error('code')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('name', 'Nama Semester *', ['class' => 'control-label']) }}
            {{ Form::text('name', $name, [
                'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                'placeholder' => 'Contoh: Semester Ganjil 2024/2025',
                'maxlength' => 100,
                'required' => true,
            ]) }}
            @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>



    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('start_date', 'Tanggal Mulai *', ['class' => 'control-label']) }}
            {{ Form::date('start_date', $startDate, [
                'class' => 'form-control' . ($errors->has('start_date') ? ' is-invalid' : ''),
                'required' => true,
            ]) }}
            @error('start_date')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('end_date', 'Tanggal Selesai *', ['class' => 'control-label']) }}
            {{ Form::date('end_date', $endDate, [
                'class' => 'form-control' . ($errors->has('end_date') ? ' is-invalid' : ''),
                'required' => true,
            ]) }}
            @error('end_date')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('krs_start_date', 'Tanggal Mulai KRS', ['class' => 'control-label']) }}
            {{ Form::date('krs_start_date', $krsStart, [
                'class' => 'form-control' . ($errors->has('krs_start_date') ? ' is-invalid' : ''),
            ]) }}
            @error('krs_start_date')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('krs_end_date', 'Tanggal Selesai KRS', ['class' => 'control-label']) }}
            {{ Form::date('krs_end_date', $krsEnd, [
                'class' => 'form-control' . ($errors->has('krs_end_date') ? ' is-invalid' : ''),
            ]) }}
            @error('krs_end_date')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('is_active', 'Status Aktif', ['class' => 'control-label d-block']) }}
            <div class="custom-control custom-switch">
                {{ Form::hidden('is_active', 0) }}
                {{ Form::checkbox('is_active', 1, $isActive, [
                    'class' => 'custom-control-input',
                    'id' => 'is_active',
                ]) }}
                {{ Form::label('is_active', 'Aktifkan semester', ['class' => 'custom-control-label']) }}
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('is_current', 'Semester Berjalan', ['class' => 'control-label d-block']) }}
            <div class="custom-control custom-switch">
                {{ Form::hidden('is_current', 0) }}
                {{ Form::checkbox('is_current', 1, $isCurrent, [
                    'class' => 'custom-control-input',
                    'id' => 'is_current',
                ]) }}
                {{ Form::label('is_current', 'Tandai sebagai semester berjalan', ['class' => 'custom-control-label']) }}
            </div>
            <small class="text-muted">Hanya boleh 1 semester berjalan per Unit.</small>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('type', 'Tipe *', ['class' => 'control-label']) }}
            {{ Form::select(
                'type',
                [
                    'ganjil' => 'Ganjil',
                    'genap' => 'Genap',
                    'pendek' => 'Pendek',
                ],
                $type,
                [
                    'class' => 'form-control' . ($errors->has('type') ? ' is-invalid' : ''),
                    'required' => true,
                ],
            ) }}
            @error('type')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
