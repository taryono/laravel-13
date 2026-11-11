{{-- resources/views/ppdb/partials/_card_jalur.blade.php --}}
@php
    $queryPathId = request()->filled('path')
        ? $admission->paths->firstWhere('code', request('path'))?->id
        : null;
    $selectedPathId = old('admission_path_id', $applicant->admission_path_id ?? $queryPathId);
    $choices = old(
        'choices',
        $applicant?->choices->map(fn($c) => ['study_program_id' => $c->study_program_id])->toArray() ?? [],
    );
@endphp
<article class="ppdb-card mb-4">
    <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom"
        style="border-color:var(--bs-outline-variant) !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="card-number">2</div>
            <div>
                <h2 class="mb-0" style="font-size:1.1rem; font-weight:600;">Pilihan Jalur &amp; Sekolah Tujuan</h2>
                <p class="mb-0" style="font-size:0.7rem; color:var(--bs-on-surface-variant);">Pilih jalur yang sesuai
                    dengan kriteria syarat kelulusan pendaftar</p>
            </div>
        </div>
        <span class="material-symbols-outlined" style="color:var(--bs-primary);">account_balance</span>
    </div>

    <div class="d-flex flex-column gap-4">
        {{-- Jalur Chips (dari DB) --}}
        <div>
            <span class="form-label d-block mb-2">Pilih Jalur Pendaftaran <span
                    style="color:var(--bs-error);">*</span></span>
            <div class="row g-2">
                @foreach ($admission->paths as $path)
                    <div class="col-6 col-sm-3">
                        <label class="jalur-chip {{ $selectedPathId == $path->id ? 'active' : '' }} w-100"
                            onclick="document.getElementById('path_{{ $path->id }}').click()">
                            <input type="radio" name="admission_path_id_radio" id="path_{{ $path->id }}"
                                value="{{ $path->id }}" {{ $selectedPathId == $path->id ? 'checked' : '' }}>
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <span class="material-symbols-outlined"
                                    style="color:var(--bs-primary); font-size:1.1rem;">{{ $path->icon ?? 'share_location' }}</span>
                                <span class="rounded-circle"
                                    style="width:0.5rem;height:0.5rem;background:var(--bs-primary);"></span>
                            </div>
                            <span class="jalur-label" style="font-size:0.75rem;">{{ $path->name }}</span>
                            <span style="font-size:0.65rem; color:var(--bs-on-surface-variant);">Kuota
                                {{ $path->quota }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pilihan Sekolah --}}
        @for ($i = 0; $i < 2; $i++)
            <div class="p-3 rounded-3"
                style="background:var(--bs-surface-container-low); border:1px solid var(--bs-outline-variant);">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                    <span class="d-flex align-items-center gap-2 fw-bold"
                        style="font-size:0.8rem; {{ $i === 0 ? 'color:var(--bs-primary);' : '' }}">
                        <span
                            class="rounded-circle d-flex align-items-center justify-content-center {{ $i === 0 ? 'text-white' : '' }}"
                            style="width:1.25rem;height:1.25rem;background:{{ $i === 0 ? 'var(--bs-primary)' : 'var(--bs-outline-variant)' }}; font-size:0.7rem;">{{ $i + 1 }}</span>
                        Sekolah Pilihan {{ $i === 0 ? 'Pertama (Prioritas Utama)' : 'Kedua (Cadangan)' }}
                    </span>
                </div>
                <div class="row g-2">
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Pilih Sekolah Negeri</label>
                        <select class="form-select school-select" data-choice="{{ $i }}"
                            onchange="loadPrograms(this, {{ $i }})">
                            <option value="">-- Pilih Sekolah --</option>
                            @foreach (\Models\School::whereHas('admissions', fn($q) => $q->where('id', $admission->id))->get() as $school)
                                <option value="{{ $school->id }}"
                                    {{ old("choices.$i.school_id", $applicant?->choices->get($i)?->study_program?->school_id ?? '') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Peminatan / Konsentrasi</label>
                        <select class="form-select program-select"
                            name="choices[{{ $i }}][study_program_id]" data-choice="{{ $i }}">
                            <option value="">-- Pilih Program --</option>
                            @if ($applicant && isset($choices[$i]['study_program_id']))
                                @php $selectedProgram = \Models\StudyProgram::find($choices[$i]['study_program_id']); @endphp
                                @if ($selectedProgram)
                                    <option value="{{ $selectedProgram->id }}" selected>{{ $selectedProgram->name }}
                                    </option>
                                @endif
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</article>
