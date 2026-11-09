 {{-- Sembunyikan input jika diperlukan / otomatis dari konteks --}}
 @if (!isset($academic_year))
     <div class="row mb-3">
         <label class="col-md-3 form-label">Organisasi <span class="text-danger">*</span></label>
         <div class="col-md-9">
             {{ Form::select('organization_id', $organizations ?? [], null, ['class' => 'form-select', 'required' => 'required', 'placeholder' => '-- Pilih Organisasi --']) }}
         </div>
     </div>

     <div class="row mb-3">
         <label class="col-md-3 form-label">Sekolah <span class="text-danger">*</span></label>
         <div class="col-md-9">
             {{ Form::select('school_id', $schools ?? [], null, ['class' => 'form-select', 'required' => 'required', 'placeholder' => '-- Pilih Sekolah --']) }}
         </div>
     </div>
 @endif

 <div class="row mb-3">
     <label class="col-md-3 form-label">Nama Tahun Ajaran <span class="text-danger">*</span></label>
     <div class="col-md-9">
         {{ Form::text('name', $academic_year->name ?? null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Contoh: Tahun Ajaran 2026/2027']) }}
     </div>
 </div>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Kode <span class="text-danger">*</span></label>
     <div class="col-md-9">
         {{ Form::text('code', $academic_year->code ?? null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => 20, 'placeholder' => 'Contoh: TA2026-27']) }}
         <small class="text-muted">Unik per Organisasi</small>
     </div>
 </div>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Tanggal Mulai <span class="text-danger">*</span></label>
     <div class="col-md-9">
         {{ Form::date('start_date', $academic_year->start_date ?? null, ['class' => 'form-control', 'required' => 'required']) }}
     </div>
 </div>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Tanggal Selesai <span class="text-danger">*</span></label>
     <div class="col-md-9">
         {{ Form::date('end_date', $academic_year->end_date ?? null, ['class' => 'form-control', 'required' => 'required']) }}
     </div>
 </div>

 <hr>
 <h6 class="mb-3">Periode Semester</h6>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Tanggal Mulai Semester</label>
     <div class="col-md-9">
         {{ Form::date('semester_start_date', $academic_year->semester_start_date ?? null, ['class' => 'form-control']) }}
     </div>
 </div>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Tanggal Selesai Semester</label>
     <div class="col-md-9">
         {{ Form::date('semester_end_date', $academic_year->semester_end_date ?? null, ['class' => 'form-control']) }}
     </div>
 </div>

 <hr>
 <h6 class="mb-3">Pengaturan Pendaftaran</h6>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Buka Pendaftaran?</label>
     <div class="col-md-9 pt-2">
         <label class="form-check-label me-3">
             {{ Form::checkbox('is_registration_open', 1, $academic_year->is_registration_open ?? false, ['class' => 'form-check-input']) }}
             Ya, Buka
         </label>
     </div>
 </div>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Tanggal Mulai Daftar</label>
     <div class="col-md-9">
         {{ Form::date('registration_start_date', $academic_year->registration_start_date ?? null, ['class' => 'form-control']) }}
     </div>
 </div>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Tanggal Tutup Daftar</label>
     <div class="col-md-9">
         {{ Form::date('registration_end_date', $academic_year->registration_end_date ?? null, ['class' => 'form-control']) }}
     </div>
 </div>

 <hr>
 <h6 class="mb-3">Status</h6>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Status Aktif</label>
     <div class="col-md-9 pt-2">
         <label class="form-check-label me-3">
             {{ Form::checkbox('is_active', 1, $academic_year->is_active ?? true, ['class' => 'form-check-input']) }}
             Aktif
         </label>
     </div>
 </div>

 <div class="row mb-3">
     <label class="col-md-3 form-label">Tahun Ajaran Saat Ini</label>
     <div class="col-md-9 pt-2">
         <label class="form-check-label me-3">
             {{ Form::checkbox('is_current', 1, $academic_year->is_current ?? false, ['class' => 'form-check-input']) }}
             Jadikan Saat Ini
         </label>
         <small class="text-muted">Hanya boleh satu yang aktif</small>
     </div>
 </div>

 {{-- DI BAWAH isi Tahun Ajaran, tambahkan Bagian Periode --}}
 <hr>
 <h5 class="mb-3">Daftar Periode Tahun Ajaran Ini</h5>

 <div id="periodsWrapper">
     {{-- Baris Periode Awal --}}
     <div class="period-row border p-3 mb-2 bg-light rounded">
         <div class="row">
             <div class="col-md-2 mb-2">
                 <label class="form-label">Nama</label>
                 <input type="text" name="periods[name][]" class="form-control" placeholder="Semester 1">
             </div>
             <div class="col-md-2 mb-2">
                 <label class="form-label">Kode</label>
                 <input type="text" name="periods[code][]" class="form-control" placeholder="SEM1">
             </div>
             <div class="col-md-2 mb-2">
                 <label class="form-label">Tanggal Mulai</label>
                 <input type="date" name="periods[start_date][]" class="form-control">
             </div>
             <div class="col-md-2 mb-2">
                 <label class="form-label">Tanggal Selesai</label>
                 <input type="date" name="periods[end_date][]" class="form-control">
             </div>
             <div class="col-md-2 mb-2">
                 <label class="form-label">Aktif</label>
                 <select name="periods[is_active][]" class="form-select">
                     <option value="1">Ya</option>
                     <option value="0">Tidak</option>
                 </select>
             </div>
             <div class="col-md-2 mb-2 pt-4">
                 <button type="button" class="btn btn-outline-danger btn-remove-period">Hapus</button>
             </div>
         </div>
     </div>
 </div>

 <button type="button" class="btn btn-outline-success btn-sm mt-2" id="btnAddPeriod">
     + Tambah Baris Periode
 </button>
