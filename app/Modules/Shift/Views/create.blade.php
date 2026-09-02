{{ Form::open(['method' => 'POST', 'route' => ['shift.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
<div class="row g-3">
    <!-- Baris 1: Kode & Nama -->
    <div class="col-md-4">
        <label class="form-label">Kode Shift <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', $shift->code) }}" placeholder="Cth: S1, Pagi" required>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-8">
        <label class="form-label">Nama Shift <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $shift->name) }}" placeholder="Cth: Shift Pagi" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Baris 2: Waktu Mulai & Selesai -->
    <div class="col-md-6">
        <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
        <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror"
            value="{{ old('start_time', $shift->start_time) }}" required>
        @error('start_time')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
        <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror"
            value="{{ old('end_time', $shift->end_time) }}" required>
        @error('end_time')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <!-- Helper Text Dinamis -->
        <div class="form-text text-muted">
            <i class="bi bi-info-circle"></i>
            Jika jam selesai <b>lebih kecil</b> dari jam mulai (misal: 22:00 s/d 06:00),
            sistem akan otomatis mendeteksi ini sebagai shift yang melewati tengah malam.
        </div>
    </div>

    <!-- Baris 3: Toleransi (Menit) -->
    <div class="col-md-6">
        <label class="form-label">Toleransi Telat (Menit)</label>
        <input type="number" name="grace_period_start" class="form-control"
            value="{{ old('grace_period_start', $shift->grace_period_start ?? 15) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Toleransi Pulang Cepat (Menit)</label>
        <input type="number" name="grace_period_end" class="form-control"
            value="{{ old('grace_period_end', $shift->grace_period_end ?? 15) }}">
    </div>

    <!-- Baris 4: Istirahat -->
    <div class="col-md-4">
        <label class="form-label">Mulai Istirahat</label>
        <input type="time" name="break_start" class="form-control"
            value="{{ old('break_start', $shift->break_start) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Selesai Istirahat</label>
        <input type="time" name="break_end" class="form-control" value="{{ old('break_end', $shift->break_end) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Durasi Istirahat (Menit)</label>
        <input type="number" name="break_duration" class="form-control"
            value="{{ old('break_duration', $shift->break_duration ?? 0) }}">
    </div>

    <!-- Baris 5: Lembur & Shift Malam -->
    <div class="col-md-4">
        <label class="form-label">Minimal Lembur (Menit)</label>
        <input type="number" name="overtime_threshold" class="form-control"
            value="{{ old('overtime_threshold', $shift->overtime_threshold ?? 60) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Rate Lembur (x)</label>
        <input type="number" step="0.01" name="overtime_rate" class="form-control"
            value="{{ old('overtime_rate', $shift->overtime_rate ?? 1.5) }}">
    </div>
    <div class="col-md-4 d-flex align-items-end">

    </div>

    <!-- Baris 6: Detail Shift Malam & Status -->
    <div class="col-md-6">
        <label class="form-label">Jam Mulai Perhitungan Malam</label>
        <input type="time" name="night_shift_start" class="form-control"
            value="{{ old('night_shift_start', $shift->night_shift_start) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="is_active" class="form-select">
            <option value="1" {{ old('is_active', $shift->is_active ?? 1) == 1 ? 'selected' : '' }}>Aktif
            </option>
            <option value="0" {{ old('is_active', $shift->is_active ?? 1) == 0 ? 'selected' : '' }}>Non-Aktif
            </option>
        </select>
    </div>
</div>
{{ Form::close() }}
