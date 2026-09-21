<div class="card mb-3">
    <div class="card-header fw-bold">Rubrik Penilaian (opsional)</div>
    <div class="card-body">
        <small class="text-muted d-block mb-2">
            Soal esai dikoreksi manual. Rubrik hanya panduan.
        </small>
        <textarea name="essay_rubric" rows="3" class="form-control" placeholder="Contoh: skor 10 jika ...">{{ old('essay_rubric', $question->essay_rubric ?? '') }}</textarea>
    </div>
</div>
