 <div class="row g-3">
     {{-- Opsi Rentang Tanggal --}}
     {{-- ============================================= --}}
     {{-- MODE CREATE: Tampilkan Checkbox Range --}}
     {{-- ============================================= --}}
     @if (!$holiday->exists)
         <div class="col-12">
             <div class="form-check form-switch">
                 <input class="form-check-input" type="checkbox" role="switch" id="isRange" name="is_range" value="1"
                     {{ old('is_range') ? 'checked' : '' }}>
                 <label class="form-check-label fw-bold" for="isRange">
                     Libur dalam rentang tanggal (Contoh: Libur Semester)
                 </label>
             </div>
         </div>

         {{-- Tanggal Mulai --}}
         <div class="col-md-6">
             <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
             <input type="date" name="start_date" id="start_date"
                 class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}"
                 required>
             @error('start_date')
                 <div class="invalid-feedback">{{ $message }}</div>
             @enderror
         </div>

         {{-- Tanggal Selesai (Hidden by default) --}}
         <div class="col-md-6" id="end_date_container" style="display: none;">
             <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
             <input type="date" name="end_date" id="end_date"
                 class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
             @error('end_date')
                 <div class="invalid-feedback">{{ $message }}</div>
             @enderror
             <div class="form-text text-muted">Sistem akan mencatat setiap hari antara tanggal mulai dan selesai.</div>
         </div>
     @else
         {{-- ============================================= --}}
         {{-- MODE EDIT: Hanya 1 Tanggal --}}
         {{-- ============================================= --}}
         <div class="col-md-6">
             <label class="form-label">Tanggal Libur <span class="text-danger">*</span></label>
             <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                 value="{{ old('date', \Carbon\Carbon::parse($holiday->date)->format('Y-m-d')) }}" required>
             @error('date')
                 <div class="invalid-feedback">{{ $message }}</div>
             @enderror
         </div>
     @endif

     {{-- Baris 2: Nama & Jenis Libur --}}
     <div class="col-md-8">
         <label class="form-label">Nama Periode Libur <span class="text-danger">*</span></label>
         <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
             value="{{ old('name', $holiday->name ?? '') }}" placeholder="Contoh: Libur Semester Genap 2025/2026"
             required>
         @error('name')
             <div class="invalid-feedback">{{ $message }}</div>
         @enderror
     </div>

     <div class="col-md-4">
         <label class="form-label">Jenis Libur <span class="text-danger">*</span></label>
         <select name="type" class="form-select @error('type') is-invalid @enderror" required>
             <option value="national" {{ old('type', $holiday->type ?? 'national') == 'national' ? 'selected' : '' }}>
                 🇮🇩 Libur Nasional
             </option>
             <option value="special" {{ old('type', $holiday->type) == 'special' ? 'selected' : '' }}>
                 🏫 Libur Khusus Yayasan
             </option>
         </select>
         @error('type')
             <div class="invalid-feedback">{{ $message }}</div>
         @enderror
     </div>

     {{-- Baris 3: Status Berulang --}}
     <div class="col-12">
         <div class="form-check form-switch">
             <input class="form-check-input" type="checkbox" role="switch" name="is_recurring" id="is_recurring"
                 value="1" {{ old('is_recurring', $holiday->is_recurring ?? 0) ? 'checked' : '' }}>
             <label class="form-check-label" for="is_recurring">
                 Berulang Tiap Tahun pada tanggal yang sama?
             </label>
         </div>
     </div>
 </div>
