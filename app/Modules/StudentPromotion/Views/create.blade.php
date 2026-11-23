{{ Form::open(['method' => 'POST', 'route' => ['student_promotion.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Kode Batch <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', 'PROMO-' . date('Y') . '-' . date('Y', strtotime('+1 year'))) }}" required>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Nama Batch <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', 'Promosi Akhir Tahun ' . date('Y') . '/' . date('Y', strtotime('+1 year'))) }}"
            required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Tahun Ajaran Asal <span class="text-danger">*</span></label>
        <select name="from_academic_year_id" class="form-select @error('from_academic_year_id') is-invalid @enderror"
            required>
            <option value="">-- Pilih --</option>
            @foreach ($academicYears as $year)
                <option value="{{ $year->id }}" {{ old('from_academic_year_id') == $year->id ? 'selected' : '' }}>
                    {{ $year->name }}
                </option>
            @endforeach
        </select>
        @error('from_academic_year_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Tahun Ajaran Tujuan <span class="text-danger">*</span></label>
        <select name="to_academic_year_id" class="form-select @error('to_academic_year_id') is-invalid @enderror"
            required>
            <option value="">-- Pilih --</option>
            @foreach ($academicYears as $year)
                <option value="{{ $year->id }}" {{ old('to_academic_year_id') == $year->id ? 'selected' : '' }}>
                    {{ $year->name }}
                </option>
            @endforeach
        </select>
        @error('to_academic_year_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{ Form::close() }}
