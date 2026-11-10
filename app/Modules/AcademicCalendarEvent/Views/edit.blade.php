{{ Form::model($academic_calendar_event, ['method' => 'PUT', 'route' => ['academic_calendar_event.update', $academic_calendar_event->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
{{-- Kolom tersembunyi (sudah ada datanya) --}}
<input type="hidden" name="organization_id" value="{{ $organizationId }}">
<input type="hidden" name="school_id" value="{{ $schoolId }}">
<input type="hidden" name="academic_year_id" value="{{ $academicYearId }}">

<div class="row">
    {{-- Judul --}}
    <div class="col-md-8 mb-3">
        <label for="title" class="form-label">Judul Event <span class="text-danger">*</span></label>
        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
            value="{{ old('title') }}" maxlength="200" required>
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Tipe Event --}}
    <div class="col-md-4 mb-3">
        <label for="event_type" class="form-label">Tipe Event</label>
        <select name="event_type" id="event_type" class="form-select @error('event_type') is-invalid @enderror">
            <option value="">-- Pilih Tipe --</option>
            <option value="academic" {{ old('event_type') == 'academic' ? 'selected' : '' }}>Akademik</option>
            <option value="meeting" {{ old('event_type') == 'meeting' ? 'selected' : '' }}>Rapat</option>
            <option value="holiday" {{ old('event_type') == 'holiday' ? 'selected' : '' }}>Libur</option>
            <option value="activity" {{ old('event_type') == 'activity' ? 'selected' : '' }}>Kegiatan</option>
            <option value="other" {{ old('event_type') == 'other' ? 'selected' : '' }}>Lainnya</option>
        </select>
        @error('event_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Tanggal Mulai --}}
    <div class="col-md-4 mb-3">
        <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
        <input type="date" name="start_date" id="start_date"
            class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
        @error('start_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Tanggal Selesai --}}
    <div class="col-md-4 mb-3">
        <label for="end_date" class="form-label">Tanggal Selesai</label>
        <input type="date" name="end_date" id="end_date"
            class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
        @error('end_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Sepanjang Hari --}}
    <div class="col-md-4 mb-3 align-self-end">
        <div class="form-check form-switch">
            <input type="hidden" name="is_all_day" value="0">
            <input type="checkbox" name="is_all_day" id="is_all_day" class="form-check-input" value="1"
                {{ old('is_all_day') ? 'checked' : '' }}>
            <label for="is_all_day" class="form-check-label">Sepanjang Hari</label>
        </div>
    </div>

    {{-- Jam Mulai --}}
    <div class="col-md-4 mb-3 time-field">
        <label for="start_time" class="form-label">Jam Mulai</label>
        <input type="time" name="start_time" id="start_time"
            class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}">
        @error('start_time')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Jam Selesai --}}
    <div class="col-md-4 mb-3 time-field">
        <label for="end_time" class="form-label">Jam Selesai</label>
        <input type="time" name="end_time" id="end_time"
            class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}">
        @error('end_time')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Libur --}}
    <div class="col-md-4 mb-3 align-self-end">
        <div class="form-check form-switch">
            <input type="hidden" name="is_holiday" value="0">
            <input type="checkbox" name="is_holiday" id="is_holiday" class="form-check-input" value="1"
                {{ old('is_holiday') ? 'checked' : '' }}>
            <label for="is_holiday" class="form-check-label">Sebagai Hari Libur</label>
        </div>
    </div>

    {{-- Lokasi --}}
    <div class="col-md-6 mb-3">
        <label for="location" class="form-label">Lokasi</label>
        <input type="text" name="location" id="location"
            class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}"
            maxlength="200">
        @error('location')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Warna --}}
    <div class="col-md-3 mb-3">
        <label for="color" class="form-label">Warna Label</label>
        <input type="color" name="color" id="color"
            class="form-control form-control-color @error('color') is-invalid @enderror"
            value="{{ old('color', '#3b82f6') }}">
        @error('color')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Status Aktif --}}
    <div class="col-md-3 mb-3 align-self-end">
        <div class="form-check form-switch">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                {{ old('is_active', true) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Aktif</label>
        </div>
    </div>

    {{-- Keterangan --}}
    <div class="col-12 mb-3">
        <label for="description" class="form-label">Keterangan / Deskripsi</label>
        <textarea name="description" id="description" rows="4"
            class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Lampiran URL --}}
    <div class="col-12 mb-3">
        <label for="attachment_url" class="form-label">Tautan Lampiran</label>
        <input type="url" name="attachment_url" id="attachment_url"
            class="form-control @error('attachment_url') is-invalid @enderror" value="{{ old('attachment_url') }}"
            placeholder="https://...">
        @error('attachment_url')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
{{ Form::close() }}
<!-- /.row -->
