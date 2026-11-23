{{-- Hidden fields --}}
<input type="hidden" name="school_id" value="{{ old('school_id', $assessment->school_id ?? auth()->user()->school_id) }}">
<div class="row g-3">
    {{-- Kolom Kiri --}}
    <div class="col-md-6">
        {{-- Curriculum Structure --}}
        <div class="mb-3">
            <label for="curriculum_structure_id" class="form-label fw-semibold">Struktur Kurikulum <span
                    class="text-danger">*</span></label>
            <select name="curriculum_structure_id" id="curriculum_structure_id"
                class="form-select @error('curriculum_structure_id') is-invalid @enderror" required>
                <option value="">-- Pilih --</option>
                {{-- Isi dari controller: $curriculumStructures --}}
                @foreach ($curriculumStructures ?? [] as $item)
                    <option value="{{ $item->id }}"
                        {{ old('curriculum_structure_id', $assessment->curriculum_structure_id ?? '') == $item->id ? 'selected' : '' }}>
                        {{ $item->name ?? ($item->title ?? $item->id) }}
                    </option>
                @endforeach
            </select>
            @error('curriculum_structure_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Assessment Type --}}
        <div class="mb-3">
            <label for="assessment_type_id" class="form-label fw-semibold">Jenis Penilaian <span
                    class="text-danger">*</span></label>
            <select name="assessment_type_id" id="assessment_type_id"
                class="form-select @error('assessment_type_id') is-invalid @enderror" required>
                <option value="">-- Pilih --</option>
                @foreach ($assessmentTypes ?? [] as $item)
                    <option value="{{ $item->id }}"
                        {{ old('assessment_type_id', $assessment->assessment_type_id ?? '') == $item->id ? 'selected' : '' }}>
                        {{ $item->name ?? $item->id }}
                    </option>
                @endforeach
            </select>
            @error('assessment_type_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Assessment Method --}}
        <div class="mb-3">
            <label for="assessment_method_id" class="form-label fw-semibold">Metode Penilaian</label>
            <select name="assessment_method_id" id="assessment_method_id"
                class="form-select @error('assessment_method_id') is-invalid @enderror">
                <option value="">-- Pilih (Opsional) --</option>
                @foreach ($assessmentMethods ?? [] as $item)
                    <option value="{{ $item->id }}"
                        {{ old('assessment_method_id', $assessment->assessment_method_id ?? '') == $item->id ? 'selected' : '' }}>
                        {{ $item->name ?? $item->id }}
                    </option>
                @endforeach
            </select>
            @error('assessment_method_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Assessment Rubric --}}
        <div class="mb-3">
            <label for="assessment_rubric_id" class="form-label fw-semibold">Rubrik Penilaian</label>
            <select name="assessment_rubric_id" id="assessment_rubric_id"
                class="form-select @error('assessment_rubric_id') is-invalid @enderror">
                <option value="">-- Pilih (Opsional) --</option>
                @foreach ($assessmentRubrics ?? [] as $item)
                    <option value="{{ $item->id }}"
                        {{ old('assessment_rubric_id', $assessment->assessment_rubric_id ?? '') == $item->id ? 'selected' : '' }}>
                        {{ $item->name ?? $item->id }}
                    </option>
                @endforeach
            </select>
            @error('assessment_rubric_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Academic Year --}}
        <div class="mb-3">
            <label for="academic_year_id" class="form-label fw-semibold">Tahun Akademik <span
                    class="text-danger">*</span></label>
            <select name="academic_year_id" id="academic_year_id"
                class="form-select @error('academic_year_id') is-invalid @enderror" required>
                <option value="">-- Pilih --</option>
                @foreach ($academicYears ?? [] as $item)
                    <option value="{{ $item->id }}"
                        {{ old('academic_year_id', $assessment->academic_year_id ?? '') == $item->id ? 'selected' : '' }}>
                        {{ $item->name ?? ($item->year ?? $item->id) }}
                    </option>
                @endforeach
            </select>
            @error('academic_year_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Semester --}}
        <div class="mb-3">
            <label for="semester" class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
            <select name="semester" id="semester" class="form-select @error('semester') is-invalid @enderror" required>
                <option value="1" {{ old('semester', $assessment->semester ?? 1) == 1 ? 'selected' : '' }}>1 -
                    Ganjil</option>
                <option value="2" {{ old('semester', $assessment->semester ?? 1) == 2 ? 'selected' : '' }}>2 -
                    Genap</option>
            </select>
            @error('semester')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Kolom Kanan --}}
    <div class="col-md-6">
        {{-- Nama --}}
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Nama Penilaian</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $assessment->name ?? '') }}" maxlength="255">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Judul --}}
        <div class="mb-3">
            <label for="title" class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title"
                class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $assessment->title ?? '') }}" maxlength="150" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kode --}}
        <div class="mb-3">
            <label for="code" class="form-label fw-semibold">Kode</label>
            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror"
                value="{{ old('code', $assessment->code ?? '') }}" maxlength="30">
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tanggal Penilaian --}}
        <div class="mb-3">
            <label for="assessment_date" class="form-label fw-semibold">Tanggal Penilaian</label>
            <input type="date" name="assessment_date" id="assessment_date"
                class="form-control @error('assessment_date') is-invalid @enderror"
                value="{{ old('assessment_date', $assessment->assessment_date ?? '') }}">
            @error('assessment_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tanggal Tenggat --}}
        <div class="mb-3">
            <label for="due_date" class="form-label fw-semibold">Tanggal Tenggat</label>
            <input type="date" name="due_date" id="due_date"
                class="form-control @error('due_date') is-invalid @enderror"
                value="{{ old('due_date', $assessment->due_date ?? '') }}">
            @error('due_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Skor Maksimal & Bobot --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="max_score" class="form-label fw-semibold">Skor Maksimal <span
                        class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" name="max_score" id="max_score"
                    class="form-control @error('max_score') is-invalid @enderror"
                    value="{{ old('max_score', $assessment->max_score ?? '100.00') }}" required>
                @error('max_score')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="weight" class="form-label fw-semibold">Bobot (%) <span
                        class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" max="100" name="weight" id="weight"
                    class="form-control @error('weight') is-invalid @enderror"
                    value="{{ old('weight', $assessment->weight ?? '0.00') }}" required>
                @error('weight')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    {{-- Deskripsi (Full Width) --}}
    <div class="col-12">
        <div class="mb-3">
            <label for="description" class="form-label fw-semibold">Deskripsi</label>
            <textarea name="description" id="description" rows="4"
                class="form-control @error('description') is-invalid @enderror">{{ old('description', $assessment->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Status --}}
    <div class="col-md-6">
        <div class="form-check form-switch mb-3">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" id="is_published" class="form-check-input" value="1"
                {{ old('is_published', $assessment->is_published ?? 0) ? 'checked' : '' }}>
            <label for="is_published" class="form-check-label">Dipublikasikan</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-check form-switch mb-3">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                {{ old('is_active', $assessment->is_active ?? 1) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Aktif</label>
        </div>
    </div>
</div>
