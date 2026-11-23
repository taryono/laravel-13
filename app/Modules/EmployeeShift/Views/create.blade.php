{{ Form::open(['method' => $employee_shift->exists ? 'PUT' : 'POST', 'route' => $employee_shift->exists ? ['employee_shift.update', $employee_shift->id] : ['employee_shift.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}

{{-- Pilih Karyawan --}}
<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label fw-bold">Karyawan <span class="text-danger">*</span></label>
    <div class="col-md-9">
        {{ Form::select(
            'employee_id',
            $employees->pluck('full_name', 'id'),
            old('employee_id', $employee_shift->employee_id),
            [
                'class' => 'form-control select2' . ($errors->has('employee_id') ? ' is-invalid' : ''),
                'placeholder' => '-- Pilih Karyawan --',
                'required' => 'required',
            ],
        ) }}
        @error('employee_id')
            <span class="invalid-feedback d-block">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Pilih Shift --}}
<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label fw-bold">Shift <span class="text-danger">*</span></label>
    <div class="col-md-9">
        {{ Form::select('shift_id', $shifts->pluck('name', 'id'), old('shift_id', $employee_shift->shift_id), [
            'class' => 'form-control select2' . ($errors->has('shift_id') ? ' is-invalid' : ''),
            'placeholder' => '-- Pilih Shift --',
            'required' => 'required',
        ]) }}
        @error('shift_id')
            <span class="invalid-feedback d-block">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Tanggal Mulai Berlaku --}}
<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label fw-bold">Tanggal Mulai Berlaku <span class="text-danger">*</span></label>
    <div class="col-md-9">
        {{ Form::date('effective_date', old('effective_date', $employee_shift->effective_date?->format('Y-m-d')), [
            'class' => 'form-control' . ($errors->has('effective_date') ? ' is-invalid' : ''),
            'required' => 'required',
        ]) }}
        @error('effective_date')
            <span class="invalid-feedback d-block">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Tanggal Berakhir --}}
<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label fw-bold">Tanggal Berakhir</label>
    <div class="col-md-9">
        {{ Form::date('end_date', old('end_date', $employee_shift->end_date?->format('Y-m-d')), [
            'class' => 'form-control' . ($errors->has('end_date') ? ' is-invalid' : ''),
            'placeholder' => 'Kosongkan jika tidak ada batas',
        ]) }}
        <small class="text-muted">Kosongkan jika berlaku terus-menerus</small>
        @error('end_date')
            <span class="invalid-feedback d-block">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Hari dalam Minggu --}}
{{-- Hari Shift --}}
<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label fw-bold">Hari Berlaku</label>
    <div class="col-md-9">
        {{ Form::select(
            'day_of_week',
            ['' => 'Berdasarkan Rentang Tanggal'] + $days,
            old('day_of_week', $employee_shift->day_of_week),
            [
                'class' => 'form-control' . ($errors->has('day_of_week') ? ' is-invalid' : ''),
            ],
        ) }}
        <small class="text-muted">
            ⓘ Pilih hari jika jadwal berulang setiap minggu. Jika berlaku Senin–Sabtu,
            <strong>buat 6 data terpisah</strong> dengan memilih hari berbeda di tiap data,
            atau kosongkan & gunakan rentang tanggal saja.
        </small>
        @error('day_of_week')
            <span class="invalid-feedback d-block">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Apakah Shift Bergilir --}}
<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label fw-bold">Sistem Shift Bergilir?</label>
    <div class="col-md-9 pt-2">
        <label class="form-check-label me-3">
            {{ Form::radio('is_rotational', '1', old('is_rotational', $employee_shift->is_rotational) == 1, [
                'class' => 'form-check-input',
                'id' => 'is_rotational_yes',
            ]) }}
            Ya, ikut rotasi bergilir
        </label>
        <label class="form-check-label">
            {{ Form::radio('is_rotational', '0', old('is_rotational', $employee_shift->is_rotational) == 0, [
                'class' => 'form-check-input',
                'id' => 'is_rotational_no',
            ]) }}
            Tidak, jadwal tetap
        </label>
        @error('is_rotational')
            <span class="invalid-feedback d-block">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- ⚡ BAGIAN ROTASI: MUNCUL JIKA PILIH "YA" --}}
<div id="rotasi_fields" class="border-start border-4 border-primary ps-3">

    {{-- Grup Rotasi: PILIH yang ADA atau KETIK yang BARU --}}
    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label fw-bold">Kelompok Rotasi <span class="text-danger">*</span></label>
        <div class="col-md-9">
            {{-- Pilih yang sudah ada --}}
            {{ Form::select(
                'rotation_group_select',
                ['' => '-- Pilih atau Ketik Nama Baru --'] + $existingGroups->toArray(),
                old('rotation_group', $employee_shift->rotation_group),
                [
                    'class' => 'form-control mb-2',
                    'id' => 'rotation_group_select',
                ],
            ) }}
            {{-- Ketik nama baru --}}
            {{ Form::text('rotation_group', old('rotation_group', $employee_shift->rotation_group), [
                'class' => 'form-control',
                'id' => 'rotation_group_text',
                'placeholder' => 'Atau ketik nama kelompok baru (contoh: Grup-A, Tim-Siang)',
            ]) }}
            <small class="text-muted">Pilih kelompok yang sama untuk karyawan yang saling bergilir shift</small>
            @error('rotation_group')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>
    </div>

    {{-- Urutan dalam Grup --}}
    <div class="form-group row mb-3">
        <label class="col-md-3 col-form-label fw-bold">Urutan Ke- <span class="text-danger">*</span></label>
        <div class="col-md-9">
            {{ Form::number('rotation_order', old('rotation_order', $employee_shift->rotation_order), [
                'class' => 'form-control' . ($errors->has('rotation_order') ? ' is-invalid' : ''),
                'min' => '1',
                'placeholder' => 'Misal: 1, 2, 3... (tidak boleh sama dalam satu kelompok)',
            ]) }}
            <small class="text-muted">Urutan kerja dalam kelompok. Tidak boleh sama dengan rekan sekelompok!</small>
            @error('rotation_order')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>
    </div>

</div>

{{-- Catatan --}}
<div class="form-group row mb-3">
    <label class="col-md-3 col-form-label fw-bold">Catatan</label>
    <div class="col-md-9">
        {{ Form::textarea('notes', old('notes', $employee_shift->notes), [
            'class' => 'form-control' . ($errors->has('notes') ? ' is-invalid' : ''),
            'rows' => 3,
            'placeholder' => 'Catatan tambahan (opsional)',
        ]) }}
        @error('notes')
            <span class="invalid-feedback d-block">{{ $message }}</span>
        @enderror
    </div>
</div>
{{ Form::close() }}
