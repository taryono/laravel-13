{{-- resources/views/Asset/partials/_form.blade.php --}}

<div class="row g-3">

    {{-- ============================================================= --}}
    {{-- SECTION: IDENTITAS ASET --}}
    {{-- ============================================================= --}}
    <div class="col-12">
        <h6 class="text-uppercase text-muted fw-bold mb-2">
            <i class="fa fa-cube me-1"></i> Identitas Aset
        </h6>
        <hr class="mt-0">
    </div>

    <div class="col-md-4">
        {{ Form::label('code', 'Kode Aset <span class="text-danger">*</span>', ['class' => 'form-label'], false) }}
        {{ Form::text('code', old('code'), [
            'class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : ''),
            'placeholder' => 'AST-001',
            'required' => true,
            'maxlength' => 50,
        ]) }}
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-8">
        {{ Form::label('name', 'Nama Aset <span class="text-danger">*</span>', ['class' => 'form-label'], false) }}
        {{ Form::text('name', old('name'), [
            'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
            'placeholder' => 'Contoh: Laptop Dell Latitude 5420',
            'required' => true,
            'maxlength' => 200,
        ]) }}
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('asset_category_id', 'Kategori <span class="text-danger">*</span>', ['class' => 'form-label'], false) }}
        {{ Form::select('asset_category_id', $categories ?? [], old('asset_category_id'), [
            'class' => 'form-select select2' . ($errors->has('asset_category_id') ? ' is-invalid' : ''),
            'placeholder' => '-- Pilih Kategori --',
            'required' => true,
        ]) }}
        @error('asset_category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('supplier_id', 'Supplier', ['class' => 'form-label']) }}
        {{ Form::select('supplier_id', $suppliers ?? [], old('supplier_id'), [
            'class' => 'form-select select2' . ($errors->has('supplier_id') ? ' is-invalid' : ''),
            'placeholder' => '-- Pilih Supplier --',
        ]) }}
        @error('supplier_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        {{ Form::label('brand', 'Merek', ['class' => 'form-label']) }}
        {{ Form::text('brand', old('brand'), [
            'class' => 'form-control' . ($errors->has('brand') ? ' is-invalid' : ''),
            'placeholder' => 'Dell, HP, Asus, ...',
            'maxlength' => 100,
        ]) }}
        @error('brand')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        {{ Form::label('model', 'Model', ['class' => 'form-label']) }}
        {{ Form::text('model', old('model'), [
            'class' => 'form-control' . ($errors->has('model') ? ' is-invalid' : ''),
            'placeholder' => 'Latitude 5420',
            'maxlength' => 100,
        ]) }}
        @error('model')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        {{ Form::label('serial_number', 'Serial Number', ['class' => 'form-label']) }}
        {{ Form::text('serial_number', old('serial_number'), [
            'class' => 'form-control' . ($errors->has('serial_number') ? ' is-invalid' : ''),
            'placeholder' => 'SN-XXXX-XXXX',
            'maxlength' => 100,
        ]) }}
        @error('serial_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        {{ Form::label('specification', 'Spesifikasi', ['class' => 'form-label']) }}
        {{ Form::textarea('specification', old('specification'), [
            'class' => 'form-control' . ($errors->has('specification') ? ' is-invalid' : ''),
            'rows' => 2,
            'placeholder' => 'Intel i7, RAM 16GB, SSD 512GB, ...',
        ]) }}
        @error('specification')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- ============================================================= --}}
    {{-- SECTION: KEPEMILIKAN & LOKASI --}}
    {{-- ============================================================= --}}
    <div class="col-12 mt-3">
        <h6 class="text-uppercase text-muted fw-bold mb-2">
            <i class="fa fa-map-marker-alt me-1"></i> Kepemilikan & Lokasi
        </h6>
        <hr class="mt-0">
    </div>

    <div class="col-md-6">
        {{ Form::label('organization_id', 'Organisasi', ['class' => 'form-label']) }}
        {{ Form::select('organization_id', $organizations ?? [], old('organization_id'), [
            'class' => 'form-select select2',
            'placeholder' => '-- Pilih Organisasi --',
        ]) }}
    </div>

    <div class="col-md-6">
        {{ Form::label('school_id', 'Sekolah', ['class' => 'form-label']) }}
        {{ Form::select('school_id', $schools ?? [], old('school_id'), [
            'class' => 'form-select select2',
            'placeholder' => '-- Pilih Sekolah --',
        ]) }}
    </div>

    <div class="col-md-6">
        {{ Form::label('location_id', 'Lokasi', ['class' => 'form-label']) }}
        {{ Form::select('location_id', $locations ?? [], old('location_id'), [
            'class' => 'form-select select2',
            'placeholder' => '-- Pilih Lokasi --',
        ]) }}
    </div>

    <div class="col-md-6">
        {{ Form::label('assigned_to', 'Penanggung Jawab', ['class' => 'form-label']) }}
        {{ Form::select('assigned_to', $users ?? [], old('assigned_to'), [
            'class' => 'form-select select2',
            'placeholder' => '-- Pilih User --',
        ]) }}
    </div>

    {{-- ============================================================= --}}
    {{-- SECTION: KEUANGAN & PENYUSUTAN --}}
    {{-- ============================================================= --}}
    <div class="col-12 mt-3">
        <h6 class="text-uppercase text-muted fw-bold mb-2">
            <i class="fa fa-coins me-1"></i> Keuangan & Penyusutan
        </h6>
        <hr class="mt-0">
    </div>

    <div class="col-md-4">
        {{ Form::label('purchase_date', 'Tanggal Pembelian <span class="text-danger">*</span>', ['class' => 'form-label'], false) }}
        {{ Form::date('purchase_date', old('purchase_date'), [
            'class' => 'form-control' . ($errors->has('purchase_date') ? ' is-invalid' : ''),
            'required' => true,
        ]) }}
        @error('purchase_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        {{ Form::label('purchase_price', 'Harga Beli <span class="text-danger">*</span>', ['class' => 'form-label'], false) }}
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            {{ Form::text('purchase_price', old('purchase_price'), [
                'class' => 'form-control text-end currency' . ($errors->has('purchase_price') ? ' is-invalid' : ''),
                'placeholder' => '0',
                'required' => true,
                'data-type' => 'currency',
            ]) }}
            @error('purchase_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        {{ Form::label('salvage_value', 'Nilai Sisa', ['class' => 'form-label']) }}
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            {{ Form::text('salvage_value', old('salvage_value', 0), [
                'class' => 'form-control text-end currency' . ($errors->has('salvage_value') ? ' is-invalid' : ''),
                'placeholder' => '0',
                'data-type' => 'currency',
            ]) }}
            @error('salvage_value')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        {{ Form::label('useful_life_months', 'Umur Ekonomis (bulan) <span class="text-danger">*</span>', ['class' => 'form-label'], false) }}
        {{ Form::number('useful_life_months', old('useful_life_months', 60), [
            'class' => 'form-control' . ($errors->has('useful_life_months') ? ' is-invalid' : ''),
            'min' => 1,
            'required' => true,
        ]) }}
        @error('useful_life_months')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        {{ Form::label('depreciation_method', 'Metode Penyusutan <span class="text-danger">*</span>', ['class' => 'form-label'], false) }}
        {{ Form::select(
            'depreciation_method',
            [
                'straight_line' => 'Garis Lurus (Straight Line)',
                'declining_balance' => 'Saldo Menurun (Declining Balance)',
                'sum_of_years' => 'Jumlah Angka Tahun (Sum of Years)',
                'double_declining' => 'Saldo Menurun Ganda (Double Declining)',
            ],
            old('depreciation_method', 'straight_line'),
            [
                'class' => 'form-select' . ($errors->has('depreciation_method') ? ' is-invalid' : ''),
                'required' => true,
            ],
        ) }}
        @error('depreciation_method')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        {{ Form::label('warranty_end', 'Akhir Garansi', ['class' => 'form-label']) }}
        {{ Form::date('warranty_end', old('warranty_end'), [
            'class' => 'form-control' . ($errors->has('warranty_end') ? ' is-invalid' : ''),
        ]) }}
        @error('warranty_end')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- ============================================================= --}}
    {{-- SECTION: AKUN COA --}}
    {{-- ============================================================= --}}
    <div class="col-12 mt-3">
        <h6 class="text-uppercase text-muted fw-bold mb-2">
            <i class="fa fa-book me-1"></i> Akun Akuntansi (COA)
        </h6>
        <hr class="mt-0">
    </div>

    <div class="col-md-4">
        {{ Form::label('asset_chart_of_account_id', 'Akun Aset <span class="text-danger">*</span>', ['class' => 'form-label'], false) }}
        {{ Form::select('asset_chart_of_account_id', $accounts ?? [], old('asset_chart_of_account_id'), [
            'class' => 'form-select select2' . ($errors->has('asset_chart_of_account_id') ? ' is-invalid' : ''),
            'placeholder' => '-- Pilih Akun --',
            'required' => true,
        ]) }}
        @error('asset_chart_of_account_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        {{ Form::label('accumulated_depreciation_chart_of_account_id', 'Akun Akum. Penyusutan <span class="text-danger">*</span>', ['class' => 'form-label'], false) }}
        {{ Form::select(
            'accumulated_depreciation_chart_of_account_id',
            $accounts ?? [],
            old('accumulated_depreciation_chart_of_account_id'),
            [
                'class' =>
                    'form-select select2' . ($errors->has('accumulated_depreciation_chart_of_account_id') ? ' is-invalid' : ''),
                'placeholder' => '-- Pilih Akun --',
                'required' => true,
            ],
        ) }}
        @error('accumulated_depreciation_chart_of_account_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        {{ Form::label('depreciation_expense_chart_of_account_id', 'Akun Beban Penyusutan <span class="text-danger">*</span>', ['class' => 'form-label'], false) }}
        {{ Form::select(
            'depreciation_expense_chart_of_account_id',
            $accounts ?? [],
            old('depreciation_expense_chart_of_account_id'),
            [
                'class' =>
                    'form-select select2' . ($errors->has('depreciation_expense_chart_of_account_id') ? ' is-invalid' : ''),
                'placeholder' => '-- Pilih Akun --',
                'required' => true,
            ],
        ) }}
        @error('depreciation_expense_chart_of_account_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- ============================================================= --}}
    {{-- SECTION: STATUS & KONDISI --}}
    {{-- ============================================================= --}}
    <div class="col-12 mt-3">
        <h6 class="text-uppercase text-muted fw-bold mb-2">
            <i class="fa fa-info-circle me-1"></i> Status & Kondisi
        </h6>
        <hr class="mt-0">
    </div>

    <div class="col-md-4">
        {{ Form::label('status', 'Status <span class="text-danger">*</span>', ['class' => 'form-label'], false) }}
        {{ Form::select(
            'status',
            [
                'active' => 'Aktif',
                'inactive' => 'Tidak Aktif',
                'maintenance' => 'Perawatan',
                'disposed' => 'Dihapus',
            ],
            old('status', 'active'),
            [
                'class' => 'form-select' . ($errors->has('status') ? ' is-invalid' : ''),
                'required' => true,
            ],
        ) }}
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        {{ Form::label('condition', 'Kondisi <span class="text-danger">*</span>', ['class' => 'form-label'], false) }}
        {{ Form::select(
            'condition',
            [
                'good' => 'Baik',
                'fair' => 'Cukup',
                'poor' => 'Rusak Ringan',
                'broken' => 'Rusak Berat',
            ],
            old('condition', 'good'),
            [
                'class' => 'form-select' . ($errors->has('condition') ? ' is-invalid' : ''),
                'required' => true,
            ],
        ) }}
        @error('condition')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        {{ Form::label('is_active', 'Status Aktif', ['class' => 'form-label d-block']) }}
        <div class="form-check form-switch mt-2">
            {{ Form::hidden('is_active', 0) }}
            {{ Form::checkbox('is_active', 1, old('is_active', true), [
                'class' => 'form-check-input',
                'id' => 'is_active',
            ]) }}
            <label class="form-check-label" for="is_active">Aktifkan aset ini</label>
        </div>
    </div>

    {{-- ============================================================= --}}
    {{-- SECTION: LAMPIRAN & CATATAN --}}
    {{-- ============================================================= --}}
    <div class="col-12 mt-3">
        <h6 class="text-uppercase text-muted fw-bold mb-2">
            <i class="fa fa-paperclip me-1"></i> Lampiran & Catatan
        </h6>
        <hr class="mt-0">
    </div>

    <div class="col-md-6">
        {{ Form::label('photo_url', 'Foto (URL)', ['class' => 'form-label']) }}
        {{ Form::text('photo_url', old('photo_url'), [
            'class' => 'form-control' . ($errors->has('photo_url') ? ' is-invalid' : ''),
            'placeholder' => 'https://...',
        ]) }}
        @error('photo_url')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        {{ Form::label('notes', 'Catatan', ['class' => 'form-label']) }}
        {{ Form::textarea('notes', old('notes'), [
            'class' => 'form-control' . ($errors->has('notes') ? ' is-invalid' : ''),
            'rows' => 3,
            'placeholder' => 'Catatan tambahan...',
        ]) }}
        @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>
