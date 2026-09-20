{{-- Informasi Utama --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Informasi Utama</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $student->name ?? '') }}" required maxlength="150">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">NIS <span class="text-danger">*</span></label>
                <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror"
                    value="{{ old('nis', $student->nis ?? '') }}" required maxlength="50">
                @error('nis')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">NISN</label>
                <input type="text" name="nisn" class="form-control @error('nisn') is-invalid @enderror"
                    value="{{ old('nisn', $student->nisn ?? '') }}" maxlength="50">
                @error('nisn')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Nomor Pendaftaran</label>
                <input type="text" name="registration_number"
                    class="form-control @error('registration_number') is-invalid @enderror"
                    value="{{ old('registration_number', $student->registration_number ?? '') }}" maxlength="50">
                @error('registration_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Sekolah</label>
                <select name="school_id" class="form-select @error('school_id') is-invalid @enderror">
                    <option value="">-- Pilih Sekolah --</option>
                    @foreach ($schools as $s)
                        <option value="{{ $s->id }}"
                            {{ old('school_id', $student->school_id ?? '') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
                @error('school_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Tahun Masuk</label>
                <input type="number" name="enrollment_year"
                    class="form-control @error('enrollment_year') is-invalid @enderror"
                    value="{{ old('enrollment_year', $student->enrollment_year ?? '') }}" placeholder="Contoh: 2026">
                @error('enrollment_year')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

{{-- Data Pribadi --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Data Pribadi</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Tempat Lahir</label>
                <input type="text" name="birth_place" class="form-control @error('birth_place') is-invalid @enderror"
                    value="{{ old('birth_place', $student->birth_place ?? '') }}" maxlength="100">
                @error('birth_place')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror"
                    value="{{ old('birth_date', $student->birth_date?->format('Y-m-d') ?? '') }}">
                @error('birth_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                    <option value="">-- Pilih --</option>
                    <option value="MALE" {{ old('gender', $student->gender ?? '') === 'MALE' ? 'selected' : '' }}>
                        Laki-laki</option>
                    <option value="FEMALE" {{ old('gender', $student->gender ?? '') === 'FEMALE' ? 'selected' : '' }}>
                        Perempuan</option>
                </select>
                @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Agama</label>
                <input type="text" name="religion" class="form-control @error('religion') is-invalid @enderror"
                    value="{{ old('religion', $student->religion ?? '') }}" maxlength="20">
                @error('religion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $student->address ?? '') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

{{-- Kontak --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Kontak</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">No. Telp Orang Tua</label>
                <input type="tel" name="parents_phone"
                    class="form-control @error('parents_phone') is-invalid @enderror"
                    value="{{ old('parents_phone', $student->parents_phone ?? '') }}" maxlength="20">
                @error('parents_phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Kontak Darurat</label>
                <input type="tel" name="emergency_contact"
                    class="form-control @error('emergency_contact') is-invalid @enderror"
                    value="{{ old('emergency_contact', $student->emergency_contact ?? '') }}" maxlength="20">
                @error('emergency_contact')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

{{-- Status & Kehadiran --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Status & Administrasi</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Status Siswa <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="ACTIVE"
                        {{ old('status', $student->status ?? 'ACTIVE') === 'ACTIVE' ? 'selected' : '' }}>Aktif</option>
                    <option value="GRADUATED"
                        {{ old('status', $student->status ?? '') === 'GRADUATED' ? 'selected' : '' }}>Lulus</option>
                    <option value="TRANSFERRED"
                        {{ old('status', $student->status ?? '') === 'TRANSFERRED' ? 'selected' : '' }}>Pindah</option>
                    <option value="DROPPED"
                        {{ old('status', $student->status ?? '') === 'DROPPED' ? 'selected' : '' }}>Keluar</option>
                    <option value="SUSPENDED"
                        {{ old('status', $student->status ?? '') === 'SUSPENDED' ? 'selected' : '' }}>Ditangguhkan
                    </option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Jenis Masuk</label>
                <select name="admission_status" class="form-select @error('admission_status') is-invalid @enderror">
                    <option value="">-- Pilih --</option>
                    <option value="NEW"
                        {{ old('admission_status', $student->admission_status ?? '') === 'NEW' ? 'selected' : '' }}>
                        Siswa Baru</option>
                    <option value="TRANSFER"
                        {{ old('admission_status', $student->admission_status ?? '') === 'TRANSFER' ? 'selected' : '' }}>
                        Pindahan</option>
                    <option value="RETURNING"
                        {{ old('admission_status', $student->admission_status ?? '') === 'RETURNING' ? 'selected' : '' }}>
                        Kembali</option>
                </select>
                @error('admission_status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Tanggal Masuk</label>
                <input type="date" name="join_date" class="form-control @error('join_date') is-invalid @enderror"
                    value="{{ old('join_date', $student->join_date?->format('Y-m-d') ?? '') }}">
                @error('join_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Pendaftar (jika ada)</label>
                <select name="applicant_id" class="form-select @error('applicant_id') is-invalid @enderror">
                    <option value="">-- Tidak Ada --</option>
                    @foreach ($applicants as $a)
                        <option value="{{ $a->id }}"
                            {{ old('applicant_id', $student->applicant_id ?? '') == $a->id ? 'selected' : '' }}>
                            {{ $a->name }}
                        </option>
                    @endforeach
                </select>
                @error('applicant_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <label class="form-label">Catatan Medis</label>
                <textarea name="medical_notes" rows="3" class="form-control @error('medical_notes') is-invalid @enderror">{{ old('medical_notes', $student->medical_notes ?? '') }}</textarea>
                @error('medical_notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>
