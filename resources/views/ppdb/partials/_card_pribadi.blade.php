{{-- resources/views/ppdb/partials/_card_pribadi.blade.php --}}
<article class="ppdb-card mb-4">
    <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom"
        style="border-color:var(--bs-outline-variant) !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="card-number">1</div>
            <div>
                <h2 class="mb-0" style="font-size:1.1rem; font-weight:600;">Data Pribadi Calon Siswa</h2>
                <p class="mb-0" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Kesesuaian NIK dan NISN
                    terintegrasi langsung dengan Dapodik</p>
            </div>
        </div>
        <span class="material-symbols-outlined" style="color:var(--bs-primary);">badge</span>
    </div>

    <div class="d-flex flex-column gap-3">
        <div class="row g-3">
            {{-- NIK --}}
            <div class="col-12 col-sm-6">
                <label class="form-label" for="nik">NIK <span style="color:var(--bs-error);">*</span></label>
                <div class="position-relative">
                    <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik"
                        name="nik" maxlength="16" placeholder="16 digit sesuai Kartu Keluarga"
                        value="{{ old('nik', $applicant->nik ?? '') }}" required>
                    @if ($applicant && $applicant->nik)
                        <span class="material-symbols-outlined position-absolute end-0 top-50 translate-middle-y me-3"
                            style="color:var(--bs-success); font-size:1.1rem;">check_circle</span>
                    @endif
                </div>
                @error('nik')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <p class="mt-1 mb-0 d-flex align-items-center gap-1"
                    style="font-size:0.7rem; color:var(--bs-success-dark);">
                    <span class="material-symbols-outlined" style="font-size:0.85rem;">verified_user</span>
                    NIK Terverifikasi Dukcapil
                </p>
            </div>

            {{-- NISN --}}
            <div class="col-12 col-sm-6">
                <label class="form-label" for="nisn">NISN <span style="color:var(--bs-error);">*</span></label>
                <input type="text" class="form-control @error('nisn') is-invalid @enderror" id="nisn"
                    name="nisn" maxlength="10" placeholder="10 digit nomor NISN aktif"
                    value="{{ old('nisn', $applicant->nisn ?? '') }}" required>
                @error('nisn')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Nama --}}
        <div>
            <label class="form-label" for="name">Nama Lengkap (Sesuai Akta) <span
                    style="color:var(--bs-error);">*</span></label>
            <input type="text" class="form-control text-uppercase @error('name') is-invalid @enderror" id="name"
                name="name" placeholder="Contoh: Muhammad Farhan Al-Hafiz"
                value="{{ old('name', $applicant->name ?? '') }}" required>
            @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tempat & Tgl Lahir --}}
        <div class="row g-3">
            <div class="col-12 col-sm-6">
                <label class="form-label" for="birth_place">Tempat Lahir <span
                        style="color:var(--bs-error);">*</span></label>
                <input type="text" class="form-control @error('birth_place') is-invalid @enderror" id="birth_place"
                    name="birth_place" value="{{ old('birth_place', $applicant->birth_place ?? '') }}" required>
                @error('birth_place')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label" for="birth_date">Tanggal Lahir <span
                        style="color:var(--bs-error);">*</span></label>
                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date"
                    name="birth_date"
                    value="{{ old('birth_date', optional($applicant->birth_date ?? null)->format('Y-m-d')) }}" required>
                @error('birth_date')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Gender --}}
        <div>
            <span class="form-label d-block mb-2">Jenis Kelamin <span style="color:var(--bs-error);">*</span></span>
            <div class="row g-2">
                @php $gender = old('gender', $applicant->gender ?? 'L'); @endphp
                <div class="col-6">
                    <label class="jalur-chip {{ $gender === 'L' ? 'active' : '' }} w-100 align-items-center"
                        style="flex-direction:row; justify-content:center;">
                        <input type="radio" name="gender" value="L" {{ $gender === 'L' ? 'checked' : '' }}>
                        <span style="font-size:0.75rem; font-weight:600;">Laki-Laki</span>
                    </label>
                </div>
                <div class="col-6">
                    <label class="jalur-chip {{ $gender === 'P' ? 'active' : '' }} w-100 align-items-center"
                        style="flex-direction:row; justify-content:center;">
                        <input type="radio" name="gender" value="P" {{ $gender === 'P' ? 'checked' : '' }}>
                        <span style="font-size:0.75rem; font-weight:600;">Perempuan</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Asal Sekolah --}}
        <div>
            <label class="form-label" for="previous_school">Asal Sekolah (SMP / MTs) <span
                    style="color:var(--bs-error);">*</span></label>
            <input type="text" class="form-control @error('previous_school') is-invalid @enderror"
                id="previous_school" name="previous_school" placeholder="Cari nama SMP / NPSN asal"
                value="{{ old('previous_school', $applicant->previous_school ?? '') }}" required>
            @error('previous_school')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        {{-- Alamat --}}
        <div>
            <label class="form-label" for="address">Alamat Domisili Sesuai KK <span
                    style="color:var(--bs-error);">*</span></label>
            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2"
                placeholder="Nama Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan" required>{{ old('address', $applicant->address ?? '') }}</textarea>
            @error('address')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        {{-- Geo Pin --}}
        <div class="geo-box">
            <div class="d-flex align-items-center gap-2">
                <span class="material-symbols-outlined" style="color:var(--bs-secondary);">pin_drop</span>
                <div>
                    <div style="font-size:0.7rem; font-weight:600;">Titik Koordinat Rumah Calon Siswa</div>
                    <div class="font-monospace" id="geo-display"
                        style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                        @if ($applicant && $applicant->domicile?->latitude)
                            {{ $applicant->domicile->latitude }}, {{ $applicant->domicile->longitude }}
                        @else
                            Belum ada titik koordinat
                        @endif
                    </div>
                </div>
            </div>
            <button type="button" class="btn-sm-custom" onclick="detectLocation()">
                <span class="material-symbols-outlined" style="font-size:0.9rem;">my_location</span>
                Perbarui Titik Peta
            </button>
        </div>

        {{-- Phone & Email --}}
        <div class="row g-3">
            <div class="col-12 col-sm-6">
                <label class="form-label" for="phone">Nomor HP Siswa</label>
                <input type="tel" class="form-control" id="phone" name="phone"
                    value="{{ old('phone', $applicant->phone ?? '') }}">
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label" for="email">Email Siswa</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="{{ old('email', $applicant->email ?? '') }}">
            </div>
        </div>
    </div>
</article>
