 @php
     $isEdit = isset($promotion_batch) && $promotion_batch->exists;
 @endphp

 <div class="row g-3">
     {{-- Kode --}}
     <div class="col-md-4">
         {{ Form::label('code', 'Kode Batch', ['class' => 'form-label']) }} <span class="text-danger">*</span>
         {{ Form::text('code', $isEdit ? null : 'PB-' . now()->format('Ymd-His'), [
             'class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : ''),
             'maxlength' => 50,
             'required',
         ]) }}
         @error('code')
             <div class="invalid-feedback">{{ $message }}</div>
         @enderror
     </div>

     {{-- Nama --}}
     <div class="col-md-8">
         {{ Form::label('name', 'Nama Batch', ['class' => 'form-label']) }} <span class="text-danger">*</span>
         {{ Form::text('name', null, [
             'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
             'placeholder' => 'Contoh: Kenaikan Kelas TA 2025/2026 ke 2026/2027',
             'maxlength' => 150,
             'required',
         ]) }}
         @error('name')
             <div class="invalid-feedback">{{ $message }}</div>
         @enderror
     </div>

     {{-- Tahun ajaran asal --}}
     <div class="col-md-6">
         {{ Form::label('from_academic_year_id', 'Dari Tahun Ajaran', ['class' => 'form-label']) }} <span
             class="text-danger">*</span>
         {{ Form::select('from_academic_year_id', $academicYears, null, [
             'class' => 'form-select' . ($errors->has('from_academic_year_id') ? ' is-invalid' : ''),
             'placeholder' => '-- Pilih Tahun Ajaran Asal --',
             'required',
         ]) }}
         @error('from_academic_year_id')
             <div class="invalid-feedback">{{ $message }}</div>
         @enderror
     </div>

     {{-- Tahun ajaran tujuan --}}
     <div class="col-md-6">
         {{ Form::label('to_academic_year_id', 'Ke Tahun Ajaran', ['class' => 'form-label']) }} <span
             class="text-danger">*</span>
         {{ Form::select('to_academic_year_id', $academicYears, null, [
             'class' => 'form-select' . ($errors->has('to_academic_year_id') ? ' is-invalid' : ''),
             'placeholder' => '-- Pilih Tahun Ajaran Tujuan --',
             'required',
         ]) }}
         @error('to_academic_year_id')
             <div class="invalid-feedback">{{ $message }}</div>
         @enderror
     </div>

     {{-- Status (hanya saat edit, hanya draft -> cancelled) --}}
     @if ($isEdit)
         <div class="col-md-6">
             {{ Form::label('status', 'Status', ['class' => 'form-label']) }} <span class="text-danger">*</span>
             {{ Form::select('status', ['draft' => 'Draft', 'cancelled' => 'Dibatalkan'], null, [
                 'class' => 'form-select' . ($errors->has('status') ? ' is-invalid' : ''),
                 'required',
             ]) }}
             <div class="form-text">Status "Diproses" dan "Selesai" diatur otomatis oleh proses kenaikan kelas.</div>
             @error('status')
                 <div class="invalid-feedback">{{ $message }}</div>
             @enderror
         </div>
     @endif
 </div>
