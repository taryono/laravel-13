<div class="row g-3">

    {{-- ============================================================= --}}
    {{-- SECTION: KONTEKS AKADEMIK --}}
    {{-- ============================================================= --}}
    <div class="col-12">
        <h6 class="text-uppercase text-muted fw-bold mb-2">
            <i class="fa fa-graduation-cap me-1"></i> Konteks Akademik
        </h6>
        <hr class="mt-0">
    </div>

    <div class="col-md-6">
        {{ Form::label('academic_year_id', 'Tahun Ajaran <span class="text-danger">*</span>', ['class' => 'form-label'], false) }}
        {{ Form::select('academic_year_id', $academic_years ?? [], old('academic_year_id'), [
            'class' => 'form-select select2' . ($errors->has('academic_year_id') ? ' is-invalid' : ''),
            'placeholder' => '-- Pilih Tahun Ajaran --',
            'required' => true,
        ]) }}
        @error('academic_year_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('class_level_id', 'Tingkat Kelas <span class="text-danger">*</span>', ['class' => 'form-label'], false) }}
        {{ Form::select('class_level_id', $class_levels ?? [], old('class_level_id'), [
            'class' => 'form-select select2' . ($errors->has('class_level_id') ? ' is-invalid' : ''),
            'placeholder' => '-- Pilih Tingkat Kelas --',
            'required' => true,
        ]) }}
        @error('class_level_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- ============================================================= --}}
    {{-- SECTION: KRITERIA KELULUSAN --}}
    {{-- ============================================================= --}}
    <div class="col-12 mt-3">
        <h6 class="text-uppercase text-muted fw-bold mb-2">
            <i class="fa fa-clipboard-check me-1"></i> Kriteria Kenaikan Kelas
        </h6>
        <hr class="mt-0">
    </div>

    <div class="col-md-6">
        {{ Form::label('min_final_score', 'Nilai Akhir Minimum <span class="text-danger">*</span>', ['class' => 'form-label'], false) }}
        <div class="input-group">
            {{ Form::number('min_final_score', old('min_final_score', 70), [
                'class' => 'form-control text-end' . ($errors->has('min_final_score') ? ' is-invalid' : ''),
                'min' => 0,
                'max' => 100,
                'step' => '0.01',
                'required' => true,
            ]) }}
            <span class="input-group-text">/ 100</span>
            @error('min_final_score')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        {{ Form::label('min_attendance_rate', 'Kehadiran Minimum (%) <span class="text-danger">*</span>', ['class' => 'form-label'], false) }}
        <div class="input-group">
            {{ Form::number('min_attendance_rate', old('min_attendance_rate', 75), [
                'class' => 'form-control text-end' . ($errors->has('min_attendance_rate') ? ' is-invalid' : ''),
                'min' => 0,
                'max' => 100,
                'step' => '0.01',
                'required' => true,
            ]) }}
            <span class="input-group-text">%</span>
            @error('min_attendance_rate')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        {{ Form::label('min_behavior_score', 'Nilai Sikap Minimum', ['class' => 'form-label']) }}
        <div class="input-group">
            {{ Form::number('min_behavior_score', old('min_behavior_score', 70), [
                'class' => 'form-control text-end' . ($errors->has('min_behavior_score') ? ' is-invalid' : ''),
                'min' => 0,
                'max' => 100,
                'step' => '0.01',
            ]) }}
            <span class="input-group-text">/ 100</span>
            @error('min_behavior_score')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <small class="text-muted">Kosongkan jika tidak ada kriteria sikap.</small>
    </div>

    <div class="col-md-6">
        {{ Form::label('max_failed_subjects', 'Maks. Jumlah Mapel Gagal', ['class' => 'form-label']) }}
        {{ Form::number('max_failed_subjects', old('max_failed_subjects', 0), [
            'class' => 'form-control' . ($errors->has('max_failed_subjects') ? ' is-invalid' : ''),
            'min' => 0,
            'max' => 20,
        ]) }}
        @error('max_failed_subjects')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- ============================================================= --}}
    {{-- SECTION: OPSI PROMOSI --}}
    {{-- ============================================================= --}}
    <div class="col-12 mt-3">
        <h6 class="text-uppercase text-muted fw-bold mb-2">
            <i class="fa fa-cogs me-1"></i> Opsi Promosi
        </h6>
        <hr class="mt-0">
    </div>

    <div class="col-md-6">
        <div class="form-check form-switch mt-4">
            {{ Form::hidden('auto_promote', 0) }}
            {{ Form::checkbox('auto_promote', 1, old('auto_promote', false), [
                'class' => 'form-check-input',
                'id' => 'auto_promote',
            ]) }}
            {{ Form::label('auto_promote', 'Auto Promote', ['class' => 'form-check-label']) }}
            <small class="d-block text-muted">Sistem otomatis menaikkan kelas siswa yang memenuhi kriteria.</small>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-check form-switch mt-4">
            {{ Form::hidden('allow_manual_override', 0) }}
            {{ Form::checkbox('allow_manual_override', 1, old('allow_manual_override', true), [
                'class' => 'form-check-input',
                'id' => 'allow_manual_override',
            ]) }}
            {{ Form::label('allow_manual_override', 'Izinkan Override Manual', ['class' => 'form-check-label']) }}
            <small class="d-block text-muted">Admin/guru bisa mengubah keputusan sistem secara manual.</small>
        </div>
    </div>

    {{-- ============================================================= --}}
    {{-- SECTION: CATATAN & STATUS --}}
    {{-- ============================================================= --}}
    <div class="col-12 mt-3">
        <h6 class="text-uppercase text-muted fw-bold mb-2">
            <i class="fa fa-info-circle me-1"></i> Catatan & Status
        </h6>
        <hr class="mt-0">
    </div>

    <div class="col-12">
        {{ Form::label('notes', 'Catatan', ['class' => 'form-label']) }}
        {{ Form::textarea('notes', old('notes'), [
            'class' => 'form-control' . ($errors->has('notes') ? ' is-invalid' : ''),
            'rows' => 3,
            'placeholder' => 'Catatan tambahan terkait aturan kenaikan kelas ini...',
        ]) }}
        @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            {{ Form::hidden('is_active', 0) }}
            {{ Form::checkbox('is_active', 1, old('is_active', true), [
                'class' => 'form-check-input',
                'id' => 'is_active',
            ]) }}
            {{ Form::label('is_active', 'Aktifkan Aturan Ini', ['class' => 'form-check-label']) }}
        </div>
    </div>

</div>
