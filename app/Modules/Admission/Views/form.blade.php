  <div class="row g-3">
      {{-- Sekolah --}}
      <div class="col-md-6">
          <label for="school_id" class="form-label fw-semibold">Sekolah <span class="text-danger">*</span></label>
          <select name="school_id" id="school_id" class="form-select @error('school_id') is-invalid @enderror" required>
              <option value="">-- Pilih Sekolah --</option>
              @foreach ($schools as $school)
                  <option value="{{ $school->id }}" @selected(old('school_id', $admission->school_id ?? auth()->user()?->school_id) == $school->id)>
                      {{ $school->name }}
                  </option>
              @endforeach
          </select>
          @error('school_id')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
      </div>

      {{-- Tahun Ajaran --}}
      <div class="col-md-6">
          <label for="academic_year_id" class="form-label fw-semibold">Tahun Ajaran <span
                  class="text-danger">*</span></label>
          <select name="academic_year_id" id="academic_year_id"
              class="form-select @error('academic_year_id') is-invalid @enderror" required>
              <option value="">-- Pilih Tahun Ajaran --</option>
              @foreach ($academicYears as $year)
                  <option value="{{ $year->id }}" @selected(old('academic_year_id', $admission->academic_year_id ?? null) == $year->id)>
                      {{ $year->name }}
                  </option>
              @endforeach
          </select>
          @error('academic_year_id')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
      </div>

      {{-- Kode Gelombang --}}
      <div class="col-md-4">
          <label for="code" class="form-label fw-semibold">Kode Gelombang <span class="text-danger">*</span></label>
          <input type="text" name="code" id="code" maxlength="20"
              class="form-control text-uppercase @error('code') is-invalid @enderror" placeholder="PPDB-2026-G1"
              value="{{ old('code', $admission->code ?? '') }}" required>
          @error('code')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
      </div>

      {{-- Nama Gelombang / Jalur --}}
      <div class="col-md-8">
          <label for="name" class="form-label fw-semibold">Nama Gelombang / Jalur <span
                  class="text-danger">*</span></label>
          <input type="text" name="name" id="name" maxlength="100"
              class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Gelombang 1 - Jalur Reguler"
              value="{{ old('name', $admission->name ?? '') }}" required>
          @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
      </div>

      {{-- Jalur Pendaftaran --}}
      <div class="col-md-6">
          <label for="type" class="form-label fw-semibold">Tipe / Jalur Pendaftaran</label>
          <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
              @php
                  $types = [
                      'REGULAR' => 'Jalur Reguler',
                      'ACHIEVEMENT' => 'Jalur Prestasi',
                      'AFFIRMATION' => 'Jalur Afirmasi / Beasiswa',
                      'TRANSFER' => 'Siswa Pindahan',
                  ];
              @endphp
              @foreach ($types as $val => $label)
                  <option value="{{ $val }}" @selected(old('type', $admission->type ?? 'REGULAR') === $val)>
                      {{ $label }}
                  </option>
              @endforeach
          </select>
          @error('type')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
      </div>

      {{-- Tingkat Kelas Tujuan --}}
      <div class="col-md-6">
          <label for="class_level_id" class="form-label fw-semibold">Tingkat Kelas Tujuan</label>
          <select name="class_level_id" id="class_level_id"
              class="form-select @error('class_level_id') is-invalid @enderror">
              <option value="">-- Semua Tingkat / Umum --</option>
              @foreach ($classLevels as $level)
                  <option value="{{ $level->id }}" @selected(old('class_level_id', $admission->class_level_id ?? null) == $level->id)>
                      {{ $level->name }}
                  </option>
              @endforeach
          </select>
          @error('class_level_id')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
      </div>

      {{-- Tanggal Mulai --}}
      <div class="col-md-6">
          <label for="start_date" class="form-label fw-semibold">Tanggal Mulai Pendaftaran <span
                  class="text-danger">*</span></label>
          <input type="date" name="start_date" id="start_date"
              class="form-control @error('start_date') is-invalid @enderror"
              value="{{ old('start_date', isset($admission) ? $admission->start_date?->format('Y-m-d') : '') }}"
              required>
          @error('start_date')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
      </div>

      {{-- Tanggal Selesai --}}
      <div class="col-md-6">
          <label for="end_date" class="form-label fw-semibold">Tanggal Tutup Pendaftaran <span
                  class="text-danger">*</span></label>
          <input type="date" name="end_date" id="end_date"
              class="form-control @error('end_date') is-invalid @enderror"
              value="{{ old('end_date', isset($admission) ? $admission->end_date?->format('Y-m-d') : '') }}" required>
          @error('end_date')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
      </div>

      {{-- Kuota & Biaya Pendaftaran --}}
      <div class="col-md-4">
          <label for="quota" class="form-label fw-semibold">Kuota Penerimaan <span
                  class="text-danger">*</span></label>
          <div class="input-group">
              <input type="number" name="quota" id="quota" min="0"
                  class="form-control @error('quota') is-invalid @enderror"
                  value="{{ old('quota', $admission->quota ?? 0) }}" required>
              <span class="input-group-text">Siswa</span>
          </div>
          @error('quota')
              <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
      </div>

      <div class="col-md-4">
          <label for="registration_fee" class="form-label fw-semibold">Biaya Pendaftaran <span
                  class="text-danger">*</span></label>
          <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="number" name="registration_fee" id="registration_fee" min="0" step="1000"
                  class="form-control @error('registration_fee') is-invalid @enderror"
                  value="{{ old('registration_fee', isset($admission) ? (int) $admission->registration_fee : 0) }}"
                  required>
          </div>
          @error('registration_fee')
              <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
      </div>

      <div class="col-12">
          <div class="border rounded-3 p-3 bg-light">
              <div class="d-flex justify-content-between align-items-center mb-3">
                  <div>
                      <h6 class="fw-bold mb-0 text-dark">Ketentuan Batas Usia Calon Siswa</h6>
                      <small class="text-muted">Kosongkan jika gelombang ini tidak membatasi usia pendaftar.</small>
                  </div>
              </div>

              <div class="row g-3">
                  {{-- KOTAK 1: USIA MINIMAL --}}
                  <div class="col-md-6">
                      <div class="bg-white border rounded-3 p-3 h-100">
                          <span class="badge bg-primary-subtle text-primary mb-2">Batas Usia Minimal</span>

                          <div class="row g-2 mb-2">
                              <div class="col-6">
                                  <label for="min_age_years" class="form-label small fw-semibold mb-1">Tahun</label>
                                  <div class="input-group input-group-sm">
                                      <input type="number" name="min_age_years" id="min_age_years" min="0"
                                          max="30"
                                          class="form-control age-rule-input @error('min_age_years') is-invalid @enderror"
                                          placeholder="6"
                                          value="{{ old('min_age_years', $admission->min_age_years ?? '') }}">
                                      <span class="input-group-text">Thn</span>
                                  </div>
                              </div>
                              <div class="col-6">
                                  <label for="min_age_months" class="form-label small fw-semibold mb-1">Bulan</label>
                                  <div class="input-group input-group-sm">
                                      <input type="number" name="min_age_months" id="min_age_months" min="0"
                                          max="11"
                                          class="form-control age-rule-input @error('min_age_months') is-invalid @enderror"
                                          placeholder="0"
                                          value="{{ old('min_age_months', $admission->min_age_months ?? 0) }}">
                                      <span class="input-group-text">Bln</span>
                                  </div>
                              </div>
                          </div>

                          <div class="mb-2">
                              <label for="min_age_reference_date" class="form-label small fw-semibold mb-1">
                                  Dihitung Per Tanggal (Patokan)
                              </label>
                              <input type="date" name="min_age_reference_date" id="min_age_reference_date"
                                  class="form-control form-control-sm age-rule-input @error('min_age_reference_date') is-invalid @enderror"
                                  value="{{ old('min_age_reference_date', isset($admission) ? $admission->min_age_reference_date?->format('Y-m-d') : '') }}">
                              @error('min_age_reference_date')
                                  <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                          </div>

                          <div class="small text-muted bg-light rounded p-2" id="minAgePreview">
                              Isi tahun &amp; tanggal patokan untuk melihat batas tanggal lahir.
                          </div>
                      </div>
                  </div>

                  {{-- KOTAK 2: USIA MAKSIMAL --}}
                  <div class="col-md-6">
                      <div class="bg-white border rounded-3 p-3 h-100">
                          <span class="badge bg-warning-subtle text-dark mb-2">Batas Usia Maksimal</span>

                          <div class="row g-2 mb-2">
                              <div class="col-6">
                                  <label for="max_age_years" class="form-label small fw-semibold mb-1">Tahun</label>
                                  <div class="input-group input-group-sm">
                                      <input type="number" name="max_age_years" id="max_age_years" min="0"
                                          max="30"
                                          class="form-control age-rule-input @error('max_age_years') is-invalid @enderror"
                                          placeholder="7"
                                          value="{{ old('max_age_years', $admission->max_age_years ?? '') }}">
                                      <span class="input-group-text">Thn</span>
                                  </div>
                              </div>
                              <div class="col-6">
                                  <label for="max_age_months" class="form-label small fw-semibold mb-1">Bulan</label>
                                  <div class="input-group input-group-sm">
                                      <input type="number" name="max_age_months" id="max_age_months" min="0"
                                          max="11"
                                          class="form-control age-rule-input @error('max_age_months') is-invalid @enderror"
                                          placeholder="0"
                                          value="{{ old('max_age_months', $admission->max_age_months ?? 0) }}">
                                      <span class="input-group-text">Bln</span>
                                  </div>
                              </div>
                          </div>

                          <div class="mb-2">
                              <label for="max_age_reference_date" class="form-label small fw-semibold mb-1">
                                  Dihitung Per Tanggal (Patokan)
                              </label>
                              <input type="date" name="max_age_reference_date" id="max_age_reference_date"
                                  class="form-control form-control-sm age-rule-input @error('max_age_reference_date') is-invalid @enderror"
                                  value="{{ old('max_age_reference_date', isset($admission) ? $admission->max_age_reference_date?->format('Y-m-d') : '') }}">
                              @error('max_age_reference_date')
                                  <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                          </div>

                          <div class="small text-muted bg-light rounded p-2" id="maxAgePreview">
                              Isi tahun &amp; tanggal patokan untuk melihat batas tanggal lahir.
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      {{-- Persyaratan Pendaftaran --}}
      <div class="col-12">
          <label for="requirements" class="form-label fw-semibold">Persyaratan Berkas &amp; Ketentuan</label>
          <textarea name="requirements" id="requirements" rows="3"
              class="form-control @error('requirements') is-invalid @enderror"
              placeholder="Contoh: Fotokopi Akta Kelahiran, Kartu Keluarga, Rapor 2 semester terakhir...">{{ old('requirements', $admission->requirements ?? '') }}</textarea>
          @error('requirements')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
      </div>

      {{-- Status Aktif --}}
      <div class="col-12">
          <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active"
                  value="1" @checked(old('is_active', $admission->is_active ?? true))>
              <label class="form-check-label fw-semibold" for="is_active">
                  Gelombang Aktif (Tampilkan di Portal Pendaftaran PPDB)
              </label>
          </div>
      </div>
  </div>
