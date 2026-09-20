<div class="card mb-3">
    <div class="card-header fw-bold">Data Utama</div>
    <div class="card-body row g-3">

        <div class="col-md-6">
            <label class="form-label">Paket Ujian <span class="text-danger">*</span></label>
            <select name="exam_package_id" class="form-select @error('exam_package_id') is-invalid @enderror" required>
                <option value="">-- Pilih Paket Ujian --</option>
                @foreach ($examPackages as $pkg)
                    <option value="{{ $pkg->id }}"
                        {{ old('exam_package_id', $session->exam_package_id ?? '') == $pkg->id ? 'selected' : '' }}>
                        {{ $pkg->name }}
                    </option>
                @endforeach
            </select>
            @error('exam_package_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Siswa <span class="text-danger">*</span></label>
            <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                <option value="">-- Pilih Siswa --</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}"
                        {{ old('student_id', $session->student_id ?? '') == $student->id ? 'selected' : '' }}>
                        {{ $student->name }} ({{ $student->nis ?? ($student->username ?? '-') }})
                    </option>
                @endforeach
            </select>
            @error('student_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Jadwal Ujian</label>
            <select name="schedule_id" class="form-select">
                <option value="">-- Tanpa Jadwal --</option>
                @foreach ($schedules as $schedule)
                    <option value="{{ $schedule->id }}"
                        {{ old('schedule_id', $session->schedule_id ?? '') == $schedule->id ? 'selected' : '' }}>
                        {{ $schedule->name ?? $schedule->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Kelas</label>
            <select name="classroom_id" class="form-select">
                <option value="">-- Tanpa Kelas --</option>
                @foreach ($classrooms as $classroom)
                    <option value="{{ $classroom->id }}"
                        {{ old('classroom_id', $session->classroom_id ?? '') == $classroom->id ? 'selected' : '' }}>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>
</div>

<div class="card mb-3">
    <div class="card-header fw-bold">Waktu &amp; Durasi (Override Manual)</div>
    <div class="card-body row g-3">

        <div class="col-md-4">
            <label class="form-label">Waktu Mulai</label>
            <input type="datetime-local" name="start_time" class="form-control"
                value="{{ old('start_time', isset($session->start_time) ? $session->start_time->format('Y-m-d\TH:i') : '') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Waktu Selesai</label>
            <input type="datetime-local" name="end_time" class="form-control"
                value="{{ old('end_time', isset($session->end_time) ? $session->end_time->format('Y-m-d\TH:i') : '') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Durasi (detik)</label>
            <input type="number" name="duration_seconds" class="form-control" min="0"
                value="{{ old('duration_seconds', $session->duration_seconds ?? '') }}">
            <small class="text-muted">Kosongkan jika mengikuti durasi default paket ujian.</small>
        </div>

    </div>
</div>

<div class="card mb-3">
    <div class="card-header fw-bold">Status &amp; Pengawasan</div>
    <div class="card-body row g-3">

        <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="pending" {{ old('status', $session->status ?? '') == 'pending' ? 'selected' : '' }}>
                    Pending</option>
                <option value="ongoing" {{ old('status', $session->status ?? '') == 'ongoing' ? 'selected' : '' }}>
                    Sedang Berjalan</option>
                <option value="submitted" {{ old('status', $session->status ?? '') == 'submitted' ? 'selected' : '' }}>
                    Selesai</option>
                <option value="expired" {{ old('status', $session->status ?? '') == 'expired' ? 'selected' : '' }}>
                    Waktu Habis</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Status Aktif</label>
            <div class="form-check form-switch mt-2">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $session->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label">Sesi Aktif</label>
            </div>
        </div>

        @if (isset($session))
            <div class="col-md-4">
                <label class="form-label">Nilai Akhir (Koreksi Manual)</label>
                <input type="number" step="0.01" min="0" max="100" name="final_score"
                    class="form-control" value="{{ old('final_score', $session->final_score ?? '') }}">
            </div>
        @endif

        <div class="col-md-12">
            <label class="form-label">Catatan Pengawas (Proctor Notes)</label>
            <textarea name="proctor_notes" rows="3" class="form-control"
                placeholder="Contoh: siswa keluar tab 2x, koneksi terputus pukul 09.15, dst.">{{ old('proctor_notes', $session->proctor_notes ?? '') }}</textarea>
        </div>

    </div>
</div>

{{-- Info read-only, hanya tampil saat edit --}}
@if (isset($session))
    <div class="card mb-3">
        <div class="card-header fw-bold">Info Sistem (Read-only)</div>
        <div class="card-body row g-3">
            <div class="col-md-3">
                <label class="form-label d-block">Sudah Disubmit?</label>
                <span class="badge bg-{{ $session->is_submitted ? 'success' : 'secondary' }}">
                    {{ $session->is_submitted ? 'Ya' : 'Belum' }}
                </span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block">Waktu Submit</label>
                <span>{{ $session->submitted_at?->format('d-m-Y H:i') ?? '-' }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block">Soal Terakhir Dikerjakan (index)</label>
                <span>{{ $session->current_question_index }}</span>
            </div>
            <div class="col-md-3">
                <label class="form-label d-block">IP Address</label>
                <span>{{ $session->ip_address ?? '-' }}</span>
            </div>
            <div class="col-md-12">
                <label class="form-label d-block">User Agent</label>
                <span class="text-muted small">{{ $session->user_agent ?? '-' }}</span>
            </div>
        </div>
    </div>
@endif
