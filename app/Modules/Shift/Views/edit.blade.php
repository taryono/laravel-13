 
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ isset($shift->id) ? 'Edit Shift' : 'Tambah Shift Baru' }}</h5>
        </div>
        <div class="card-body">
            {{ Form::model($shift, ['method' => 'PUT', 'route' => ['shift.update', $shift->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
                @csrf
                @if(isset($shift->id))
                    @method('PUT')
                @endif

                <div class="row">
                    <!-- Kode & Nama -->
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">Kode Shift <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code', $shift->code ?? '') }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama Shift <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $shift->name ?? '') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jam Mulai & Selesai -->
                    <div class="col-md-6 mb-3">
                        <label for="start_time" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                               id="start_time" name="start_time" value="{{ old('start_time', $shift->start_time ?? '') }}" required>
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_time" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                               id="end_time" name="end_time" value="{{ old('end_time', $shift->end_time ?? '') }}" required>
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Jika melewati tengah malam (misal 22:00 - 06:00), sistem akan otomatis mendeteksi.</small>
                    </div>

                    <!-- Toleransi -->
                    <div class="col-md-6 mb-3">
                        <label for="grace_period_start" class="form-label">Toleransi Telat (menit)</label>
                        <input type="number" class="form-control @error('grace_period_start') is-invalid @enderror" 
                               id="grace_period_start" name="grace_period_start" 
                               value="{{ old('grace_period_start', $shift->grace_period_start ?? 15) }}" min="0" max="120">
                        @error('grace_period_start')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="grace_period_end" class="form-label">Toleransi Pulang Cepat (menit)</label>
                        <input type="number" class="form-control @error('grace_period_end') is-invalid @enderror" 
                               id="grace_period_end" name="grace_period_end" 
                               value="{{ old('grace_period_end', $shift->grace_period_end ?? 15) }}" min="0" max="120">
                        @error('grace_period_end')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Istirahat -->
                    <div class="col-md-4 mb-3">
                        <label for="break_start" class="form-label">Jam Mulai Istirahat</label>
                        <input type="time" class="form-control @error('break_start') is-invalid @enderror" 
                               id="break_start" name="break_start" value="{{ old('break_start', $shift->break_start ?? '') }}">
                        @error('break_start')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="break_end" class="form-label">Jam Selesai Istirahat</label>
                        <input type="time" class="form-control @error('break_end') is-invalid @enderror" 
                               id="break_end" name="break_end" value="{{ old('break_end', $shift->break_end ?? '') }}">
                        @error('break_end')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="break_duration" class="form-label">Durasi Istirahat (menit)</label>
                        <input type="number" class="form-control @error('break_duration') is-invalid @enderror" 
                               id="break_duration" name="break_duration" 
                               value="{{ old('break_duration', $shift->break_duration ?? 60) }}" min="0">
                        @error('break_duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Lembur -->
                    <div class="col-md-6 mb-3">
                        <label for="overtime_threshold" class="form-label">Minimal Lembur (menit)</label>
                        <input type="number" class="form-control @error('overtime_threshold') is-invalid @enderror" 
                               id="overtime_threshold" name="overtime_threshold" 
                               value="{{ old('overtime_threshold', $shift->overtime_threshold ?? 60) }}" min="0">
                        @error('overtime_threshold')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="overtime_rate" class="form-label">Rate Lembur (x)</label>
                        <input type="number" step="0.1" class="form-control @error('overtime_rate') is-invalid @enderror" 
                               id="overtime_rate" name="overtime_rate" 
                               value="{{ old('overtime_rate', $shift->overtime_rate ?? 1.5) }}" min="0" max="10">
                        @error('overtime_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Contoh: 1.5 (standar), 2.0 (hari libur), 3.0 (hari besar)</small>
                    </div>

                    <!-- Shift Malam -->
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch mt-2">
                            <input type="hidden" name="night_shift" value="0">
                            <input type="checkbox" class="form-check-input @error('night_shift') is-invalid @enderror" 
                                   id="night_shift" name="night_shift" value="1" 
                                   {{ old('night_shift', $shift->night_shift ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="night_shift">Shift Malam</label>
                            @error('night_shift')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 mb-3" id="night_shift_start_wrapper" style="{{ old('night_shift', $shift->night_shift ?? false) ? '' : 'display:none' }}">
                        <label for="night_shift_start" class="form-label">Jam Mulai Shift Malam</label>
                        <input type="time" class="form-control @error('night_shift_start') is-invalid @enderror" 
                               id="night_shift_start" name="night_shift_start" 
                               value="{{ old('night_shift_start', $shift->night_shift_start ?? '22:00') }}">
                        @error('night_shift_start')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Waktu mulai perhitungan shift malam (default 22:00)</small>
                    </div>

                    <!-- Status -->
                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror" 
                                   id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $shift->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktif</label>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('shift.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        {{ isset($shift->id) ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>  
<script>
    document.getElementById('night_shift').addEventListener('change', function() {
        const wrapper = document.getElementById('night_shift_start_wrapper');
        if (this.checked) {
            wrapper.style.display = 'block';
        } else {
            wrapper.style.display = 'none';
        }
    });
</script> 