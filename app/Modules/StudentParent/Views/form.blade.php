@php
    $isEdit = isset($student_parent) && $student_parent->exists;
    $relationships = [
        'FATHER' => 'Ayah',
        'MOTHER' => 'Ibu',
        'GUARDIAN' => 'Wali',
    ];
@endphp

<div class="row g-3">
    {{-- Siswa --}}
    <div class="col-md-6">
        {{ Form::label('student_id', 'Siswa', ['class' => 'form-label']) }} <span class="text-danger">*</span>
        {{ Form::select(
            'student_id',
            $students,
            null,
            array_filter([
                'class' => 'form-select' . ($errors->has('student_id') ? ' is-invalid' : ''),
                'placeholder' => '-- Pilih Siswa --',
                'required' => true,
                'disabled' => $isEdit ? 'disabled' : null,
            ]),
        ) }}
        @error('student_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Orang Tua --}}
    <div class="col-md-6">
        {{ Form::label('parent_id', 'Orang Tua / Wali', ['class' => 'form-label']) }} <span class="text-danger">*</span>
        {{ Form::select(
            'parent_id',
            $parents,
            null,
            array_filter([
                'class' => 'form-select' . ($errors->has('parent_id') ? ' is-invalid' : ''),
                'placeholder' => '-- Pilih Orang Tua --',
                'required' => true,
                'disabled' => $isEdit ? 'disabled' : null,
            ]),
        ) }}
        @error('parent_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @if ($isEdit)
        <div class="col-12">
            <div class="form-text mt-0">Siswa dan orang tua tidak dapat diubah. Hapus relasi ini lalu buat yang baru bila
                salah pilih.</div>
        </div>
    @endif

    {{-- Hubungan --}}
    <div class="col-md-6">
        {{ Form::label('relationship', 'Hubungan', ['class' => 'form-label']) }} <span class="text-danger">*</span>
        {{ Form::select('relationship', $relationships, null, [
            'class' => 'form-select' . ($errors->has('relationship') ? ' is-invalid' : ''),
            'placeholder' => '-- Pilih Hubungan --',
            'required',
        ]) }}
        @error('relationship')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Kontak utama --}}
    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
            {{ Form::checkbox('is_primary', 1, null, ['class' => 'form-check-input', 'id' => 'is_primary', 'role' => 'switch']) }}
            {{ Form::label('is_primary', 'Jadikan kontak utama', ['class' => 'form-check-label']) }}
        </div>
    </div>
</div>

<hr class="my-4">
