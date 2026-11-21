{{-- resources/views/payroll-periods/_form.blade.php --}}
@php
    $statuses = [
        'open' => 'Open',
        'processing' => 'Processing',
        'approved' => 'Approved',
        'paid' => 'Paid',
        'closed' => 'Closed',
        'cancelled' => 'Cancelled',
    ];
    $dateValue = fn($field) => old($field, optional($payrollPeriod->$field ?? null)->format('Y-m-d'));
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label for="organization_id" class="form-label">Organisasi <span class="text-danger">*</span></label>
        <select name="organization_id" id="organization_id"
            class="form-select @error('organization_id') is-invalid @enderror" required>
            <option value="">-- Pilih organisasi --</option>
            @foreach ($organizations as $org)
                <option value="{{ $org->id }}" @selected(old('organization_id', $payrollPeriod->organization_id ?? null) == $org->id)>
                    {{ $org->name }}
                </option>
            @endforeach
        </select>
        @error('organization_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="period" class="form-label">Periode <span class="text-danger">*</span></label>
        <input type="month" name="period" id="period" class="form-control @error('period') is-invalid @enderror"
            value="{{ old('period', $payrollPeriod->period ?? now()->format('Y-m')) }}" required>
        @error('period')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Format YYYY-MM. Kombinasi organisasi dan periode harus unik.</div>
    </div>

    <div class="col-md-4">
        <label for="code" class="form-label">Kode</label>
        <input type="text" name="code" id="code" maxlength="255"
            class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', $payrollPeriod->code ?? '') }}" placeholder="PRD-2026-11">
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-8">
        <label for="name" class="form-label">Nama</label>
        <input type="text" name="name" id="name" maxlength="255"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $payrollPeriod->name ?? '') }}" placeholder="Penggajian November 2026">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="start_date" class="form-label">Tanggal mulai <span class="text-danger">*</span></label>
        <input type="date" name="start_date" id="start_date"
            class="form-control @error('start_date') is-invalid @enderror" value="{{ $dateValue('start_date') }}"
            required>
        @error('start_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="end_date" class="form-label">Tanggal selesai <span class="text-danger">*</span></label>
        <input type="date" name="end_date" id="end_date"
            class="form-control @error('end_date') is-invalid @enderror" value="{{ $dateValue('end_date') }}" required>
        @error('end_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="payment_date" class="form-label">Tanggal pembayaran</label>
        <input type="date" name="payment_date" id="payment_date"
            class="form-control @error('payment_date') is-invalid @enderror" value="{{ $dateValue('payment_date') }}">
        @error('payment_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $payrollPeriod->status ?? 'open') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
            <input type="hidden" name="is_closed" value="0">
            <input class="form-check-input" type="checkbox" role="switch" id="is_closed" name="is_closed"
                value="1" @checked(old('is_closed', $payrollPeriod->is_closed ?? false))>
            <label class="form-check-label" for="is_closed">Periode ditutup</label>
        </div>
    </div>
</div>
