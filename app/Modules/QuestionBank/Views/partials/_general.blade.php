{{-- resources/views/admin/question_banks/partials/_general.blade.php --}}
<div class="card mb-3">
    <div class="card-header fw-bold">Data Umum</div>
    <div class="card-body row g-3">

        <div class="col-md-4">
            <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
            <select name="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                <option value="">-- Pilih --</option>
                @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}" @selected(old('subject_id', $question->subject_id ?? '') == $subject->id)>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
            @error('subject_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Tipe Soal <span class="text-danger">*</span></label>
            <select name="question_type_id" class="form-select @error('question_type_id') is-invalid @enderror"
                x-model="typeId" required>
                <option value="">-- Pilih Tipe Soal --</option>
                @foreach ($questionTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
            <small class="text-muted">Mengubah tipe soal akan mengosongkan bagian jawaban di bawah.</small>
            @error('question_type_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Poin <span class="text-danger">*</span></label>
            <input type="number" name="points" min="1" required
                class="form-control @error('points') is-invalid @enderror"
                value="{{ old('points', $question->points ?? 10) }}">
            @error('points')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Tingkat Kesulitan <span class="text-danger">*</span></label>
            <select name="difficulty_level_id" required
                class="form-select @error('difficulty_level_id') is-invalid @enderror">
                <option value="">-- Pilih --</option>
                @foreach ($difficultyLevels as $level)
                    <option value="{{ $level->id }}" @selected(old('difficulty_level_id', $question->difficulty_level_id ?? '') == $level->id)>
                        {{ $level->name }}
                    </option>
                @endforeach
            </select>
            @error('difficulty_level_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Level Kognitif <span class="text-danger">*</span></label>
            <select name="cognitive_level_id" required
                class="form-select @error('cognitive_level_id') is-invalid @enderror">
                <option value="">-- Pilih --</option>
                @foreach ($cognitiveLevels as $level)
                    <option value="{{ $level->id }}" @selected(old('cognitive_level_id', $question->cognitive_level_id ?? '') == $level->id)>
                        {{ $level->name }} @if ($level->code)
                            ({{ $level->code }})
                        @endif
                    </option>
                @endforeach
            </select>
            @error('cognitive_level_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Tag</label>
            <input type="text" name="tags_input" class="form-control @error('tags_input') is-invalid @enderror"
                placeholder="pisahkan dengan koma, contoh: aljabar, kelas-10"
                value="{{ old('tags_input', isset($question->tags) ? implode(', ', $question->tags ?? []) : '') }}">
            @error('tags_input')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="use_stimulus" value="1"
                    x-model="useStimulus">
                <label class="form-check-label">Soal ini bagian dari wacana/stimulus berkelompok</label>
            </div>
        </div>

        <div class="col-md-8" x-show="useStimulus" x-cloak>
            <label class="form-label">Pilih Stimulus</label>
            <select name="stimulus_id" class="form-select">
                <option value="">-- Pilih Stimulus yang Sudah Ada --</option>
                @foreach ($stimuli as $stimulus)
                    <option value="{{ $stimulus->id }}" @selected(old('stimulus_id', $question->stimulus_id ?? '') == $stimulus->id)>
                        {{ $stimulus->title ?? 'Stimulus #' . $stimulus->id }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">
                Belum ada stimulus yang cocok?
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="ajax-modal" data-size="lg"
                    data-method="GET" data-title="Tambah Data Stimulus Baru"
                    data-url="{{ route('question_stimuli.create') }}"
                    data-submit='<button class="btn btn-primary" type="submit" form="formQuestionStimuli">Simpan</button>'
                    data-cancel='<button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>'>
                    <i class="bi bi-plus-circle"></i> Stimulus Baru
                </button>
            </small>
        </div>

        <div class="col-12">
            <label class="form-label">Teks Soal <span class="text-danger">*</span></label>
            <textarea name="question_text" rows="4" required class="form-control @error('question_text') is-invalid @enderror"
                placeholder="Tulis pertanyaan di sini...">{{ old('question_text', $question->question_text ?? '') }}</textarea>
            @error('question_text')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label class="form-label">Lampiran Media Soal (opsional)</label>
            <input type="file" name="question_media[]" class="form-control" multiple
                accept="image/*,audio/*,video/*">
            <small class="text-muted">Bisa upload lebih dari satu file.</small>
        </div>

    </div>
</div>
