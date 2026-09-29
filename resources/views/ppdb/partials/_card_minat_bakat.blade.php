{{-- resources/views/ppdb/partials/_card_minat_bakat.blade.php --}}
@php
    $selectedExtras = old('extracurriculars', $applicant?->extracurriculars ?? []);
    $foreignLang = old('foreign_language', $applicant?->foreign_language ?? '');
    $specialSkill = old('special_skill', $applicant?->special_skill ?? '');
@endphp

<article class="ppdb-card mb-4">
    <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom"
        style="border-color:var(--bs-outline-variant) !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="card-number">3</div>
            <div>
                <h2 class="mb-0" style="font-size:1.1rem; font-weight:600;">Minat Bakat &amp; Bahasa Asing Pilihan</h2>
                <p class="mb-0" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Penyesuaian program
                    pembinaan potensi siswa di sekolah penerima</p>
            </div>
        </div>
        <span class="material-symbols-outlined" style="color:var(--bs-primary);">psychology</span>
    </div>

    <div class="d-flex flex-column gap-3">
        {{-- Ekstrakurikuler --}}
        <div>
            <span class="form-label d-block mb-2">Minat Kegiatan Ekstrakurikuler (Boleh pilih hingga 2)</span>
            <div class="row g-2">
                @foreach (['PMR / UKS', 'Robotik & Koding', 'Paskibraka', 'KIR (Karya Ilmiah)'] as $extra)
                    <div class="col-6 col-sm-3">
                        <label class="check-pill {{ in_array($extra, $selectedExtras) ? 'active' : '' }} w-100">
                            <input type="checkbox" name="extracurriculars[]" value="{{ $extra }}"
                                {{ in_array($extra, $selectedExtras) ? 'checked' : '' }} class="form-check-input mt-0">
                            <span>{{ $extra }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Bahasa & Prestasi --}}
        <div class="row g-3">
            <div class="col-12 col-sm-6">
                <label class="form-label" for="foreign_language">Pilihan Bahasa Asing</label>
                <select class="form-select" id="foreign_language" name="foreign_language">
                    @foreach (['Bahasa Jepang', 'Bahasa Jerman', 'Bahasa Perancis', 'Bahasa Mandarin'] as $lang)
                        <option value="{{ $lang }}" {{ $foreignLang === $lang ? 'selected' : '' }}>
                            {{ $lang }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label" for="special_skill">Prestasi Olahraga / Seni (Opsional)</label>
                <input type="text" class="form-control" id="special_skill" name="special_skill"
                    placeholder="Contoh: Juara 1 Taekwondo Tingkat Kota" value="{{ $specialSkill }}">
            </div>
        </div>

        {{-- Kesediaan Tes --}}
        <div class="geo-box align-items-start">
            <input type="checkbox" name="agree_test" value="1" class="form-check-input mt-1" id="agree_test"
                {{ old('agree_test', $applicant->agree_test ?? true) ? 'checked' : '' }}>
            <label for="agree_test" class="mb-0" style="font-size:0.8rem; cursor:pointer;">
                <strong>Verifikasi Kesediaan:</strong> Saya menyatakan bersedia mengikuti Tes Diagnostik Pemetaan
                Akademik dan Minat Bakat yang dijadwalkan oleh Satuan Pendidikan secara daring/luring.
            </label>
        </div>
    </div>
</article>
