<div class="py-4">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body">
                {{ Form::open(['method' => 'POST', 'route' => ['tax_setting.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
                {{-- Organisasi --}}
                <div class="mb-3">
                    <label for="organization_id" class="form-label">Organisasi <span class="text-danger">*</span></label>
                    <select name="organization_id" id="organization_id"
                        class="form-select @error('organization_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Organisasi --</option>
                        {{-- Ambil dari daftar organisasi --}}
                        @foreach ($organizations as $org)
                            <option value="{{ $org->id }}"
                                {{ old('organization_id') == $org->id ? 'selected' : '' }}>
                                {{ $org->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('organization_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jenis Pajak --}}
                <div class="mb-3">
                    <label for="tax_type_id" class="form-label">Jenis Pajak <span class="text-danger">*</span></label>
                    <select name="tax_type_id" id="tax_type_id"
                        class="form-select @error('tax_type_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Jenis Pajak --</option>
                        @foreach ($taxTypes as $type)
                            <option value="{{ $type->id }}" {{ old('tax_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} ({{ $type->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('tax_type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Kode Pajak --}}
                <div class="mb-3">
                    <label for="tax_code" class="form-label">Kode Pajak <span class="text-danger">*</span></label>
                    <input type="text" name="tax_code" id="tax_code"
                        class="form-control @error('tax_code') is-invalid @enderror" value="{{ old('tax_code') }}"
                        required maxlength="50">
                    @error('tax_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nama Pajak --}}
                <div class="mb-3">
                    <label for="tax_name" class="form-label">Nama Pajak <span class="text-danger">*</span></label>
                    <input type="text" name="tax_name" id="tax_name"
                        class="form-control @error('tax_name') is-invalid @enderror" value="{{ old('tax_name') }}"
                        required maxlength="255">
                    @error('tax_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tarif Pajak --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="tax_rate" class="form-label">Tarif Pajak (%) <span
                                class="text-danger">*</span></label>
                        <input type="number" step="0.0001" min="0" max="100" name="tax_rate"
                            id="tax_rate" class="form-control @error('tax_rate') is-invalid @enderror"
                            value="{{ old('tax_rate', '0.0000') }}" required>
                        @error('tax_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Min & Max Amount --}}
                    <div class="col-md-4 mb-3">
                        <label for="min_amount" class="form-label">Minimal Nilai</label>
                        <input type="number" step="0.01" min="0" name="min_amount" id="min_amount"
                            class="form-control @error('min_amount') is-invalid @enderror"
                            value="{{ old('min_amount') }}">
                        @error('min_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="max_amount" class="form-label">Maksimal Nilai</label>
                        <input type="number" step="0.01" min="0" name="max_amount" id="max_amount"
                            class="form-control @error('max_amount') is-invalid @enderror"
                            value="{{ old('max_amount') }}">
                        @error('max_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Tanggal Berlaku --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="effective_date" class="form-label">Tanggal Berlaku <span
                                class="text-danger">*</span></label>
                        <input type="date" name="effective_date" id="effective_date"
                            class="form-control @error('effective_date') is-invalid @enderror"
                            value="{{ old('effective_date') }}" required>
                        @error('effective_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="expiry_date" class="form-label">Tanggal Berakhir</label>
                        <input type="date" name="expiry_date" id="expiry_date"
                            class="form-control @error('expiry_date') is-invalid @enderror"
                            value="{{ old('expiry_date') }}">
                        @error('expiry_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Status Aktif --}}
                <div class="mb-3">
                    <div class="form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
                            value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktif</label>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Keterangan</label>
                    <textarea name="description" id="description" rows="3"
                        class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
