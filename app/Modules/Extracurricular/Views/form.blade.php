{{-- resources/views/extracurricular/form.blade.php --}}
@php
    $daysList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    $selected = old('days', $extracurricular?->days->toArray() ?? []);
    $existing = collect($selected)->keyBy('day_of_week');
@endphp

{{-- Sekolah --}}
<div class="row mb-3">
    <label for="school_id" class="col-sm-3 col-form-label fw-semibold">Sekolah <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        {{ Form::select('school_id', $schools ?? [], old('school_id', $extracurricular?->school_id), [
            'class' => 'form-select' . ($errors->has('school_id') ? ' is-invalid' : ''),
            'required' => 'required',
            'placeholder' => '-- Pilih Sekolah --',
            'id' => 'school_id',
        ]) }}
        @if ($errors->has('school_id'))
            <div class="invalid-feedback d-block">{{ $errors->first('school_id') }}</div>
        @endif
    </div>
</div>

{{-- Nama Kegiatan --}}
<div class="row mb-3">
    <label for="name" class="col-sm-3 col-form-label fw-semibold">Nama Kegiatan <span
            class="text-danger">*</span></label>
    <div class="col-sm-9">
        {{ Form::text('name', old('name', $extracurricular?->name), [
            'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
            'id' => 'name',
            'maxlength' => 100,
            'required' => 'required',
            'placeholder' => 'Contoh: Pramuka, Paduan Suara',
        ]) }}
        @if ($errors->has('name'))
            <div class="invalid-feedback d-block">{{ $errors->first('name') }}</div>
        @endif
    </div>
</div>

{{-- Kode --}}
<div class="row mb-3">
    <label for="code" class="col-sm-3 col-form-label fw-semibold">Kode <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        {{ Form::text('code', old('code', $extracurricular?->code), [
            'class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : ''),
            'id' => 'code',
            'maxlength' => 20,
            'required' => 'required',
            'placeholder' => 'Contoh: PRAMUKA, OLAHRAGA',
        ]) }}
        <small class="text-muted">Kode unik per sekolah</small>
        @if ($errors->has('code'))
            <div class="invalid-feedback d-block">{{ $errors->first('code') }}</div>
        @endif
    </div>
</div>

{{-- Guru Pembina --}}
<div class="row mb-3">
    <label for="teacher_id" class="col-sm-3 col-form-label fw-semibold">Guru Pembina</label>
    <div class="col-sm-9">
        {{ Form::select('teacher_id', $teachers ?? [], old('teacher_id', $extracurricular?->teacher_id), [
            'class' => 'form-select' . ($errors->has('teacher_id') ? ' is-invalid' : ''),
            'placeholder' => '-- Pilih Guru --',
            'id' => 'teacher_id',
        ]) }}
        @if ($errors->has('teacher_id'))
            <div class="invalid-feedback d-block">{{ $errors->first('teacher_id') }}</div>
        @endif
    </div>
</div>

{{-- Hari --}}
{{-- Hari — Pilih Banyak --}}
<div class="row mb-3">
    <label class="col-sm-3 col-form-label fw-semibold">Hari <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <div class="row">
            @php
                $days = [
                    'Senin' => 'Senin',
                    'Selasa' => 'Selasa',
                    'Rabu' => 'Rabu',
                    'Kamis' => 'Kamis',
                    'Jumat' => 'Jumat',
                    'Sabtu' => 'Sabtu',
                    'Minggu' => 'Minggu',
                ];
                // Ambil hari yang sudah dipilih (untuk edit)
                $selectedDays = old(
                    'day_of_week',
                    is_object($extracurricular ?? null)
                        ? $extracurricular->days->pluck('day_of_week')->toArray() ?? []
                        : [],
                );
            @endphp
            @foreach ($days as $val => $label)
                <div class="col-4 col-md-3">
                    <div class="form-check">
                        {{ Form::checkbox('day_of_week[]', $val, in_array($val, $selectedDays), [
                            'class' => 'form-check-input',
                            'id' => 'day_' . $val,
                        ]) }}
                        <label class="form-check-label" for="day_{{ $val }}">
                            {{ $label }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
        @if ($errors->has('day_of_week'))
            <div class="text-danger small mt-1">{{ $errors->first('day_of_week') }}</div>
        @endif
    </div>
</div>

{{-- Jam Mulai & Selesai --}}
<div class="row mb-3">
    <label class="col-sm-3 col-form-label fw-semibold">Waktu</label>
    <div class="col-sm-4">
        <div class="mb-0">
            {{ Form::label('start_time', 'Mulai', ['class' => 'form-label small text-muted']) }}
            {{ Form::time('start_time', old('start_time', $extracurricular?->start_time?->format('H:i')), [
                'class' => 'form-control' . ($errors->has('start_time') ? ' is-invalid' : ''),
                'id' => 'start_time',
            ]) }}
            @if ($errors->has('start_time'))
                <div class="invalid-feedback d-block">{{ $errors->first('start_time') }}</div>
            @endif
        </div>
    </div>
    <div class="col-sm-5">
        <div class="mb-0">
            {{ Form::label('end_time', 'Selesai', ['class' => 'form-label small text-muted']) }}
            {{ Form::time('end_time', old('end_time', $extracurricular?->end_time?->format('H:i')), [
                'class' => 'form-control' . ($errors->has('end_time') ? ' is-invalid' : ''),
                'id' => 'end_time',
            ]) }}
            @if ($errors->has('end_time'))
                <div class="invalid-feedback d-block">{{ $errors->first('end_time') }}</div>
            @endif
        </div>
    </div>
</div>

{{-- Kapasitas Siswa --}}
<div class="row mb-3">
    <label for="max_students" class="col-sm-3 col-form-label fw-semibold">Kapasitas Siswa</label>
    <div class="col-sm-9">
        {{ Form::number('max_students', old('max_students', $extracurricular?->max_students ?? 30), [
            'class' => 'form-control' . ($errors->has('max_students') ? ' is-invalid' : ''),
            'id' => 'max_students',
            'min' => 1,
            'max' => 999,
        ]) }}
        <small class="text-muted">Jumlah maksimal peserta, bawaan: 30</small>
        @if ($errors->has('max_students'))
            <div class="invalid-feedback d-block">{{ $errors->first('max_students') }}</div>
        @endif
    </div>
</div>

{{-- Keterangan --}}
<div class="row mb-3">
    <label for="description" class="col-sm-3 col-form-label fw-semibold">Keterangan</label>
    <div class="col-sm-9">
        {{ Form::textarea('description', old('description', $extracurricular?->description), [
            'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''),
            'id' => 'description',
            'rows' => 3,
            'placeholder' => 'Deskripsi kegiatan, tujuan, persyaratan, dll.',
        ]) }}
        @if ($errors->has('description'))
            <div class="invalid-feedback d-block">{{ $errors->first('description') }}</div>
        @endif
    </div>
</div>

{{-- Status Aktif --}}
<div class="row mb-3">
    <div class="col-sm-9 offset-sm-3">
        <div class="form-check form-switch">
            {{ Form::checkbox('is_active', '1', old('is_active', $extracurricular?->is_active ?? true), [
                'class' => 'form-check-input',
                'id' => 'is_active',
                'role' => 'switch',
            ]) }}
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
    </div>
</div>
