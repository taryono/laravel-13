{{-- resources/views/ppdb/partials/_card_ortu.blade.php --}}
@php
    $father = $applicant?->parents->where('relation_type', 'father')->first();
    $mother = $applicant?->parents->where('relation_type', 'mother')->first();
    $guardian = $applicant?->parents->where('relation_type', 'guardian')->first();
@endphp

<article class="ppdb-card mb-4">
    <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom"
        style="border-color:var(--bs-outline-variant) !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="card-number">4</div>
            <div>
                <h2 class="mb-0" style="font-size:1.1rem; font-weight:600;">Informasi Orang Tua &amp; Wali Siswa</h2>
                <p class="mb-0" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Kontak darurat dan
                    penerima notifikasi kelulusan resmi</p>
            </div>
        </div>
        <span class="material-symbols-outlined" style="color:var(--bs-primary);">family_restroom</span>
    </div>

    <div class="d-flex flex-column gap-3">
        <div class="row g-3">
            <div class="col-12 col-sm-6">
                <label class="form-label" for="parent_name">Nama Lengkap Ayah / Ibu <span
                        style="color:var(--bs-error);">*</span></label>
                <input type="text" class="form-control @error('parent_name') is-invalid @enderror" id="parent_name"
                    name="parent_name" value="{{ old('parent_name', $applicant->parent_name ?? ($father->name ?? '')) }}"
                    required>
                @error('parent_name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label" for="parent_nik">NIK Orang Tua</label>
                <input type="text" class="form-control" id="parent_nik" name="parent_nik" maxlength="16"
                    value="{{ old('parent_nik', $father->nik ?? '') }}">
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-sm-4">
                <label class="form-label">Pendidikan Terakhir</label>
                <select class="form-select" name="parent_education">
                    @foreach (['SMA / Sederajat', 'D3 / Diploma', 'S1 / Sarjana', 'S2 / Magister'] as $edu)
                        <option value="{{ $edu }}"
                            {{ old('parent_education', $father->education_level ?? '') === $edu ? 'selected' : '' }}>
                            {{ $edu }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-4">
                <label class="form-label">Pekerjaan Orang Tua</label>
                <select class="form-select" name="parent_job">
                    @foreach (['PNS / TNI / POLRI', 'Karyawan Swasta', 'Wiraswasta / Usaha', 'Lainnya'] as $job)
                        <option value="{{ $job }}"
                            {{ old('parent_job', $father->job ?? '') === $job ? 'selected' : '' }}>{{ $job }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-4">
                <label class="form-label">Rentang Penghasilan</label>
                <select class="form-select" name="parent_income">
                    <option value="below_1m">&lt; Rp 2.500.000</option>
                    <option value="1m_2m">Rp 2.500.000 - Rp 5.000.000</option>
                    <option value="2m_5m" selected>Rp 5.000.000 - Rp 10.000.000</option>
                    <option value="above_10m">&gt; Rp 10.000.000</option>
                </select>
            </div>
        </div>

        <div>
            <label class="form-label" for="parent_phone">Nomor WhatsApp Aktif <span
                    style="color:var(--bs-error);">*</span></label>
            <div class="position-relative">
                <span class="position-absolute start-0 top-50 translate-middle-y ms-3 fw-bold"
                    style="font-size:0.8rem; color:var(--bs-on-surface-variant);">+62</span>
                <input type="tel"
                    class="form-control ps-5 font-monospace @error('parent_phone') is-invalid @enderror"
                    id="parent_phone" name="parent_phone"
                    value="{{ old('parent_phone', $applicant->parent_phone ?? '') }}" required
                    style="padding-left:3rem;">
            </div>
            @error('parent_phone')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <p class="mt-1 mb-0" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">
                Nomor ini akan menerima token verifikasi PIN pengumuman dan tautan unduh bukti tanda terima.
            </p>
        </div>

        <div class="pt-2">
            <label class="d-inline-flex align-items-center gap-2" style="cursor:pointer;">
                <input type="checkbox" name="is_guardian" value="1" class="form-check-input mt-0">
                <span style="font-size:0.8rem;">Data pendaftar diwakilkan oleh Wali resmi (bukan orang tua
                    kandung)</span>
            </label>
        </div>
    </div>
</article>
