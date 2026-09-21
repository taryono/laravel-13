<div class="card mb-3">
    <div class="card-header fw-bold">Pembahasan &amp; Pengaturan</div>
    <div class="card-body row g-3">
        <div class="col-12" x-show="typeCode !== 'essay'" x-cloak>
            <label class="form-label">Pembahasan Jawaban (opsional)</label>
            <textarea name="explanation" rows="3" class="form-control" placeholder="Penjelasan kenapa jawaban benar...">{{ old('explanation', $question->explanation ?? '') }}</textarea>
        </div>
        <div class="col-md-4">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_public" value="1"
                    @checked(old('is_public', $question->is_public ?? false))>
                <label class="form-check-label">Soal publik</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    @checked(old('is_active', $question->is_active ?? true))>
                <label class="form-check-label">Aktif</label>
            </div>
        </div>
    </div>
</div>
