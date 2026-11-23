{{ Form::model($employee_leave, ['method' => 'POST', 'route' => ['employee_leave.update', $employee_leave->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
<div class="row">

    {{-- Karyawan --}}
    <div class="col-md-6 mb-3">
        <label for="employee_id" class="form-label">Karyawan <span class="text-danger">*</span></label>
        {{ Form::select('employee_id', $employees ?? [], old('employee_id'), [
            'class' => 'form-control' . ($errors->has('employee_id') ? ' is-invalid' : ''),
            'required' => 'required',
            'placeholder' => '-- Pilih Karyawan --',
        ]) }}
        @if ($errors->has('employee_id'))
            <div class="invalid-feedback">{{ $errors->first('employee_id') }}</div>
        @endif
    </div>

    {{-- Jenis Cuti --}}
    <div class="col-md-6 mb-3">
        <label for="leave_type_id" class="form-label">Jenis Cuti <span class="text-danger">*</span></label>
        {{ Form::select('leave_type_id', $leaveTypes ?? [], old('leave_type_id'), [
            'class' => 'form-control' . ($errors->has('leave_type_id') ? ' is-invalid' : ''),
            'required' => 'required',
            'placeholder' => '-- Pilih Jenis Cuti --',
        ]) }}
        @if ($errors->has('leave_type_id'))
            <div class="invalid-feedback">{{ $errors->first('leave_type_id') }}</div>
        @endif
    </div>

    {{-- Tanggal Mulai --}}
    <div class="col-md-6 mb-3">
        <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
        {{ Form::date('start_date', old('start_date'), [
            'class' => 'form-control' . ($errors->has('start_date') ? ' is-invalid' : ''),
            'required' => 'required',
            'id' => 'start_date',
        ]) }}
        @if ($errors->has('start_date'))
            <div class="invalid-feedback">{{ $errors->first('start_date') }}</div>
        @endif
    </div>

    {{-- Tanggal Selesai --}}
    <div class="col-md-6 mb-3">
        <label for="end_date" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
        {{ Form::date('end_date', old('end_date'), [
            'class' => 'form-control' . ($errors->has('end_date') ? ' is-invalid' : ''),
            'required' => 'required',
            'id' => 'end_date',
        ]) }}
        @if ($errors->has('end_date'))
            <div class="invalid-feedback">{{ $errors->first('end_date') }}</div>
        @endif
    </div>

    {{-- Total Hari --}}
    <div class="col-md-6 mb-3">
        <label for="total_days" class="form-label">Total Hari <span class="text-danger">*</span></label>
        {{ Form::number('total_days', old('total_days', 0.0), [
            'class' => 'form-control' . ($errors->has('total_days') ? ' is-invalid' : ''),
            'step' => '0.01',
            'min' => '0.5',
            'required' => 'required',
            'id' => 'total_days',
            'readonly' => true,
        ]) }}
        <small class="text-muted">Dihitung otomatis berdasarkan rentang tanggal</small>
        @if ($errors->has('total_days'))
            <div class="invalid-feedback">{{ $errors->first('total_days') }}</div>
        @endif
    </div>

    {{-- Status --}}
    <div class="col-md-6 mb-3">
        <label for="status" class="form-label">Status</label>
        {{ Form::select(
            'status',
            [
                'draft' => 'Draft',
                'submitted' => 'Diajukan',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                'cancelled' => 'Dibatalkan',
            ],
            old('status', 'draft'),
            [
                'class' => 'form-control' . ($errors->has('status') ? ' is-invalid' : ''),
                'id' => 'status',
            ],
        ) }}
        @if ($errors->has('status'))
            <div class="invalid-feedback">{{ $errors->first('status') }}</div>
        @endif
    </div>

    {{-- Alasan --}}
    <div class="col-md-12 mb-3">
        <label for="reason" class="form-label">Alasan</label>
        {{ Form::textarea('reason', old('reason'), [
            'class' => 'form-control' . ($errors->has('reason') ? ' is-invalid' : ''),
            'rows' => 3,
            'placeholder' => 'Jelaskan alasan pengajuan cuti...',
        ]) }}
        @if ($errors->has('reason'))
            <div class="invalid-feedback">{{ $errors->first('reason') }}</div>
        @endif
    </div>

    {{-- Lampiran --}}
    <div class="col-md-12 mb-3">
        <label for="attachment_path" class="form-label">Lampiran</label>
        {{ Form::file('attachment_path', [
            'class' => 'form-control' . ($errors->has('attachment_path') ? ' is-invalid' : ''),
            'id' => 'attachment_path',
            'accept' => '.pdf,.jpg,.jpeg,.png,.doc,.docx',
        ]) }}
        <small class="text-muted">Format: PDF, Gambar, DOC/DOCX. Maks 2MB. Kosongkan jika tidak perlu</small>
        @if ($errors->has('attachment_path'))
            <div class="invalid-feedback">{{ $errors->first('attachment_path') }}</div>
        @endif

        {{-- Tampilkan file yang sudah ada saat edit --}}
        @if (isset($employeeLeave) && $employeeLeave->attachment_path)
            <div class="mt-2">
                <a href="{{ asset('storage/' . $employeeLeave->attachment_path) }}" target="_blank"
                    class="btn btn-sm btn-info">
                    Lihat Lampiran
                </a>
            </div>
        @endif
    </div>

    {{-- Alasan Penolakan (hanya muncul jika status ditolak) --}}
    <div class="col-md-12 mb-3" id="rejection_reason_wrapper" style="display: none;">
        <label for="rejection_reason" class="form-label">Alasan Penolakan</label>
        {{ Form::textarea(
            'rejection_reason',
            old('rejection_reason', isset($employeeLeave) ? $employeeLeave->rejection_reason : null),
            [
                'class' => 'form-control' . ($errors->has('rejection_reason') ? ' is-invalid' : ''),
                'rows' => 2,
                'placeholder' => 'Alasan penolakan...',
            ],
        ) }}
        @if ($errors->has('rejection_reason'))
            <div class="invalid-feedback">{{ $errors->first('rejection_reason') }}</div>
        @endif
    </div>
</div>
{{ Form::close() }}

{{-- Script Hitung Otomatis Total Hari --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');
            const totalDays = document.getElementById('total_days');
            const statusSelect = document.getElementById('status');
            const rejectionWrapper = document.getElementById('rejection_reason_wrapper');

            function calculateDays() {
                if (startDate.value && endDate.value) {
                    const start = new Date(startDate.value);
                    const end = new Date(endDate.value);
                    if (end >= start) {
                        // Hitung selisih hari + 1 karena mulai & selesai termasuk
                        const diffTime = Math.abs(end - start);
                        const diffDays = (diffTime / (1000 * 60 * 60 * 24)) + 1;
                        totalDays.value = diffDays.toFixed(2);
                    } else {
                        totalDays.value = 0;
                    }
                }
            }

            function toggleRejectionReason() {
                rejectionWrapper.style.display = statusSelect.value === 'rejected' ? 'block' : 'none';
            }

            startDate.addEventListener('change', calculateDays);
            endDate.addEventListener('change', calculateDays);
            statusSelect.addEventListener('change', toggleRejectionReason);

            // Saat edit: jalankan sekali
            calculateDays();
            toggleRejectionReason();
        });
    </script>
@endpush
