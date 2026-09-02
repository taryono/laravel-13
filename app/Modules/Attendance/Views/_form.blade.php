{{-- app/Modules/Attendance/Views/_form.blade.php --}}
<div class="row">
    <!-- Employee -->
    <div class="col-md-6 mb-3">
        <label for="employee_id" class="form-label">Karyawan <span class="text-danger">*</span></label>
        <select name="employee_id" id="employee_id" class="form-select @error('employee_id') is-invalid @enderror"
            required>
            <option value="">Pilih Karyawan</option>
            @foreach ($employees as $id => $name)
                <option value="{{ $id }}"
                    {{ old('employee_id', $attendance->employee_id ?? '') == $id ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>
        @error('employee_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Period -->
    <div class="col-md-6 mb-3">
        <label for="payroll_period_id" class="form-label">Periode Penggajian <span class="text-danger">*</span></label>
        <select name="payroll_period_id" id="payroll_period_id"
            class="form-select @error('payroll_period_id') is-invalid @enderror" required>
            <option value="">Pilih Periode</option>
            @foreach ($periods as $id => $code)
                <option value="{{ $id }}"
                    {{ old('payroll_period_id', $attendance->payroll_period_id ?? '') == $id ? 'selected' : '' }}>
                    {{ $code }}
                </option>
            @endforeach
        </select>
        @error('payroll_period_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <!-- Attendance Date -->
    <div class="col-md-4 mb-3">
        <label for="attendance_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
        <input type="date" name="attendance_date" id="attendance_date"
            class="form-control @error('attendance_date') is-invalid @enderror"
            value="{{ old('attendance_date', isset($attendance) && $attendance->attendance_date ? $attendance->attendance_date : '') }}"
            required>
        @error('attendance_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Status -->
    <div class="col-md-4 mb-3">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="hadir" {{ old('status', $attendance->status ?? '') == 'hadir' ? 'selected' : '' }}>Hadir
            </option>
            <option value="sakit" {{ old('status', $attendance->status ?? '') == 'sakit' ? 'selected' : '' }}>Sakit
            </option>
            <option value="izin" {{ old('status', $attendance->status ?? '') == 'izin' ? 'selected' : '' }}>Izin
            </option>
            <option value="alpha" {{ old('status', $attendance->status ?? '') == 'alpha' ? 'selected' : '' }}>Alpha
            </option>
            <option value="cuti" {{ old('status', $attendance->status ?? '') == 'cuti' ? 'selected' : '' }}>Cuti
            </option>
            <option value="dinas_luar"
                {{ old('status', $attendance->status ?? '') == 'dinas_luar' ? 'selected' : '' }}>Dinas Luar</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Jam Lembur Manual -->
    <div class="col-md-4 mb-3">
        <label for="overtime_hours" class="form-label">Jam Lembur</label>
        <input type="number" name="overtime_hours" id="overtime_hours"
            class="form-control @error('overtime_hours') is-invalid @enderror"
            value="{{ old('overtime_hours', optional($attendance->attendanceDaily ?? null)->overtime_hours ?? '') }}"
            step="0.5" min="0" placeholder="0.00">
        @error('overtime_hours')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- ✅ INPUT JAM MASUK & PULANG — BISA LINTAS HARI --}}
<div class="row">
    <!-- Jam Masuk -->
    <div class="col-md-5 mb-3">
        <label for="actual_check_in" class="form-label">Jam Masuk (Tanggal & Jam)</label>
        <input type="datetime-local" name="actual_check_in" id="actual_check_in"
            class="form-control @error('actual_check_in') is-invalid @enderror"
            value="{{ old('actual_check_in', optional($attendance->attendanceDaily ?? null)->actual_check_in?->format('Y-m-d\TH:i') ?? '') }}">
        <small class="text-muted">Format: Tanggal & Jam — aman untuk shift malam</small>
        @error('actual_check_in')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Jam Pulang -->
    <div class="col-md-5 mb-3">
        <label for="actual_check_out" class="form-label">Jam Pulang (Tanggal & Jam)</label>
        <input type="datetime-local" name="actual_check_out" id="actual_check_out"
            class="form-control @error('actual_check_out') is-invalid @enderror"
            value="{{ old('actual_check_out', optional($attendance->attendanceDaily ?? null)->actual_check_out?->format('Y-m-d\TH:i') ?? '') }}">
        <small class="text-muted">Bisa lewat tengah malam</small>
        @error('actual_check_out')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Tombol Hitung Otomatis -->
    <div class="col-md-2 mb-3">
        <label class="form-label">&nbsp;</label>
        <button type="button" class="btn btn-outline-secondary w-100" onclick="calculateOvertime()">
            <i class="bi bi-calculator"></i> Hitung
        </button>
    </div>
</div>

{{-- ✅ Info Data ASLI dari Mesin (jika ada) — untuk perbandingan saat Adjustment --}}
@if (isset($attendance) && $attendance->attendanceDaily)
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card border-info">
                <div class="card-header bg-info text-white py-2">
                    <strong>📋 Data Asli dari Mesin</strong>
                    <span class="badge bg-warning text-dark ms-2">Sebelum Penyesuaian</span>
                </div>
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Jam Masuk:</strong>
                            {{ $attendance->attendanceDaily->first_scan?->format('d/m/Y H:i') ?? '-' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Jam Pulang:</strong>
                            {{ $attendance->attendanceDaily->last_scan?->format('d/m/Y H:i') ?? '-' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Status Mesin:</strong>
                            {{ ucfirst($attendance->attendanceDaily->attendance_status ?? '-') }}
                        </div>
                        <div class="col-md-3">
                            <strong>Telat/Pulang Cepat:</strong>
                            @if ($attendance->attendanceDaily->late_minutes > 0)
                                <span class="text-danger">Telat {{ $attendance->attendanceDaily->late_minutes }}
                                    menit</span>
                            @elseif($attendance->attendanceDaily->early_leave_minutes > 0)
                                <span class="text-warning">Pulang Cepat
                                    {{ $attendance->attendanceDaily->early_leave_minutes }} menit</span>
                            @else
                                <span class="text-success">Tepat Waktu</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Catatan / Alasan Penyesuaian -->
<div class="row">
    <div class="col-12 mb-3">
        <label for="notes" class="form-label">Catatan / Alasan Penyesuaian</label>
        <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror"
            placeholder="Contoh: Lupa scan pulang, izin terlambat, penyesuaian jam kerja...">{{ old('notes', $attendance->notes ?? '') }}</textarea>
        @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
