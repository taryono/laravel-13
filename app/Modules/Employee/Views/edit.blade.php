@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ isset($employee->id) ? 'Edit Karyawan' : 'Tambah Karyawan Baru' }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ isset($employee->id) ? route('employee.update', $employee->id) : route('employee.store') }}" 
                  method="POST" id="employee-form">
                @csrf
                @if(isset($employee->id))
                    @method('PUT')
                @endif

                <div class="row">
                    <!-- Informasi Dasar -->
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3">Informasi Dasar</h6>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nik') is-invalid @enderror" 
                               id="nik" name="nik" 
                               value="{{ old('nik', $employee->nik ?? '') }}" required>
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" 
                               value="{{ old('name', $employee->name ?? '') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="nick_name" class="form-label">Nama Panggilan</label>
                        <input type="text" class="form-control @error('nick_name') is-invalid @enderror" 
                               id="nick_name" name="nick_name" 
                               value="{{ old('nick_name', $employee->nick_name ?? '') }}">
                        @error('nick_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Departemen & Jabatan -->
                    <div class="col-12 mt-2">
                        <h6 class="border-bottom pb-2 mb-3">Penempatan</h6>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="department_id" class="form-label">Departemen</label>
                        <select class="form-select @error('department_id') is-invalid @enderror" 
                                id="department_id" name="department_id">
                            <option value="">Pilih Departemen</option>
                            @foreach($departments as $id => $name)
                                <option value="{{ $id }}" 
                                    {{ old('department_id', $employee->department_id ?? '') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="position_id" class="form-label">Jabatan</label>
                        <select class="form-select @error('position_id') is-invalid @enderror" 
                                id="position_id" name="position_id">
                            <option value="">Pilih Jabatan</option>
                            @foreach($positions as $id => $name)
                                <option value="{{ $id }}" 
                                    {{ old('position_id', $employee->position_id ?? '') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('position_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="supervisor_id" class="form-label">Atasan Langsung</label>
                        <select class="form-select @error('supervisor_id') is-invalid @enderror" 
                                id="supervisor_id" name="supervisor_id">
                            <option value="">Pilih Atasan</option>
                            @foreach($supervisors as $id => $name)
                                <option value="{{ $id }}" 
                                    {{ old('supervisor_id', $employee->supervisor_id ?? '') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supervisor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Data Pribadi -->
                    <div class="col-12 mt-2">
                        <h6 class="border-bottom pb-2 mb-3">Data Pribadi</h6>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="gender" class="form-label">Jenis Kelamin</label>
                        <select class="form-select @error('gender') is-invalid @enderror" 
                                id="gender" name="gender">
                            <option value="">Pilih</option>
                            <option value="L" {{ old('gender', $employee->gender ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender', $employee->gender ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="birth_place" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control @error('birth_place') is-invalid @enderror" 
                               id="birth_place" name="birth_place" 
                               value="{{ old('birth_place', $employee->birth_place ?? '') }}">
                        @error('birth_place')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="birth_date" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                               id="birth_date" name="birth_date" 
                               value="{{ old('birth_date', isset($employee->birth_date) ? $employee->birth_date->format('Y-m-d') : '') }}">
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="phone" class="form-label">No. Telepon</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" 
                               value="{{ old('phone', $employee->phone ?? '') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" 
                               value="{{ old('email', $employee->email ?? '') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="2">{{ old('address', $employee->address ?? '') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status Kepegawaian -->
                    <div class="col-12 mt-2">
                        <h6 class="border-bottom pb-2 mb-3">Status Kepegawaian</h6>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="join_date" class="form-label">Tanggal Bergabung <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('join_date') is-invalid @enderror" 
                               id="join_date" name="join_date" 
                               value="{{ old('join_date', isset($employee->join_date) ? $employee->join_date->format('Y-m-d') : '') }}" required>
                        @error('join_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="resign_date" class="form-label">Tanggal Resign</label>
                        <input type="date" class="form-control @error('resign_date') is-invalid @enderror" 
                               id="resign_date" name="resign_date" 
                               value="{{ old('resign_date', isset($employee->resign_date) ? $employee->resign_date->format('Y-m-d') : '') }}">
                        @error('resign_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="employment_status" class="form-label">Status Karyawan <span class="text-danger">*</span></label>
                        <select class="form-select @error('employment_status') is-invalid @enderror" 
                                id="employment_status" name="employment_status" required>
                            <option value="probation" {{ old('employment_status', $employee->employment_status ?? '') == 'probation' ? 'selected' : '' }}>Probation</option>
                            <option value="tetap" {{ old('employment_status', $employee->employment_status ?? '') == 'tetap' ? 'selected' : '' }}>Tetap</option>
                            <option value="kontrak" {{ old('employment_status', $employee->employment_status ?? '') == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                            <option value="freelance" {{ old('employment_status', $employee->employment_status ?? '') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                        </select>
                        @error('employment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="form-check form-switch mt-4">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror" 
                                   id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $employee->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktif</label>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Data Bank & BPJS -->
                    <div class="col-12 mt-2">
                        <h6 class="border-bottom pb-2 mb-3">Data Bank & BPJS</h6>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="bank_name" class="form-label">Nama Bank</label>
                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                               id="bank_name" name="bank_name" 
                               value="{{ old('bank_name', $employee->bank_name ?? '') }}">
                        @error('bank_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="bank_account_number" class="form-label">No. Rekening</label>
                        <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" 
                               id="bank_account_number" name="bank_account_number" 
                               value="{{ old('bank_account_number', $employee->bank_account_number ?? '') }}">
                        @error('bank_account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="bank_account_name" class="form-label">Nama Pemilik Rekening</label>
                        <input type="text" class="form-control @error('bank_account_name') is-invalid @enderror" 
                               id="bank_account_name" name="bank_account_name" 
                               value="{{ old('bank_account_name', $employee->bank_account_name ?? '') }}">
                        @error('bank_account_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="npwp" class="form-label">NPWP</label>
                        <input type="text" class="form-control @error('npwp') is-invalid @enderror" 
                               id="npwp" name="npwp" 
                               value="{{ old('npwp', $employee->npwp ?? '') }}">
                        @error('npwp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="bpjs_ketenagakerjaan" class="form-label">BPJS Ketenagakerjaan</label>
                        <input type="text" class="form-control @error('bpjs_ketenagakerjaan') is-invalid @enderror" 
                               id="bpjs_ketenagakerjaan" name="bpjs_ketenagakerjaan" 
                               value="{{ old('bpjs_ketenagakerjaan', $employee->bpjs_ketenagakerjaan ?? '') }}">
                        @error('bpjs_ketenagakerjaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="bpjs_kesehatan" class="form-label">BPJS Kesehatan</label>
                        <input type="text" class="form-control @error('bpjs_kesehatan') is-invalid @enderror" 
                               id="bpjs_kesehatan" name="bpjs_kesehatan" 
                               value="{{ old('bpjs_kesehatan', $employee->bpjs_kesehatan ?? '') }}">
                        @error('bpjs_kesehatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('employee.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        {{ isset($employee->id) ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-fill bank account name with full name if empty
    document.getElementById('name').addEventListener('change', function() {
        var accountName = document.getElementById('bank_account_name');
        if (!accountName.value) {
            accountName.value = this.value;
        }
    });

    // Validate resign date >= join date
    document.getElementById('resign_date').addEventListener('change', function() {
        var joinDate = document.getElementById('join_date').value;
        var resignDate = this.value;
        
        if (joinDate && resignDate && resignDate < joinDate) {
            alert('Tanggal resign harus setelah tanggal bergabung!');
            this.value = '';
        }
    });
</script>
@endpush