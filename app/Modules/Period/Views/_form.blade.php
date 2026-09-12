 {{-- Tahun Ajaran (diisi otomatis dari konteks / pilih saat create) --}}
 @if (!isset($period))
     <div class="row mb-3">
         <label class="col-md-3 form-label">Tahun Ajaran <span class="text-danger">*</span></label>
         <div class="col-md-9">
             {{ Form::select('academic_year_id', $academicYears ?? [], null, ['class' => 'form-select', 'required' => 'required', 'placeholder' => '-- Pilih Tahun Ajaran --']) }}
         </div>
     </div>
 @else
     {{ Form::hidden('academic_year_id', $period->academic_year_id) }}
 @endif

 <div class="row mb-3">
     <label class="col-md-3 form-label">Nama Periode <span class="text-danger">*</span></label>
     <div class="col-md-9">
         {{ Form::text('name', $period->name ?? null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Contoh: Semester Ganjil']) }}
     </div>
 </div>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Kode <span class="text-danger">*</span></label>
     <div class="col-md-9">
         {{ Form::text('code', $period->code ?? null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => 20, 'placeholder' => 'Contoh: SEM-GANJIL']) }}
     </div>
 </div>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Jenis Periode <span class="text-danger">*</span></label>
     <div class="col-md-9">
         {{ Form::select(
             'type',
             [
                 'SEMESTER' => 'Semester',
                 'QUARTER' => 'Kuartal',
                 'TERM' => 'Caturwulan',
             ],
             $period->type ?? null,
             ['class' => 'form-select', 'required' => 'required', 'placeholder' => '-- Pilih Jenis --'],
         ) }}
     </div>
 </div>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Nomor Semester</label>
     <div class="col-md-9">
         {{ Form::select(
             'semester',
             [
                 '' => '-- Tidak Ada --',
                 '1' => 'Semester 1',
                 '2' => 'Semester 2',
                 '3' => 'Semester 3',
                 '4' => 'Semester 4',
             ],
             $period->semester ?? null,
             ['class' => 'form-select'],
         ) }}
     </div>
 </div>

 <hr>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Tanggal Mulai <span class="text-danger">*</span></label>
     <div class="col-md-9">
         {{ Form::date('start_date', $period->start_date ?? null, ['class' => 'form-control', 'required' => 'required']) }}
     </div>
 </div>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Tanggal Selesai <span class="text-danger">*</span></label>
     <div class="col-md-9">
         {{ Form::date('end_date', $period->end_date ?? null, ['class' => 'form-control', 'required' => 'required']) }}
     </div>
 </div>

 <hr>
 <h6 class="mb-3">Periode Penilaian / Input Nilai</h6>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Mulai Penilaian</label>
     <div class="col-md-9">
         {{ Form::date('grading_start_date', $period->grading_start_date ?? null, ['class' => 'form-control']) }}
     </div>
 </div>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Batas Akhir Penilaian</label>
     <div class="col-md-9">
         {{ Form::date('grading_end_date', $period->grading_end_date ?? null, ['class' => 'form-control']) }}
     </div>
 </div>

 <hr>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Status Aktif</label>
     <div class="col-md-9 pt-2">
         <label class="form-check-label">
             {{ Form::checkbox('is_active', 1, $period->is_active ?? true, ['class' => 'form-check-input']) }} Aktif
         </label>
     </div>
 </div>

 <div class="row">
     <div class="col-md-9 offset-md-3">
         <button type="submit" class="btn btn-primary">
             {{ isset($period) ? 'Perbarui' : 'Simpan' }}
         </button>
         <a href="{{ route('period.index') }}" class="btn btn-outline-secondary">Batal</a>
     </div>
 </div>
