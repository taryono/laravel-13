 {{-- Pilih Kegiatan --}}
 <div class="row mb-3">
     <label class="col-sm-3 col-form-label fw-semibold">Kegiatan <span class="text-danger">*</span></label>
     <div class="col-sm-9">
         {{ Form::select(
             'extracurricular_id',
             $extracurriculars ?? [],
             old('extracurricular_id', $attendance?->extracurricular_id),
             [
                 'class' => 'form-select' . ($errors->has('extracurricular_id') ? ' is-invalid' : ''),
                 'required' => 'required',
                 'placeholder' => '-- Pilih Kegiatan --',
                 'id' => 'extracurricular_id',
             ],
         ) }}
         @error('extracurricular_id')
             <div class="invalid-feedback d-block">{{ $message }}</div>
         @enderror
     </div>
 </div>

 {{-- Pilih Jadwal/Hari --}}
 <div class="row mb-3">
     <label class="col-sm-3 col-form-label fw-semibold">Jadwal <span class="text-danger">*</span></label>
     <div class="col-sm-9">
         {{ Form::select(
             'extracurricular_day_id',
             $scheduleList ?? [],
             old('extracurricular_day_id', $attendance?->extracurricular_day_id),
             [
                 'class' => 'form-select' . ($errors->has('extracurricular_day_id') ? ' is-invalid' : ''),
                 'required' => 'required',
                 'placeholder' => '-- Pilih Jadwal Dulu --',
                 'id' => 'extracurricular_day_id',
             ],
         ) }}
         <small class="text-muted">Pilih kegiatan terlebih dahulu</small>
         @error('extracurricular_day_id')
             <div class="invalid-feedback d-block">{{ $message }}</div>
         @enderror
     </div>
 </div>

 {{-- Tanggal Kehadiran --}}
 <div class="row mb-3">
     <label class="col-sm-3 col-form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
     <div class="col-sm-9">
         {{ Form::date('attendance_date', old('attendance_date', $attendance?->attendance_date?->format('Y-m-d')), [
             'class' => 'form-control' . ($errors->has('attendance_date') ? ' is-invalid' : ''),
             'required' => 'required',
             'id' => 'attendance_date',
         ]) }}
         @error('attendance_date')
             <div class="invalid-feedback d-block">{{ $message }}</div>
         @enderror
     </div>
 </div>

 {{-- Siswa --}}
 <div class="row mb-3">
     <label class="col-sm-3 col-form-label fw-semibold">Siswa <span class="text-danger">*</span></label>
     <div class="col-sm-9">
         {{ Form::select('student_id', $students ?? [], old('student_id', $attendance?->student_id), [
             'class' => 'form-select' . ($errors->has('student_id') ? ' is-invalid' : ''),
             'required' => 'required',
             'placeholder' => '-- Pilih Siswa --',
             'id' => 'student_id',
         ]) }}
         @error('student_id')
             <div class="invalid-feedback d-block">{{ $message }}</div>
         @enderror
     </div>
 </div>

 {{-- Status Kehadiran --}}
 <div class="row mb-3">
     <label class="col-sm-3 col-form-label fw-semibold">Status <span class="text-danger">*</span></label>
     <div class="col-sm-9">
         @php
             $statusOptions = [
                 'hadir' => '✅ Hadir',
                 'izin' => '📝 Izin',
                 'sakit' => '🏥 Sakit',
                 'alfa' => '❌ Alfa',
             ];
         @endphp
         {{ Form::select('status', $statusOptions, old('status', $attendance?->status), [
             'class' => 'form-select' . ($errors->has('status') ? ' is-invalid' : ''),
             'required' => 'required',
             'id' => 'status',
         ]) }}
         @error('status')
             <div class="invalid-feedback d-block">{{ $message }}</div>
         @enderror
     </div>
 </div>

 {{-- Nilai/Penilaian --}}
 <div class="row mb-3">
     <label class="col-sm-3 col-form-label fw-semibold">Nilai</label>
     <div class="col-sm-9">
         {{ Form::number('score', old('score', $attendance?->score), [
             'class' => 'form-control' . ($errors->has('score') ? ' is-invalid' : ''),
             'min' => 0,
             'max' => 100,
             'step' => '0.01',
             'placeholder' => '0 - 100',
         ]) }}
         <small class="text-muted">Opsional: isikan nilai prestasi/partisipasi</small>
         @error('score')
             <div class="invalid-feedback d-block">{{ $message }}</div>
         @enderror
     </div>
 </div>

 {{-- Keterangan --}}
 <div class="row mb-3">
     <label class="col-sm-3 col-form-label fw-semibold">Keterangan</label>
     <div class="col-sm-9">
         {{ Form::textarea('notes', old('notes', $attendance?->notes), [
             'class' => 'form-control' . ($errors->has('notes') ? ' is-invalid' : ''),
             'rows' => 3,
             'placeholder' => 'Catatan tambahan (jika ada)',
         ]) }}
         @error('notes')
             <div class="invalid-feedback d-block">{{ $message }}</div>
         @enderror
     </div>
 </div>
