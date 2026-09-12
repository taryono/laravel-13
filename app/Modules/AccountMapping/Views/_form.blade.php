@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
    $organizations = $organizations ?? \Models\Organization::orderBy('name')->pluck('name', 'id');
    $sourceTypes = [
        'payroll_component' => 'Komponen Payroll / Gaji',
        'sales'             => 'Penjualan (Sales)',
        'purchase'          => 'Pembelian (Purchase)',
        'expense'           => 'Beban / Pengeluaran (Expense)',
        'tax'               => 'Pajak (Tax)',
        'loan'              => 'Pinjaman / Kasbon (Loan)',
        'bank'              => 'Kas & Bank',
        'other'             => 'Lainnya (Other)',
    ];
@endphp

<div class="row g-3">
    {{-- Notifikasi / Info Bantuan --}}
    <div class="col-12">
        <div class="alert alert-light border d-flex align-items-center gap-2 py-2 px-3 mb-1 text-muted small">
            <i class="bi bi-info-circle-fill text-primary"></i>
            <span>Tentukan organisasi, jenis sumber transaksi, serta akun debit dan kredit (COA) yang bersesuaian.</span>
        </div>
    </div>

    {{-- Organisasi --}}
    <div class="col-md-6">
        <label for="organization_id" class="form-label fw-semibold">
            Organisasi / Unit <span class="text-danger">*</span>
        </label>
        <select name="organization_id" id="organization_id" class="form-select @error('organization_id') is-invalid @enderror select2" required>
            <option value="">-- Pilih Organisasi --</option>
            @foreach ($organizations as $orgId => $orgName)
                <option value="{{ $orgId }}" {{ (string) old('organization_id', $account_mapping->organization_id ?? '') === (string) $orgId ? 'selected' : '' }}>
                    {{ $orgName }}
                </option>
            @endforeach
        </select>
        @error('organization_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Tipe Sumber (Source Type) --}}
    <div class="col-md-6">
        <label for="source_type" class="form-label fw-semibold">
            Tipe Sumber (Source Type) <span class="text-danger">*</span>
        </label>  
        <select name="source_type" id="source_type" class="form-select @error('source_type') is-invalid @enderror select2" required>
            <option value="">-- Pilih Tipe Sumber --</option>
            @foreach ($sourceTypes as $val => $label)
                <option value="{{ $val }}" {{ old('source_type', $account_mapping->source_type ?? '') === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('source_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Kode Sumber (Source Code) --}}
    <div class="col-md-8">
        <label for="source_code" class="form-label fw-semibold">
            Kode Sumber (Source Code) <span class="text-danger">*</span>
        </label>
        <input type="text" name="source_code" id="source_code"
            class="form-control text-uppercase @error('source_code') is-invalid @enderror"
            value="{{ old('source_code', $account_mapping->source_code ?? '') }}"
            placeholder="Contoh: BASIC_SALARY, SALES_INVOICE, LISTRIK" required>
        <small class="text-muted">Kode unik komponen/modul yang dipetakan (misal: GAJI_POKOK, PPH_21, BEBAN_SEWA).</small>
        @error('source_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Status Aktif --}}
    <div class="col-md-4 align-self-center pt-2">
        <label class="form-label d-block fw-semibold">&nbsp;</label>
        <div class="form-check form-switch">
            <input type="hidden" name="is_active" value="0">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                {{ old('is_active', $account_mapping->is_active ?? 1) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="is_active">Status Aktif</label>
        </div>
        @error('is_active')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Card Group: Akun Debit & Kredit --}}
    <div class="col-12">
        <div class="card bg-light border-0 p-3 rounded-3">
            <h6 class="fw-semibold text-secondary mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-arrow-left-right text-primary"></i> Pemetaan Akun COA (Chart of Accounts)
            </h6>
            <div class="row g-3">
                {{-- Akun Debit --}}
                <div class="col-md-6">
                    <label for="debit_account_id" class="form-label fw-medium">
                        <span class="badge bg-success-subtle text-success border border-success-subtle me-1">Debit</span> Akun Debit
                    </label>
                    <select name="debit_account_id" id="debit_account_id" class="form-select select2 @error('debit_account_id') is-invalid @enderror">
                        <option value="">-- Pilih Akun Debit (Opsional) --</option>
                        @foreach ($accounts as $accId => $accLabel)
                            <option value="{{ $accId }}" {{ (string) old('debit_account_id', $account_mapping->debit_account_id ?? '') === (string) $accId ? 'selected' : '' }}>
                                {{ $accLabel }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted d-block mt-1">Akun yang didebit saat transaksi terjadi.</small>
                    @error('debit_account_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Akun Kredit --}}
                <div class="col-md-6">
                    <label for="credit_account_id" class="form-label fw-medium">
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle me-1">Kredit</span> Akun Kredit
                    </label>
                    <select name="credit_account_id" id="credit_account_id" class="form-select select2 @error('credit_account_id') is-invalid @enderror">
                        <option value="">-- Pilih Akun Kredit (Opsional) --</option>
                        @foreach ($accounts as $accId => $accLabel)
                            <option value="{{ $accId }}" {{ (string) old('credit_account_id', $account_mapping->credit_account_id ?? '') === (string) $accId ? 'selected' : '' }}>
                                {{ $accLabel }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted d-block mt-1">Akun yang dikredit saat transaksi terjadi.</small>
                    @error('credit_account_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Deskripsi / Keterangan --}}
    <div class="col-12">
        <label for="description" class="form-label fw-semibold">Deskripsi / Keterangan</label>
        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
            rows="3" placeholder="Catatan tambahan mengenai aturan pemetaan ini...">{{ old('description', $account_mapping->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Fallback tombol jika dibuka di luar modal AJAX --}}
    @if (!request()->ajax())
        <div class="col-12 text-end mt-3">
            <a href="{{ route('account_mapping.index') }}" class="btn btn-secondary px-4 me-2">Batal</a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save me-1"></i> Simpan
            </button>
        </div>
    @endif
</div>
