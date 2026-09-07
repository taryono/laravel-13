{{ Form::open(['method' => $method ?? 'POST', 'route' => $action ?? 'shift.store', 'class' => 'form-horizontal', 'id' => 'formShift']) }}
@csrf
@if(($shift->id ?? 0) > 0) @method('PUT') @endif

<div class="row g-3">
    {{-- ===== BAGIAN 1: DATA SHIFT ===== --}}
    <div class="col-12">
        <h5 class="border-bottom pb-2 mb-3">Informasi Shift</h5>
    </div>

    {{-- Kode & Nama Shift --}}
    <div class="col-md-4">
        <label class="form-label">Kode Shift <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', $shift->code) }}" placeholder="Cth: S1, S2, S3" required>
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8">
        <label class="form-label">Nama Shift <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $shift->name) }}" placeholder="Cth: Shift Pagi" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Waktu Mulai & Selesai --}}
    <div class="col-md-6">
        <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
        <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror"
            value="{{ old('start_time', $shift->start_time) }}" required>
        @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
        <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror"
            value="{{ old('end_time', $shift->end_time) }}" required>
        @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="form-text text-muted">
            <i class="bi bi-info-circle"></i> Jika selesai lebih kecil dari mulai, sistem otomatis tandai melewati tengah malam.
        </div>
    </div>

    {{-- Toleransi --}}
    <div class="col-md-6">
        <label class="form-label">Toleransi Telat (Menit)</label>
        <input type="number" name="grace_period_start" class="form-control" min="0"
            value="{{ old('grace_period_start', $shift->grace_period_start ?? 15) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Toleransi Pulang Cepat (Menit)</label>
        <input type="number" name="grace_period_end" class="form-control" min="0"
            value="{{ old('grace_period_end', $shift->grace_period_end ?? 15) }}">
    </div>

    {{-- Istirahat --}}
    <div class="col-md-4">
        <label class="form-label">Mulai Istirahat</label>
        <input type="time" name="break_start" class="form-control"
            value="{{ old('break_start', $shift->break_start) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Selesai Istirahat</label>
        <input type="time" name="break_end" class="form-control"
            value="{{ old('break_end', $shift->break_end) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Durasi Istirahat (Menit)</label>
        <input type="number" name="break_duration" class="form-control" min="0"
            value="{{ old('break_duration', $shift->break_duration ?? 0) }}" readonly>
        <small class="text-muted">Otomatis dihitung</small>
    </div>

    {{-- Lembur & Malam --}}
    <div class="col-md-4">
        <label class="form-label">Minimal Lembur (Menit)</label>
        <input type="number" name="overtime_min_threshold" class="form-control" min="0"
            value="{{ old('overtime_min_threshold', $shift->overtime_min_threshold ?? 60) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Jam Mulai Perhitungan Malam</label>
        <input type="time" name="night_shift_start" class="form-control"
            value="{{ old('night_shift_start', $shift->night_shift_start) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="is_active" class="form-select">
            <option value="1" {{ old('is_active', $shift->is_active ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ old('is_active', $shift->is_active ?? 1) == 0 ? 'selected' : '' }}>Non-Aktif</option>
        </select>
    </div>

    {{-- ===== BAGIAN 2: TARIF PER JENIS HARI ===== --}}
    <div class="col-12 mt-4">
        <h5 class="border-bottom pb-2 mb-3">Tarif & Tunjangan per Jenis Hari</h5>
        <p class="text-muted small">Isi tarif untuk setiap jenis hari. Biarkan 0 jika tidak berlaku.</p>
    </div>

    @php
        $dayTypes = [
            'weekday'           => 'Hari Kerja Biasa',
            'saturday'          => 'Hari Sabtu',
            'sunday'            => 'Hari Minggu',
            'national_holiday'  => 'Hari Libur Nasional',
            'special_holiday'   => 'Hari Libur Khusus',
        ];
        // Siapkan data lama jika edit
        $oldRates = old('rates', $shift->shiftRates->pluck('toArray', 'day_type')->toArray() ?? []);
    @endphp

    @foreach($dayTypes as $key => $label)
    <div class="col-12">
        <div class="card border mb-3">
            <div class="card-header py-2 bg-light">
                <strong>{{ $label }}</strong>
                <input type="hidden" name="rates[{{ $key }}][day_type]" value="{{ $key }}">
            </div>
            <div class="card-body py-2">
                <div class="row g-2">
                    <div class="col-md-2">
                        <label class="form-label small">Upah/Shift (Rp)</label>
                        <input type="number" step="0.01" name="rates[{{ $key }}][base_rate_per_shift]" class="form-control form-control-sm"
                            value="{{ $oldRates[$key]['base_rate_per_shift'] ?? '0.00' }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Pengali Lembur</label>
                        <input type="number" step="0.01" name="rates[{{ $key }}][overtime_multiplier]" class="form-control form-control-sm"
                            value="{{ $oldRates[$key]['overtime_multiplier'] ?? '1.50' }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Lembur/Jam (Rp)</label>
                        <input type="number" step="0.01" name="rates[{{ $key }}][overtime_rate_per_hour]" class="form-control form-control-sm"
                            value="{{ $oldRates[$key]['overtime_rate_per_hour'] ?? '0.00' }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Tunjangan Malam (Rp)</label>
                        <input type="number" step="0.01" name="rates[{{ $key }}][night_allowance_per_shift]" class="form-control form-control-sm"
                            value="{{ $oldRates[$key]['night_allowance_per_shift'] ?? '0.00' }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Uang Makan (Rp)</label>
                        <input type="number" step="0.01" name="rates[{{ $key }}][meal_allowance_per_shift]" class="form-control form-control-sm"
                            value="{{ $oldRates[$key]['meal_allowance_per_shift'] ?? '0.00' }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Uang Transport (Rp)</label>
                        <input type="number" step="0.01" name="rates[{{ $key }}][transport_allowance_per_shift]" class="form-control form-control-sm"
                            value="{{ $oldRates[$key]['transport_allowance_per_shift'] ?? '0.00' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="col-12 text-end mt-4">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-save"></i> {{ ($shift->id ?? 0) > 0 ? 'Perbarui' : 'Simpan' }}
        </button>
    </div>
</div>
{{ Form::close() }}

{{-- Auto-hitung durasi istirahat --}}
@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const breakStart = document.querySelector('input[name="break_start"]');
    const breakEnd = document.querySelector('input[name="break_end"]');
    const breakDur = document.querySelector('input[name="break_duration"]');

    function calcBreak() {
        if (breakStart.value && breakEnd.value) {
            let [h1, m1] = breakStart.value.split(':').map(Number);
            let [h2, m2] = breakEnd.value.split(':').map(Number);
            let min1 = h1 * 60 + m1, min2 = h2 * 60 + m2;
            if (min2 < min1) min2 += 24 * 60;
            breakDur.value = min2 - min1;
        } else {
            breakDur.value = 0;
        }
    }
    breakStart.addEventListener('change', calcBreak);
    breakEnd.addEventListener('change', calcBreak);
});
</script>
@endpush