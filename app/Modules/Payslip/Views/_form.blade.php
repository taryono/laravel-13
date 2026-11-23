@php
    $errors = $errors ?? (session('errors') ?? new \Illuminate\Support\ViewErrorBag());
    $model = $payslip ?? null;
    $selectedEmployee = old('employee_id', $model->employee_id ?? null);
    $selectedPeriod = old('payroll_period_id', $model->payroll_period_id ?? null);
    $selectedDetail = old('payroll_detail_id', $model->payroll_detail_id ?? null);
    $payslipNumber = old('payslip_number', $model->payslip_number ?? '');
    $grossSalary = old('gross_salary', $model->gross_salary ?? 0);
    $totalAllowance = old('total_allowance', $model->total_allowance ?? 0);
    $totalDeduction = old('total_deduction', $model->total_deduction ?? 0);
    $netSalary = old('net_salary', $model->net_salary ?? 0);
    $currency = old('currency', $model->currency ?? 'IDR');
    $status = old('status', $model->status ?? 'draft');
    $generatedAt = old(
        'generated_at',
        isset($model->generated_at)
            ? \Carbon\Carbon::parse($model->generated_at)->format('Y-m-d\TH:i')
            : now()->format('Y-m-d\TH:i'),
    );
    $isSent = old('is_sent', $model->is_sent ?? false);
    $sentAt = old(
        'sent_at',
        isset($model->sent_at) ? \Carbon\Carbon::parse($model->sent_at)->format('Y-m-d\TH:i') : null,
    );
    $isDownloaded = old('is_downloaded', $model->is_downloaded ?? false);
    $downloadedAt = old(
        'downloaded_at',
        isset($model->downloaded_at) ? \Carbon\Carbon::parse($model->downloaded_at)->format('Y-m-d\TH:i') : null,
    );
@endphp

<div class="row g-3">
    {{-- Nomor Slip & Mata Uang --}}
    <div class="col-md-8">
        <div class="form-group">
            {{ Form::label('payslip_number', 'Nomor Slip Gaji *', ['class' => 'form-label fw-semibold']) }}
            {{ Form::text('payslip_number', $payslipNumber, [
                'class' => 'form-control' . ($errors->has('payslip_number') ? ' is-invalid' : ''),
                'placeholder' => 'Contoh: PS/202611/00001 (Otomatis dibuat jika kosong)',
                'required' => true,
            ]) }}
            @error('payslip_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {{ Form::label('currency', 'Mata Uang *', ['class' => 'form-label fw-semibold']) }}
            {{ Form::select('currency', ['IDR' => 'IDR (Rupiah)', 'USD' => 'USD (Dollar)'], $currency, [
                'class' => 'form-select' . ($errors->has('currency') ? ' is-invalid' : ''),
                'required' => true,
            ]) }}
            @error('currency')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Karyawan & Periode Payroll --}}
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('employee_id', 'Karyawan / Pegawai *', ['class' => 'form-label fw-semibold']) }}
            {{ Form::select('employee_id', $employees ?? [], $selectedEmployee, [
                'class' => 'form-select select2' . ($errors->has('employee_id') ? ' is-invalid' : ''),
                'placeholder' => '-- Pilih Karyawan --',
                'required' => true,
            ]) }}
            @error('employee_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('payroll_period_id', 'Periode Payroll *', ['class' => 'form-label fw-semibold']) }}
            {{ Form::select('payroll_period_id', $payroll_periods ?? [], $selectedPeriod, [
                'class' => 'form-select select2' . ($errors->has('payroll_period_id') ? ' is-invalid' : ''),
                'placeholder' => '-- Pilih Periode --',
                'required' => true,
            ]) }}
            @error('payroll_period_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Detail Payroll (Optional / Auto-link) --}}
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('payroll_detail_id', 'Payroll Detail (Opsional)', ['class' => 'form-label fw-semibold']) }}
            {{ Form::select('payroll_detail_id', $payroll_details ?? [], $selectedDetail, [
                'class' => 'form-select select2' . ($errors->has('payroll_detail_id') ? ' is-invalid' : ''),
                'placeholder' => '-- Pilih Payroll Detail (Opsional, otomatis digenerate jika kosong) --',
            ]) }}
            <small class="form-text text-muted">Jika dibiarkan kosong, sistem akan otomatis mengaitkan atau membuat
                detail payroll untuk karyawan dan periode terkait.</small>
            @error('payroll_detail_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Rincian Gaji --}}
    <div class="col-12">
        <div class="p-3 bg-light rounded-3 border">
            <h6 class="fw-bold text-dark mb-3">Rincian Nominal Gaji</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="form-group">
                        {{ Form::label('gross_salary', 'Gaji Pokok / Kotor (Gross)', ['class' => 'form-label']) }}
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            {{ Form::number('gross_salary', $grossSalary, [
                                'class' => 'form-control salary-calc' . ($errors->has('gross_salary') ? ' is-invalid' : ''),
                                'step' => '0.01',
                                'min' => '0',
                                'id' => 'gross_salary',
                            ]) }}
                        </div>
                        @error('gross_salary')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{ Form::label('total_allowance', 'Total Tunjangan', ['class' => 'form-label']) }}
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            {{ Form::number('total_allowance', $totalAllowance, [
                                'class' => 'form-control salary-calc' . ($errors->has('total_allowance') ? ' is-invalid' : ''),
                                'step' => '0.01',
                                'min' => '0',
                                'id' => 'total_allowance',
                            ]) }}
                        </div>
                        @error('total_allowance')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{ Form::label('total_deduction', 'Total Potongan', ['class' => 'form-label']) }}
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            {{ Form::number('total_deduction', $totalDeduction, [
                                'class' => 'form-control salary-calc' . ($errors->has('total_deduction') ? ' is-invalid' : ''),
                                'step' => '0.01',
                                'min' => '0',
                                'id' => 'total_deduction',
                            ]) }}
                        </div>
                        @error('total_deduction')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        {{ Form::label('net_salary', 'Gaji Bersih (Take Home Pay) *', ['class' => 'form-label fw-bold text-success']) }}
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white fw-bold">Rp</span>
                            {{ Form::number('net_salary', $netSalary, [
                                'class' => 'form-control form-control-lg fw-bold text-success' . ($errors->has('net_salary') ? ' is-invalid' : ''),
                                'step' => '0.01',
                                'min' => '0',
                                'id' => 'net_salary',
                                'required' => true,
                            ]) }}
                        </div>
                        @error('net_salary')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Status & Tanggal Generate --}}
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('status', 'Status Slip Gaji *', ['class' => 'form-label fw-semibold']) }}
            {{ Form::select(
                'status',
                [
                    'draft' => 'Draft (Konsep)',
                    'generated' => 'Generated (Siap Kirim)',
                    'sent' => 'Sent (Terkirim)',
                    'downloaded' => 'Downloaded (Telah Diunduh)',
                    'void' => 'Void (Dibatalkan)',
                ],
                $status,
                [
                    'class' => 'form-select' . ($errors->has('status') ? ' is-invalid' : ''),
                    'required' => true,
                ],
            ) }}
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('generated_at', 'Tanggal & Waktu Dibuat', ['class' => 'form-label fw-semibold']) }}
            {{ Form::input('datetime-local', 'generated_at', $generatedAt, [
                'class' => 'form-control' . ($errors->has('generated_at') ? ' is-invalid' : ''),
            ]) }}
            @error('generated_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Status Pengiriman & Unduhan --}}
    <div class="col-md-6">
        <div class="card p-3 border">
            <div class="form-check form-switch mb-2">
                {{ Form::checkbox('is_sent', 1, $isSent, ['class' => 'form-check-input', 'id' => 'is_sent']) }}
                {{ Form::label('is_sent', 'Slip Sudah Dikirim ke Karyawan', ['class' => 'form-check-label fw-semibold']) }}
            </div>
            <div class="form-group mt-2">
                {{ Form::label('sent_at', 'Waktu Pengiriman', ['class' => 'form-label text-muted small']) }}
                {{ Form::input('datetime-local', 'sent_at', $sentAt, ['class' => 'form-control form-control-sm', 'id' => 'sent_at']) }}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3 border">
            <div class="form-check form-switch mb-2">
                {{ Form::checkbox('is_downloaded', 1, $isDownloaded, ['class' => 'form-check-input', 'id' => 'is_downloaded']) }}
                {{ Form::label('is_downloaded', 'Slip Sudah Diunduh Karyawan', ['class' => 'form-check-label fw-semibold']) }}
            </div>
            <div class="form-group mt-2">
                {{ Form::label('downloaded_at', 'Waktu Diunduh', ['class' => 'form-label text-muted small']) }}
                {{ Form::input('datetime-local', 'downloaded_at', $downloadedAt, ['class' => 'form-control form-control-sm', 'id' => 'downloaded_at']) }}
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        function calcNet() {
            var gross = parseFloat(document.getElementById('gross_salary')?.value) || 0;
            var allowance = parseFloat(document.getElementById('total_allowance')?.value) || 0;
            var deduction = parseFloat(document.getElementById('total_deduction')?.value) || 0;
            var net = Math.max(0, (gross + allowance) _deduction);
            var netEl = document.getElementById('net_salary');
            if (netEl && (!netEl.value || netEl.dataset.autocalc !== 'false')) {
                netEl.value = net.toFixed(2);
            }
        }
        document.querySelectorAll('.salary-calc').forEach(function(el) {
            el.addEventListener('input', calcNet);
        });
    })();
</script>
