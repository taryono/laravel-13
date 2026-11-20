{{-- Organization --}}
<div class="row mb-3">
    <label for="organization_id" class="col-sm-3 col-form-label">Organization <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        {{ Form::select(
            'organization_id',
            $organizations ?? [],
            old('organization_id', $bonus->organization_id ?? null),
            [
                'class' => 'form-select' . ($errors->has('organization_id') ? ' is-invalid' : ''),
                'id' => 'organization_id',
                'placeholder' => '-- Pilih Organization --',
                'required' => true,
            ],
        ) }}
        @error('organization_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Employee --}}
<div class="row mb-3">
    <label for="employee_id" class="col-sm-3 col-form-label">Employee <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        {{ Form::select('employee_id', $employees ?? [], old('employee_id', $bonus->employee_id ?? null), [
            'class' => 'form-select' . ($errors->has('employee_id') ? ' is-invalid' : ''),
            'id' => 'employee_id',
            'placeholder' => '-- Pilih Employee --',
            'required' => true,
        ]) }}
        @error('employee_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Bonus Code --}}
<div class="row mb-3">
    <label for="bonus_code" class="col-sm-3 col-form-label">Bonus Code <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        @error('bonus_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Bonus Type --}}
<div class="row mb-3">
    <label for="bonus_type" class="col-sm-3 col-form-label">Bonus Type <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        {{ Form::select(
            'bonus_type',
            [
                'performance' => 'Performance',
                'thr' => 'THR',
                'bonus_year_end' => 'Bonus Year End',
                'project_bonus' => 'Project Bonus',
                'referral' => 'Referral',
                'attendance' => 'Attendance',
                'other' => 'Other',
            ],
            old('bonus_type', $bonus->bonus_type ?? null),
            [
                'class' => 'form-select' . ($errors->has('bonus_type') ? ' is-invalid' : ''),
                'id' => 'bonus_type',
                'placeholder' => '-- Pilih Tipe Bonus --',
                'required' => true,
            ],
        ) }}
        @error('bonus_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Amount --}}
<div class="row mb-3">
    <label for="amount" class="col-sm-3 col-form-label">Amount <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            {{ Form::number('amount', old('amount', $bonus->amount ?? null), [
                'class' => 'form-control' . ($errors->has('amount') ? ' is-invalid' : ''),
                'id' => 'amount',
                'step' => '0.01',
                'min' => '0',
                'placeholder' => '0.00',
                'required' => true,
            ]) }}
        </div>
        @error('amount')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Payment Date --}}
<div class="row mb-3">
    <label for="payment_date" class="col-sm-3 col-form-label">Payment Date <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        {{ Form::date(
            'payment_date',
            old(
                'payment_date',
                isset($bonus->payment_date) ? \Carbon\Carbon::parse($bonus->payment_date)->format('Y-m-d') : null,
            ),
            [
                'class' => 'form-control' . ($errors->has('payment_date') ? ' is-invalid' : ''),
                'id' => 'payment_date',
                'required' => true,
            ],
        ) }}
        @error('payment_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Payroll Period --}}
<div class="row mb-3">
    <label for="payroll_period_id" class="col-sm-3 col-form-label">Payroll Period</label>
    <div class="col-sm-9">
        {{ Form::select(
            'payroll_period_id',
            $payrollPeriods ?? [],
            old('payroll_period_id', $bonus->payroll_period_id ?? null),
            [
                'class' => 'form-select' . ($errors->has('payroll_period_id') ? ' is-invalid' : ''),
                'id' => 'payroll_period_id',
                'placeholder' => '-- Tidak terkait periode --',
            ],
        ) }}
        @error('payroll_period_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Kosongkan jika bonus belum/tidak masuk ke periode payroll tertentu.</div>
    </div>
</div>

{{-- Is Paid --}}
<div class="row mb-3">
    <label for="is_paid" class="col-sm-3 col-form-label">Status Pembayaran</label>
    <div class="col-sm-9">
        <div class="form-check form-switch">
            {{ Form::hidden('is_paid', 0) }}
            {{ Form::checkbox('is_paid', 1, old('is_paid', $bonus->is_paid ?? 0), [
                'class' => 'form-check-input' . ($errors->has('is_paid') ? ' is-invalid' : ''),
                'id' => 'is_paid',
            ]) }}
            <label class="form-check-label" for="is_paid">Sudah dibayar</label>
        </div>
        @error('is_paid')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Paid At --}}
<div class="row mb-3">
    <label for="paid_at" class="col-sm-3 col-form-label">Paid At</label>
    <div class="col-sm-9">
        {{ Form::datetimeLocal(
            'paid_at',
            old('paid_at', isset($bonus->paid_at) ? \Carbon\Carbon::parse($bonus->paid_at)->format('Y-m-d\TH:i') : null),
            [
                'class' => 'form-control' . ($errors->has('paid_at') ? ' is-invalid' : ''),
                'id' => 'paid_at',
            ],
        ) }}
        @error('paid_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Isi otomatis oleh sistem jika status "Sudah dibayar" dipilih, tapi bisa diisi manual.
        </div>
    </div>
</div>

{{-- Description --}}
<div class="row mb-3">
    <label for="description" class="col-sm-3 col-form-label">Description</label>
    <div class="col-sm-9">
        {{ Form::textarea('description', old('description', $bonus->description ?? null), [
            'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''),
            'id' => 'description',
            'rows' => 3,
            'placeholder' => 'Deskripsi singkat bonus...',
        ]) }}
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Notes --}}
<div class="row mb-3">
    <label for="notes" class="col-sm-3 col-form-label">Notes</label>
    <div class="col-sm-9">
        {{ Form::textarea('notes', old('notes', $bonus->notes ?? null), [
            'class' => 'form-control' . ($errors->has('notes') ? ' is-invalid' : ''),
            'id' => 'notes',
            'rows' => 2,
            'placeholder' => 'Catatan internal...',
        ]) }}
        @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
