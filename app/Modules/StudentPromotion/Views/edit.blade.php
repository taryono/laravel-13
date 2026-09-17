<div class="row g-3">
    <div class="col-md-6">
        <div class="mb-4 p-3 bg-light rounded">
            <div class="row">
                <div class="col-md-6">
                    <div class="text-muted small">Nama Siswa</div>
                    <div class="fw-bold">{{ $promotion->student?->name }}</div>
                    <div class="text-muted small">NIS: {{ $promotion->student?->nis }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Kelas Asal</div>
                    <div class="fw-bold">
                        {{ $promotion->fromClassroom?->name }}
                        ({{ $promotion->fromClassLevel?->name }})
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{ Form::model($student_promotion, ['method' => 'PUT', 'route' => ['student_promotion.update', $student_promotion->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Keputusan <span class="text-danger">*</span></label>
        <select name="promotion_status" class="form-select" required id="statusSelect">
            @foreach (\App\Models\StudentPromotion::statusLabels() as $key => $label)
                <option value="{{ $key }}"
                    {{ old('promotion_status', $promotion->promotion_status) == $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6" id="classroomField">
        <label class="form-label">Kelas Tujuan</label>
        <select name="to_classroom_id" class="form-select">
            <option value="">-- Pilih Kelas --</option>
            @foreach ($classrooms as $classroom)
                <option value="{{ $classroom->id }}"
                    {{ old('to_classroom_id', $promotion->to_classroom_id) == $classroom->id ? 'selected' : '' }}>
                    {{ $classroom->name }} ({{ $classroom->classLevel?->name }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <label class="form-label">Alasan / Catatan</label>
        <textarea name="decision_reason" rows="3" class="form-control">{{ old('decision_reason', $promotion->decision_reason) }}</textarea>
    </div>
</div>
{{ Form::close() }}

@push('scripts')
    <script>
        // Sembunyikan field kelas kalau status graduated/transferred/dropped_out
        const statusSelect = document.getElementById('statusSelect');
        const classroomField = document.getElementById('classroomField');

        function toggleClassroom() {
            const needsClassroom = ['promoted', 'retained'].includes(statusSelect.value);
            classroomField.style.display = needsClassroom ? 'block' : 'none';
        }

        statusSelect.addEventListener('change', toggleClassroom);
        toggleClassroom();
    </script>
@endpush
