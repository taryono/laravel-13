@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ isset($employee->id) ? 'Edit Karyawan' : 'Tambah Karyawan Baru' }}</h5>
            </div>
            <div class="card-body">
                {{ Form::model($employee ?? null, [
                    'route' => isset($employee->id) ? ['employee.update', $employee->id] : ['employee.store'],
                    'method' => isset($employee->id) ? 'PUT' : 'POST',
                    'id' => 'employee-form',
                    'class' => 'form-horizontal form-validation',
                    'files' => true,
                ]) }}

                {{-- organization_id di-handle di backend, tapi kalau perlu eksplisit: --}}
                {{-- <input type="hidden" name="organization_id" value="{{ old('organization_id', $employee->organization_id ?? auth()->user()->organization_id) }}"> --}}

                <div class="row">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3">Informasi Dasar</h6>
                    </div>

                    {{-- Employee Number (readonly, auto-generate) --}}
                    @if (isset($employee->id))
                        <div class="col-md-4 mb-3">
                            {{ Form::label('employee_number', 'Nomor Karyawan', ['class' => 'form-label']) }}
                            {{ Form::text('employee_number', null, [
                                'class' => 'form-control',
                                'id' => 'employee_number',
                                'readonly' => true,
                            ]) }}
                            <small class="text-muted">Dibuat otomatis oleh sistem</small>
                        </div>
                    @endif

                    {{-- NIK --}}
                    <div class="col-md-4 mb-3">
                        {{ Form::label('nik', 'NIK', ['class' => 'form-label']) }}
                        {{ Form::text('nik', null, [
                            'class' => 'form-control' . ($errors->has('nik') ? ' is-invalid' : ''),
                            'id' => 'nik',
                            'maxlength' => 20,
                            'placeholder' => 'Opsional, untuk karyawan kontrak/freelance',
                        ]) }}
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="col-md-4 mb-3">
                        {{ Form::label('name', 'Nama Lengkap', ['class' => 'form-label']) }}
                        <span class="text-danger">*</span>
                        {{ Form::text('name', null, [
                            'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                            'id' => 'name',
                            'required' => true,
                            'maxlength' => 255,
                        ]) }}
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Sesuai KTP --}}
                    <div class="col-md-4 mb-3">
                        {{ Form::label('full_name', 'Nama Sesuai KTP', ['class' => 'form-label']) }}
                        {{ Form::text('full_name', null, [
                            'class' => 'form-control' . ($errors->has('full_name') ? ' is-invalid' : ''),
                            'id' => 'full_name',
                            'maxlength' => 255,
                            'placeholder' => 'Nama lengkap sesuai dokumen resmi',
                        ]) }}
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Panggilan --}}
                    <div class="col-md-4 mb-3">
                        {{ Form::label('nick_name', 'Nama Panggilan', ['class' => 'form-label']) }}
                        {{ Form::text('nick_name', null, [
                            'class' => 'form-control' . ($errors->has('nick_name') ? ' is-invalid' : ''),
                            'id' => 'nick_name',
                            'maxlength' => 255,
                        ]) }}
                        @error('nick_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Foto --}}
                    <div class="col-md-4 mb-3">
                        {{ Form::label('photo', 'Foto', ['class' => 'form-label']) }}
                        {{ Form::file('photo', [
                            'class' => 'form-control' . ($errors->has('photo') ? ' is-invalid' : ''),
                            'id' => 'photo',
                            'accept' => 'image/*',
                        ]) }}
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if (!empty($employee->photo_path))
                            <div class="mt-2">
                                <img src="{{ Storage::url($employee->photo_path) }}" alt="Foto" class="img-thumbnail"
                                    width="100">
                            </div>
                        @endif
                    </div>

                    <!-- ================= Penempatan ================= -->
                    <!-- ================= Penempatan ================= -->
                    <div class="col-12 mt-2">
                        <h6 class="border-bottom pb-2 mb-3">Penempatan</h6>
                    </div>

                    <div class="col-md-4 mb-3">
                        {{ Form::label('department_id', 'Departemen', ['class' => 'form-label']) }}
                        {{ Form::select('department_id', $departments, null, [
                            'class' => 'form-select' . ($errors->has('department_id') ? ' is-invalid' : ''),
                            'id' => 'department_id',
                            'placeholder' => 'Pilih Departemen',
                        ]) }}
                        @error('department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        {{ Form::label('position_id', 'Jabatan', ['class' => 'form-label']) }}
                        {{ Form::select('position_id', $positions, null, [
                            'class' => 'form-select' . ($errors->has('position_id') ? ' is-invalid' : ''),
                            'id' => 'position_id',
                            'placeholder' => 'Pilih Jabatan',
                        ]) }}
                        @error('position_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        {{ Form::label('supervisor_id', 'Atasan Langsung', ['class' => 'form-label']) }}
                        {{ Form::select('supervisor_id', $supervisors, null, [
                            'class' => 'form-select' . ($errors->has('supervisor_id') ? ' is-invalid' : ''),
                            'id' => 'supervisor_id',
                            'placeholder' => 'Pilih Atasan',
                        ]) }}
                        @error('supervisor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ================= Data Pribadi ================= -->
                    <!-- ================= Data Pribadi ================= -->
                    <div class="col-12 mt-2">
                        <h6 class="border-bottom pb-2 mb-3">Data Pribadi</h6>
                    </div>

                    <div class="col-md-3 mb-3">
                        {{ Form::label('gender', 'Jenis Kelamin', ['class' => 'form-label']) }}
                        {{ Form::select('gender', ['L' => 'Laki-laki', 'P' => 'Perempuan'], null, [
                            'class' => 'form-select' . ($errors->has('gender') ? ' is-invalid' : ''),
                            'id' => 'gender',
                            'placeholder' => 'Pilih',
                        ]) }}
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        {{ Form::label('birth_place', 'Tempat Lahir', ['class' => 'form-label']) }}
                        {{ Form::text('birth_place', null, [
                            'class' => 'form-control' . ($errors->has('birth_place') ? ' is-invalid' : ''),
                            'id' => 'birth_place',
                            'maxlength' => 255,
                        ]) }}
                        @error('birth_place')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        {{ Form::label('birth_date', 'Tanggal Lahir', ['class' => 'form-label']) }}
                        {{ Form::date('birth_date', null, [
                            'class' => 'form-control' . ($errors->has('birth_date') ? ' is-invalid' : ''),
                            'id' => 'birth_date',
                        ]) }}
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        {{ Form::label('phone', 'No. Telepon', ['class' => 'form-label']) }}
                        {{ Form::text('phone', null, [
                            'class' => 'form-control' . ($errors->has('phone') ? ' is-invalid' : ''),
                            'id' => 'phone',
                            'maxlength' => 20,
                            'placeholder' => 'Contoh: 081234567890',
                        ]) }}
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        {{ Form::label('email', 'Email', ['class' => 'form-label']) }}
                        {{ Form::email('email', null, [
                            'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''),
                            'id' => 'email',
                            'maxlength' => 255,
                            'placeholder' => 'email@contoh.com',
                        ]) }}
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        {{ Form::label('address', 'Alamat', ['class' => 'form-label']) }}
                        {{ Form::textarea('address', null, [
                            'class' => 'form-control' . ($errors->has('address') ? ' is-invalid' : ''),
                            'id' => 'address',
                            'rows' => 2,
                        ]) }}
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ================= Status Kepegawaian ================= -->
                    <div class="col-12 mt-2">
                        <h6 class="border-bottom pb-2 mb-3">Status Kepegawaian</h6>
                    </div>

                    <div class="col-md-3 mb-3">
                        {{ Form::label('join_date', 'Tanggal Bergabung', ['class' => 'form-label']) }}
                        <span class="text-danger">*</span>
                        {{ Form::date('join_date', null, [
                            'class' => 'form-control' . ($errors->has('join_date') ? ' is-invalid' : ''),
                            'id' => 'join_date',
                            'required' => true,
                        ]) }}
                        @error('join_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        {{ Form::label('resign_date', 'Tanggal Resign', ['class' => 'form-label']) }}
                        {{ Form::date('resign_date', null, [
                            'class' => 'form-control' . ($errors->has('resign_date') ? ' is-invalid' : ''),
                            'id' => 'resign_date',
                        ]) }}
                        @error('resign_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- employment_status_id (lookup table) --}}
                    <div class="col-md-3 mb-3">
                        {{ Form::label('employment_status_id', 'Status Karyawan', ['class' => 'form-label']) }}
                        <span class="text-danger">*</span>
                        {{ Form::select('employment_status_id', $employmentStatuses, null, [
                            'class' => 'form-select' . ($errors->has('employment_status_id') ? ' is-invalid' : ''),
                            'id' => 'employment_status_id',
                            'placeholder' => 'Pilih Status',
                            'required' => true,
                        ]) }}
                        @error('employment_status_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Salary Structure --}}
                    <div class="col-md-3 mb-3">
                        {{ Form::label('salary_structure_id', 'Struktur Gaji', ['class' => 'form-label']) }}
                        {{ Form::select('salary_structure_id', $salaryStructures, null, [
                            'class' => 'form-select' . ($errors->has('salary_structure_id') ? ' is-invalid' : ''),
                            'id' => 'salary_structure_id',
                            'placeholder' => 'Pilih Struktur Gaji',
                        ]) }}
                        @error('salary_structure_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- is_active --}}
                    <div class="col-md-3 mb-3">
                        <div class="form-check form-switch mt-4">
                            {{ Form::hidden('is_active', 0) }}
                            {{ Form::checkbox('is_active', 1, null, [
                                'class' => 'form-check-input' . ($errors->has('is_active') ? ' is-invalid' : ''),
                                'id' => 'is_active',
                            ]) }}
                            {{ Form::label('is_active', 'Aktif', ['class' => 'form-check-label']) }}
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- ================= Data Pajak & BPJS ================= -->
                    <div class="col-12 mt-2">
                        <h6 class="border-bottom pb-2 mb-3">Data Pajak & BPJS</h6>
                    </div>

                    <div class="col-md-4 mb-3">
                        {{ Form::label('npwp', 'NPWP', ['class' => 'form-label']) }}
                        {{ Form::text('npwp', null, [
                            'class' => 'form-control' . ($errors->has('npwp') ? ' is-invalid' : ''),
                            'id' => 'npwp',
                            'maxlength' => 50,
                        ]) }}
                        @error('npwp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        {{ Form::label('bpjs_ketenagakerjaan', 'BPJS Ketenagakerjaan', ['class' => 'form-label']) }}
                        {{ Form::text('bpjs_ketenagakerjaan', null, [
                            'class' => 'form-control' . ($errors->has('bpjs_ketenagakerjaan') ? ' is-invalid' : ''),
                            'id' => 'bpjs_ketenagakerjaan',
                            'maxlength' => 50,
                        ]) }}
                        @error('bpjs_ketenagakerjaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        {{ Form::label('bpjs_kesehatan', 'BPJS Kesehatan', ['class' => 'form-label']) }}
                        {{ Form::text('bpjs_kesehatan', null, [
                            'class' => 'form-control' . ($errors->has('bpjs_kesehatan') ? ' is-invalid' : ''),
                            'id' => 'bpjs_kesehatan',
                            'maxlength' => 50,
                        ]) }}
                        @error('bpjs_kesehatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ================= Data Bank (Multiple) ================= -->
                    <div class="col-12 mt-2">
                        <h6 class="border-bottom pb-2 mb-3">Data Bank</h6>
                    </div>

                    <div class="col-12 mb-3" id="bank-accounts-container">
                        @php
                            $bankAccounts = old(
                                'bank_accounts',
                                isset($employee->bankAccounts) && $employee->bankAccounts->count()
                                    ? $employee->bankAccounts
                                        ->map(function ($b) {
                                            return [
                                                'id' => $b->id,
                                                'bank_name' => $b->bank_name,
                                                'account_number' => $b->account_number,
                                                'account_name' => $b->account_name,
                                                'is_primary' => $b->is_primary,
                                            ];
                                        })
                                        ->toArray()
                                    : [
                                        [
                                            'bank_name' => '',
                                            'account_number' => '',
                                            'account_name' => '',
                                            'is_primary' => true,
                                        ],
                                    ],
                            );
                        @endphp

                        @foreach ($bankAccounts as $i => $bank)
                            <div class="row bank-account-row align-items-end mb-2" data-index="{{ $i }}">
                                {{ Form::hidden("bank_accounts[{$i}][id]", $bank['id'] ?? '') }}

                                <div class="col-md-3">
                                    {{ Form::label("bank_accounts[{$i}][bank_name]", 'Nama Bank', ['class' => 'form-label']) }}
                                    {{ Form::text("bank_accounts[{$i}][bank_name]", $bank['bank_name'] ?? '', [
                                        'class' => 'form-control',
                                        'maxlength' => 100,
                                    ]) }}
                                </div>
                                <div class="col-md-3">
                                    {{ Form::label("bank_accounts[{$i}][account_number]", 'No. Rekening', ['class' => 'form-label']) }}
                                    {{ Form::text("bank_accounts[{$i}][account_number]", $bank['account_number'] ?? '', [
                                        'class' => 'form-control',
                                        'maxlength' => 255,
                                    ]) }}
                                </div>
                                <div class="col-md-3">
                                    {{ Form::label("bank_accounts[{$i}][account_name]", 'Nama Pemilik', ['class' => 'form-label']) }}
                                    {{ Form::text("bank_accounts[{$i}][account_name]", $bank['account_name'] ?? '', [
                                        'class' => 'form-control bank-account-name',
                                        'maxlength' => 255,
                                    ]) }}
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check mt-4">
                                        {{ Form::checkbox("bank_accounts[{$i}][is_primary]", 1, !empty($bank['is_primary']), [
                                            'class' => 'form-check-input bank-primary-checkbox',
                                        ]) }}
                                        {{ Form::label("bank_accounts[{$i}][is_primary]", 'Utama', ['class' => 'form-check-label']) }}
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-bank-row mb-1"
                                        {{ count($bankAccounts) === 1 ? 'disabled' : '' }}>
                                        &times;
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="col-12 mb-3">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-bank-row">
                            + Tambah Rekening
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('employee.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        {{ isset($employee->id) ? 'Update' : 'Simpan' }}
                    </button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-fill nama pemilik rekening dari nama karyawan jika kosong
        document.getElementById('name').addEventListener('change', function() {
            document.querySelectorAll('.bank-account-name').forEach(function(input) {
                if (!input.value) input.value = this.value;
            });
        });

        // Validasi resign >= join
        document.getElementById('resign_date').addEventListener('change', function() {
            var joinDate = document.getElementById('join_date').value;
            var resignDate = this.value;
            if (joinDate && resignDate && resignDate < joinDate) {
                alert('Tanggal resign harus setelah tanggal bergabung!');
                this.value = '';
            }
        });

        // ============ Dynamic bank rows ============
        let bankIndex = {{ count($bankAccounts) }};

        document.getElementById('add-bank-row').addEventListener('click', function() {
            const container = document.getElementById('bank-accounts-container');
            const html = `
                <div class="row bank-account-row align-items-end mb-2" data-index="${bankIndex}">
                    <input type="hidden" name="bank_accounts[${bankIndex}][id]" value="">
                    <div class="col-md-3">
                        <label class="form-label">Nama Bank</label>
                        <input type="text" name="bank_accounts[${bankIndex}][bank_name]" class="form-control" maxlength="100">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">No. Rekening</label>
                        <input type="text" name="bank_accounts[${bankIndex}][account_number]" class="form-control" maxlength="255">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nama Pemilik</label>
                        <input type="text" name="bank_accounts[${bankIndex}][account_name]" class="form-control bank-account-name" maxlength="255">
                    </div>
                    <div class="col-md-2">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="bank_accounts[${bankIndex}][is_primary]" class="form-check-input bank-primary-checkbox" value="1">
                            <label class="form-check-label">Utama</label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-bank-row mb-1">&times;</button>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            bankIndex++;
        });

        // Hapus baris
        document.getElementById('bank-accounts-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-bank-row')) {
                const rows = this.querySelectorAll('.bank-account-row');
                if (rows.length > 1) {
                    e.target.closest('.bank-account-row').remove();
                }
            }
        });

        // Hanya satu yang boleh primary
        document.getElementById('bank-accounts-container').addEventListener('change', function(e) {
            if (e.target.classList.contains('bank-primary-checkbox') && e.target.checked) {
                this.querySelectorAll('.bank-primary-checkbox').forEach(function(cb) {
                    if (cb !== e.target) cb.checked = false;
                });
            }
        });
    </script>
@endpush
