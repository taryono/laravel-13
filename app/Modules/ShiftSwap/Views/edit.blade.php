{{ Form::open(['method' => $method ?? 'POST', 'route' => $action ?? 'shift_swap.store', 'class' => 'form-horizontal', 'id' => 'formSwap']) }}
@csrf
@if(($swap->id ?? 0) > 0) @method('PUT') @endif

<div class="row g-3">
    {{-- Tanggal Pertukaran --}}
    <div class="col-md-6">
        <label class="form-label">Tanggal Pertukaran <span class="text-danger">*</span></label>
        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
            value="{{ old('date', $swap->date ?? '') }}" required>
        @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Status --}}
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @php $statuses = ['pending' => 'Menunggu Persetujuan', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'cancelled' => 'Dibatalkan']; @endphp
            @foreach($statuses as $val => $label)
            <option value="{{ $val }}" {{ old('status', $swap->status ?? 'pending') == $val ? 'selected' : '' }}>
                {{ $label }}
            </option>
            @endforeach
        </select>
    </div>

    {{-- Karyawan A --}}
    <div class="col-md-6">
        <div class="card border mb-0">
            <div class="card-header py-2 bg-light"><strong>Karyawan A (Pemohon)</strong></div>
            <div class="card-body py-2">
                <label class="form-label mt-2">Nama Karyawan <span class="text-danger">*</span></label>
                <select name="employee_id_a" id="emp_a" class="form-select" required>
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach($employees as $emp)
                    <option value="{{ $emp->id }}"
                        data-shift="{{ $emp->current_shift_id ?? '' }}"
                        {{ old('employee_id_a', $swap->employee_id_a ?? '') == $emp->id ? 'selected' : '' }}>
                        {{ $emp->full_name }}
                    </option>
                    @endforeach
                </select>

                <label class="form-label mt-3">Shift Asli</label>
                <select name="original_shift_a" id="shift_a_ori" class="form-select" readonly>
                    <option value="">-- Pilih Karyawan Dulu --</option>
                </select>

                <label class="form-label mt-3">Ditukar ke Shift <span class="text-danger">*</span></label>
                <select name="target_shift_a" class="form-select" required>
                    <option value="">-- Pilih Shift --</option>
                    @foreach($shifts as $s)
                    <option value="{{ $s->id }}" {{ old('target_shift_a', $swap->target_shift_a ?? '') == $s->id ? 'selected' : '' }}>
                        {{ $s->code }} - {{ $s->name }} ({{ $s->start_time }} s/d {{ $s->end_time }})
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Karyawan B --}}
    <div class="col-md-6">
        <div class="card border mb-0">
            <div class="card-header py-2 bg-light"><strong>Karyawan B (Pasangan)</strong></div>
            <div class="card-body py-2">
                <label class="form-label mt-2">Nama Karyawan <span class="text-danger">*</span></label>
                <select name="employee_id_b" id="emp_b" class="form-select" required>
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach($employees as $emp)
                    <option value="{{ $emp->id }}"
                        data-shift="{{ $emp->current_shift_id ?? '' }}"
                        {{ old('employee_id_b', $swap->employee_id_b ?? '') == $emp->id ? 'selected' : '' }}>
                        {{ $emp->full_name }}
                    </option>
                    @endforeach
                </select>

                <label class="form-label mt-3">Shift Asli</label>
                <select name="original_shift_b" id="shift_b_ori" class="form-select" readonly>
                    <option value="">-- Pilih Karyawan Dulu --</option>
                </select>

                <label class="form-label mt-3">Ditukar ke Shift <span class="text-danger">*</span></label>
                <select name="target_shift_b" class="form-select" required>
                    <option value="">-- Pilih Shift --</option>
                    @foreach($shifts as $s)
                    <option value="{{ $s->id }}" {{ old('target_shift_b', $swap->target_shift_b ?? '') == $s->id ? 'selected' : '' }}>
                        {{ $s->code }} - {{ $s->name }} ({{ $s->start_time }} s/d {{ $s->end_time }})
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Catatan --}}
    <div class="col-12">
        <label class="form-label">Catatan / Alasan</label>
        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $swap->notes ?? '') }}</textarea>
    </div>
</div>
{{ Form::close() }}

@push('script')
<script>
// Otomatis isi shift asli saat karyawan dipilih
const shifts = @json($shifts->keyBy('id'));

function bindEmpChange(empSelect, shiftInput) {
    empSelect.addEventListener('change', function () {
        let opt = this.selectedOptions[0];
        let shiftId = opt?.dataset.shift || '';
        shiftInput.innerHTML = '';
        if (shiftId && shifts[shiftId]) {
            let s = shifts[shiftId];
            shiftInput.innerHTML = `<option value="${s.id}" selected>${s.code} - ${s.name} (${s.start_time} s/d ${s.end_time})</option>`;
        }
    });
    empSelect.dispatchEvent(new Event('change'));
}

document.addEventListener('DOMContentLoaded', () => {
    bindEmpChange(document.getElementById('emp_a'), document.getElementById('shift_a_ori'));
    bindEmpChange(document.getElementById('emp_b'), document.getElementById('shift_b_ori'));
});
</script>
@endpush