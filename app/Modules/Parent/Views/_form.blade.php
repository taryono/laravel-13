{{-- Informasi Dasar Orang Tua --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Informasi Orang Tua</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $parent->name ?? '') }}" required maxlength="150">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                    value="{{ old('phone', $parent->phone ?? '') }}" required maxlength="20">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $parent->email ?? '') }}" maxlength="100">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Pekerjaan</label>
                <input type="text" name="work" class="form-control @error('work') is-invalid @enderror"
                    value="{{ old('work', $parent->work ?? '') }}" maxlength="100">
                @error('work')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $parent->address ?? '') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

{{-- Hubungan dengan Siswa --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Hubungan dengan Siswa</h5>
        <button type="button" id="add-student-row" class="btn btn-sm btn-primary">+ Tambah Siswa</button>
    </div>
    <div class="card-body">
        <div id="student-rows">
            {{-- Baris dinamis akan dimuat di sini --}}
            @php
                $oldStudents = old('student_ids', $attachedIds ?? []);
                $oldRels = old('relationships', []);
                $oldPrimary = old('is_primary', []);
            @endphp

            @if (!empty($oldStudents))
                @foreach ($oldStudents as $idx => $sid)
                    <div class="row student-row border rounded p-3 mb-2 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">Siswa</label>
                            <select name="student_ids[]" class="form-select" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach ($students as $s)
                                    <option value="{{ $s->id }}" {{ $sid == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Hubungan</label>
                            <select name="relationships[]" class="form-select">
                                <option value="">-- Pilih --</option>
                                <option value="FATHER" {{ ($oldRels[$idx] ?? '') === 'FATHER' ? 'selected' : '' }}>Ayah
                                </option>
                                <option value="MOTHER" {{ ($oldRels[$idx] ?? '') === 'MOTHER' ? 'selected' : '' }}>Ibu
                                </option>
                                <option value="GUARDIAN" {{ ($oldRels[$idx] ?? '') === 'GUARDIAN' ? 'selected' : '' }}>
                                    Wali</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="form-check mt-1">
                                <input type="checkbox" name="is_primary[{{ $idx }}]" class="form-check-input"
                                    id="p-{{ $idx }}" {{ !empty($oldPrimary[$idx]) ? 'checked' : '' }}
                                    value="1">
                                <label class="form-check-label" for="p-{{ $idx }}">Orang Tua Utama</label>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-sm btn-danger remove-row">×</button>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted">Belum ada siswa. Klik "Tambah Siswa" untuk menautkan.</p>
            @endif
        </div>
        <small class="text-muted">
            Catatan: Jika ditandai "Utama", akan otomatis melepas status utama dari orang tua lain untuk siswa tersebut.
        </small>
    </div>
</div>

<div class="d-flex gap-2">
    <a href="{{ route('parents.index') }}" class="btn btn-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan Data</button>
</div>

{{-- Template Baris Baru --}}
<template id="student-row-template">
    <div class="row student-row border rounded p-3 mb-2 align-items-center">
        <div class="col-md-4">
            <label class="form-label">Siswa</label>
            <select name="student_ids[]" class="form-select" required>
                <option value="">-- Pilih Siswa --</option>
                @foreach ($students as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Hubungan</label>
            <select name="relationships[]" class="form-select">
                <option value="">-- Pilih --</option>
                <option value="FATHER">Ayah</option>
                <option value="MOTHER">Ibu</option>
                <option value="GUARDIAN">Wali</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">&nbsp;</label>
            <div class="form-check mt-1">
                <input type="checkbox" name="is_primary[__IDX__]" class="form-check-input" id="p-__IDX__"
                    value="1">
                <label class="form-check-label" for="p-__IDX__">Orang Tua Utama</label>
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-sm btn-danger remove-row">×</button>
        </div>
    </div>
</template>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('student-rows');
            const template = document.getElementById('student-row-template');
            const addBtn = document.getElementById('add-student-row');
            let rowCount = {{ !empty($oldStudents) ? count($oldStudents) : 0 }};

            // Tambah baris baru
            addBtn.addEventListener('click', function() {
                const html = template.innerHTML.replaceAll('__IDX__', rowCount);
                container.insertAdjacentHTML('beforeend', html);
                rowCount++;
            });

            // Hapus baris
            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row')) {
                    e.target.closest('.student-row').remove();
                }
            });

            // Pastikan hanya satu "Utama" per siswa
            container.addEventListener('change', function(e) {
                if (e.target.matches('input[type="checkbox"]')) {
                    if (e.target.checked) {
                        // Opsional: Batasi hanya satu yang dicentang di seluruh form
                        // Sisanya biarkan dibatasi di backend
                    }
                }
            });
        });
    </script>
@endpush
