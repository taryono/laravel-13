{{-- resources/views/extracurricular/form.blade.php --}}
@php
    $daysList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    $selected = old('days', $extracurricular?->days->toArray() ?? []);
    $existing = collect($selected)->keyBy('day_of_week');
@endphp

{{-- Sekolah --}}
<div class="row mb-3">
    <label class="col-sm-3 col-form-label fw-semibold">Sekolah <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        {{ Form::select('school_id', $schools ?? [], old('school_id', $extracurricular?->school_id), [
            'class' => 'form-select' . ($errors->has('school_id') ? ' is-invalid' : ''),
            'required' => 'required',
            'placeholder' => '-- Pilih Sekolah --',
        ]) }}
        @error('school_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Nama --}}
<div class="row mb-3">
    <label class="col-sm-3 col-form-label fw-semibold">Nama Kegiatan <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        {{ Form::text('name', old('name', $extracurricular?->name), [
            'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
            'maxlength' => 100,
            'required' => 'required',
        ]) }}
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Kode --}}
<div class="row mb-3">
    <label class="col-sm-3 col-form-label fw-semibold">Kode <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        {{ Form::text('code', old('code', $extracurricular?->code), [
            'class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : ''),
            'maxlength' => 20,
            'required' => 'required',
        ]) }}
        <small class="text-muted">Unik per sekolah</small>
        @error('code')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Guru Pembina --}}
<div class="row mb-3">
    <label class="col-sm-3 col-form-label fw-semibold">Guru Pembina</label>
    <div class="col-sm-9">
        {{ Form::select('teacher_id', $teachers ?? [], old('teacher_id', $extracurricular?->teacher_id), [
            'class' => 'form-select',
            'placeholder' => '-- Pilih Guru --',
        ]) }}
    </div>
</div>

{{-- Kapasitas --}}
<div class="row mb-3">
    <label class="col-sm-3 col-form-label fw-semibold">Kapasitas Siswa</label>
    <div class="col-sm-9">
        {{ Form::number('max_students', old('max_students', $extracurricular?->max_students ?? 30), [
            'class' => 'form-control',
            'min' => 1,
        ]) }}
    </div>
</div>

{{-- Hari & Waktu --}}
<div class="row mb-3">
    <label class="col-sm-3 col-form-label fw-semibold">Jadwal <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        @foreach ($daysList as $hari)
            <div class="card mb-2 border">
                <div class="card-body p-2">
                    <div class="form-check mb-2">
                        {{-- Bagian Checkbox --}}
                        {{ Form::checkbox("days[{$hari}][selected]", '1', $existing->has($hari), [
                            'class' => 'form-check-input day-toggle', // ⬅️ class HARUS sama: day-toggle
                            'id' => 'day-' . $hari,
                            'data-target' => 'card-' . $hari, // ⬅️ Contoh: card-Senin
                        ]) }}
                        <label class="form-check-label fw-medium"
                            for="day-{{ $hari }}">{{ $hari }}</label>
                    </div>
                    <div class="row ps-4 {{ $existing->has($hari) ? '' : 'd-none' }}" id="card-{{ $hari }}">
                        <input type="hidden" name="days[{{ $hari }}][day_of_week]"
                            value="{{ $hari }}">
                        <div class="col-md-3 mb-2">
                            <label class="form-label small">Jam Mulai</label>
                            <input type="time" name="days[{{ $hari }}][start_time]"
                                class="form-control form-control-sm"
                                value="{{ old("days.{$hari}.start_time", optional($existing->get($hari))->start_time?->format('H:i')) }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label small">Jam Selesai</label>
                            <input type="time" name="days[{{ $hari }}][end_time]"
                                class="form-control form-control-sm"
                                value="{{ old("days.{$hari}.end_time", optional($existing->get($hari))->end_time?->format('H:i')) }}">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Lokasi</label>
                            <input type="text" name="days[{{ $hari }}][location]"
                                class="form-control form-control-sm"
                                value="{{ old("days.{$hari}.location", optional($existing->get($hari))->location) }}"
                                placeholder="Ruangan / Tempat">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        @error('days')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Keterangan --}}
<div class="row mb-3">
    <label class="col-sm-3 col-form-label fw-semibold">Keterangan</label>
    <div class="col-sm-9">
        {{ Form::textarea('description', old('description', $extracurricular?->description), [
            'class' => 'form-control',
            'rows' => 3,
        ]) }}
    </div>
</div>

{{-- Status --}}
<div class="row mb-3">
    <div class="col-sm-9 offset-sm-3">
        <div class="form-check form-switch">
            {{ Form::checkbox('is_active', '1', old('is_active', $extracurricular?->is_active ?? true), [
                'class' => 'form-check-input',
                'id' => 'is_active',
            ]) }}
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
    </div>
</div>
