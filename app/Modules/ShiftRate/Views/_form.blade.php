 {{-- ========================================= --}}
 {{-- SECTION 1: IDENTIFIKASI SHIFT & HARI     --}}
 {{-- ========================================= --}}
 <div class="card bg-light mb-3">
     <div class="card-body py-2">
         <h6 class="mb-0"><i class="bi bi-calendar-event"></i> Identifikasi Shift & Hari</h6>
         <small class="text-muted">Kombinasi Shift + Jenis Hari harus unik (tidak boleh duplikat).</small>
     </div>
 </div>

 <div class="row g-3 mb-3">
     <div class="col-md-6">
         <label class="form-label">Shift <span class="text-danger">*</span></label>
         <select name="shift_id" id="shift_id" class="form-select @error('shift_id') is-invalid @enderror" required>
             <option value="">-- Pilih Shift --</option>
             @foreach ($shifts as $shift)
                 <option value="{{ $shift->id }}" data-start="{{ $shift->start_time }}"
                     data-end="{{ $shift->end_time }}"
                     {{ old('shift_id', $shiftRate->shift_id) == $shift->id ? 'selected' : '' }}>
                     {{ $shift->code }} - {{ $shift->name }}
                     ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} -
                     {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                 </option>
             @endforeach
         </select>
         @error('shift_id')
             <div class="invalid-feedback">{{ $message }}</div>
         @enderror
         <div id="shiftInfo" class="form-text"></div>
     </div>

     <div class="col-md-6">
         <label class="form-label">Jenis Hari <span class="text-danger">*</span></label>
         <select name="day_type" class="form-select @error('day_type') is-invalid @enderror" required>
             <option value="">-- Pilih Jenis Hari --</option>
             <option value="weekday" {{ old('day_type', $shiftRate->day_type) == 'weekday' ? 'selected' : '' }}>Senin -
                 Jumat (Hari Kerja)</option>
             <option value="saturday" {{ old('day_type', $shiftRate->day_type) == 'saturday' ? 'selected' : '' }}>Sabtu
             </option>
             <option value="sunday" {{ old('day_type', $shiftRate->day_type) == 'sunday' ? 'selected' : '' }}>Minggu
                 (Libur Mingguan)</option>
             <option value="national_holiday"
                 {{ old('day_type', $shiftRate->day_type) == 'national_holiday' ? 'selected' : '' }}>Hari Libur
                 Nasional</option>
             <option value="special_holiday"
                 {{ old('day_type', $shiftRate->day_rate) == 'special_holiday' ? 'selected' : '' }}>Hari Libur Khusus
                 Yayasan</option>
         </select>
         @error('day_type')
             <div class="invalid-feedback">{{ $message }}</div>
         @enderror
     </div>
 </div>

 {{-- ========================================= --}}
 {{-- SECTION 2: UPAH DASAR & LEMBUR           --}}
 {{-- ========================================= --}}
 <div class="card bg-light mb-3">
     <div class="card-body py-2">
         <h6 class="mb-0"><i class="bi bi-cash-coin"></i> Upah Dasar & Lembur</h6>
     </div>
 </div>

 <div class="row g-3 mb-3">
     <div class="col-md-4">
         <label class="form-label">Upah Dasar per Shift (Rp) <span class="text-danger">*</span></label>
         <div class="input-group">
             <span class="input-group-text">Rp</span>
             <input type="number" step="0.01" min="0" name="base_rate"
                 class="form-control @error('base_rate') is-invalid @enderror"
                 value="{{ old('base_rate', $shiftRate->base_rate) }}" required>
         </div>
         @error('base_rate')
             <div class="invalid-feedback">{{ $message }}</div>
         @enderror
     </div>

     <div class="col-md-4">
         <label class="form-label">Upah Lembur per Jam (Rp) <span class="text-danger">*</span></label>
         <div class="input-group">
             <span class="input-group-text">Rp</span>
             <input type="number" step="0.01" min="0" name="overtime_rate_per_hour"
                 class="form-control @error('overtime_rate_per_hour') is-invalid @enderror"
                 value="{{ old('overtime_rate_per_hour', $shiftRate->overtime_rate_per_hour) }}" required>
         </div>
         @error('overtime_rate_per_hour')
             <div class="invalid-feedback">{{ $message }}</div>
         @enderror
     </div>

     <div class="col-md-4">
         <label class="form-label">Pengali Lembur <span class="text-danger">*</span></label>
         <div class="input-group">
             <input type="number" step="0.01" min="1" name="overtime_multiplier"
                 class="form-control @error('overtime_multiplier') is-invalid @enderror"
                 value="{{ old('overtime_multiplier', $shiftRate->overtime_multiplier ?? 1.5) }}" required>
             <span class="input-group-text">x</span>
         </div>
         @error('overtime_multiplier')
             <div class="invalid-feedback">{{ $message }}</div>
         @enderror
         <div class="form-text">Contoh: 1.5 = 150% dari upah normal per jam.</div>
     </div>
 </div>

 {{-- ========================================= --}}
 {{-- SECTION 3: TUNJANGAN                     --}}
 {{-- ========================================= --}}
 <div class="card bg-light mb-3">
     <div class="card-body py-2">
         <h6 class="mb-0"><i class="bi bi-gift"></i> Tunjangan (Flat per Shift)</h6>
         <small class="text-muted">Isi 0 jika tidak ada tunjangan untuk kategori tersebut.</small>
     </div>
 </div>

 <div class="row g-3 mb-3">
     <div class="col-md-4">
         <label class="form-label">Tunjangan Shift Malam (Rp)</label>
         <div class="input-group">
             <span class="input-group-text">Rp</span>
             <input type="number" step="0.01" min="0" name="night_allowance" class="form-control"
                 value="{{ old('night_allowance', $shiftRate->night_allowance ?? 0) }}">
         </div>
         <div class="form-text">Diberikan jika shift melewati tengah malam.</div>
     </div>

     <div class="col-md-4">
         <label class="form-label">Uang Makan (Rp)</label>
         <div class="input-group">
             <span class="input-group-text">Rp</span>
             <input type="number" step="0.01" min="0" name="meal_allowance" class="form-control"
                 value="{{ old('meal_allowance', $shiftRate->meal_allowance ?? 0) }}">
         </div>
     </div>

     <div class="col-md-4">
         <label class="form-label">Uang Transport (Rp)</label>
         <div class="input-group">
             <span class="input-group-text">Rp</span>
             <input type="number" step="0.01" min="0" name="transport_allowance" class="form-control"
                 value="{{ old('transport_allowance', $shiftRate->transport_allowance ?? 0) }}">
         </div>
     </div>
 </div>

 {{-- ========================================= --}}
 {{-- SECTION 4: STATUS                        --}}
 {{-- ========================================= --}}
 <div class="row g-3">
     <div class="col-md-12">
         <label class="form-label">Status</label>
         <div class="form-check form-switch">
             <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="is_active"
                 value="1" {{ old('is_active', $shiftRate->is_active ?? 1) ? 'checked' : '' }}>
             <label class="form-check-label" for="is_active">
                 <strong>Aktif</strong> — Rate ini akan digunakan dalam perhitungan payroll.
             </label>
         </div>
     </div>
 </div>
