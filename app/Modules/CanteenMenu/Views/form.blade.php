@php
    $categories = [
        'food' => 'Makanan',
        'drink' => 'Minuman',
        'snack' => 'Snack',
        'stationery' => 'Alat Tulis',
        'other' => 'Lainnya',
    ];
    $isEdit = isset($canteen_menu) && $canteen_menu->exists;
@endphp

<div class="row g-3">
    {{-- Tenant --}}
    <div class="col-md-6">
        {{ Form::label('canteen_tenant_id', 'Tenant Kantin', ['class' => 'form-label']) }} <span
            class="text-danger">*</span>
        {{ Form::select('canteen_tenant_id', $tenants, null, [
            'class' => 'form-select' . ($errors->has('canteen_tenant_id') ? ' is-invalid' : ''),
            'placeholder' => '-- Pilih Tenant --',
            'required',
        ]) }}
        @error('canteen_tenant_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Kategori --}}
    <div class="col-md-6">
        {{ Form::label('category', 'Kategori', ['class' => 'form-label']) }} <span class="text-danger">*</span>
        {{ Form::select('category', $categories, $isEdit ? null : 'food', [
            'class' => 'form-select' . ($errors->has('category') ? ' is-invalid' : ''),
            'required',
        ]) }}
        @error('category')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Nama --}}
    <div class="col-12">
        {{ Form::label('name', 'Nama Menu', ['class' => 'form-label']) }} <span class="text-danger">*</span>
        {{ Form::text('name', null, [
            'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
            'placeholder' => 'Contoh: Nasi Goreng Spesial',
            'maxlength' => 150,
            'required',
        ]) }}
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Harga Jual --}}
    <div class="col-md-6">
        {{ Form::label('price', 'Harga Jual', ['class' => 'form-label']) }} <span class="text-danger">*</span>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            {{ Form::number('price', null, [
                'class' => 'form-control' . ($errors->has('price') ? ' is-invalid' : ''),
                'min' => 0,
                'step' => '0.01',
                'required',
            ]) }}
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Harga Modal --}}
    <div class="col-md-6">
        {{ Form::label('cost_price', 'Harga Modal', ['class' => 'form-label']) }}
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            {{ Form::number('cost_price', $isEdit ? null : 0, [
                'class' => 'form-control' . ($errors->has('cost_price') ? ' is-invalid' : ''),
                'min' => 0,
                'step' => '0.01',
            ]) }}
            @error('cost_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Foto --}}
    <div class="col-12">
        {{ Form::label('photo', 'Foto Menu', ['class' => 'form-label']) }}
        {{ Form::file('photo', [
            'class' => 'form-control' . ($errors->has('photo') ? ' is-invalid' : ''),
            'accept' => 'image/*',
            'id' => 'photo',
        ]) }}
        <div class="form-text">Format: JPG, PNG, WEBP. Maksimal 2 MB.</div>
        @error('photo')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        <div class="mt-2">
            <img id="photo-preview" src="{{ $isEdit && $canteen_menu->photo_url ? $canteen_menu->photo_url : '' }}"
                alt="Preview" class="img-thumbnail {{ $isEdit && $canteen_menu->photo_url ? '' : 'd-none' }}"
                style="max-height: 180px;">
        </div>

        @if ($isEdit && $canteen_menu->photo_url)
            <div class="form-check mt-2">
                {{ Form::checkbox('remove_photo', 1, false, ['class' => 'form-check-input', 'id' => 'remove_photo']) }}
                {{ Form::label('remove_photo', 'Hapus foto saat ini', ['class' => 'form-check-label']) }}
            </div>
        @endif
    </div>

    {{-- Tersedia --}}
    <div class="col-12">
        <div class="form-check form-switch">
            {{ Form::hidden('is_available', 0) }}
            {{ Form::checkbox('is_available', 1, $isEdit ? null : true, ['class' => 'form-check-input', 'id' => 'is_available', 'role' => 'switch']) }}
            {{ Form::label('is_available', 'Menu tersedia / dijual', ['class' => 'form-check-label']) }}
        </div>
    </div>
</div>

<hr class="my-4">

@push('scripts')
    <script>
        document.getElementById('photo')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const img = document.getElementById('photo-preview');
            if (file) {
                img.src = URL.createObjectURL(file);
                img.classList.remove('d-none');
            }
        });
    </script>
@endpush
