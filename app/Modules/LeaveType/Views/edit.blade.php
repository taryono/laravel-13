{{ Form::model($leave_type, ['method' => 'PUT', 'route' => ['leave_type.update', $leave_type->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
<div class="row">
    {{-- Organization ID --}}
    <div class="col-md-6 mb-3">
        <label for="organization_id" class="form-label">Organisasi <span class="text-danger">*</span></label>
        {{ Form::select('organization_id', $organizations ?? [], old('organization_id'), [
            'class' => 'form-control' . ($errors->has('organization_id') ? ' is-invalid' : ''),
            'required' => 'required',
            'placeholder' => '-- Pilih Organisasi --',
        ]) }}
        @if ($errors->has('organization_id'))
            <div class="invalid-feedback">{{ $errors->first('organization_id') }}</div>
        @endif
    </div>

    {{-- Code --}}
    <div class="col-md-6 mb-3">
        <label for="code" class="form-label">Kode <span class="text-danger">*</span></label>
        {{ Form::text('code', old('code'), [
            'class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : ''),
            'maxlength' => 50,
            'required' => 'required',
            'placeholder' => 'Misal: ANNUAL, SICK',
        ]) }}
        @if ($errors->has('code'))
            <div class="invalid-feedback">{{ $errors->first('code') }}</div>
        @endif
    </div>

    {{-- Name --}}
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Nama Jenis Cuti <span class="text-danger">*</span></label>
        {{ Form::text('name', old('name'), [
            'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
            'maxlength' => 255,
            'required' => 'required',
            'placeholder' => 'Misal: Cuti Tahunan',
        ]) }}
        @if ($errors->has('name'))
            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
        @endif
    </div>

    {{-- Default Days --}}
    <div class="col-md-6 mb-3">
        <label for="default_days" class="form-label">Jumlah Hari Default <span class="text-danger">*</span></label>
        {{ Form::number('default_days', old('default_days', 0.0), [
            'class' => 'form-control' . ($errors->has('default_days') ? ' is-invalid' : ''),
            'step' => '0.01',
            'min' => '0',
            'required' => 'required',
        ]) }}
        @if ($errors->has('default_days'))
            <div class="invalid-feedback">{{ $errors->first('default_days') }}</div>
        @endif
    </div>

    {{-- Max Consecutive Days --}}
    <div class="col-md-6 mb-3">
        <label for="max_consecutive_days" class="form-label">Maksimal Hari Berturut-turut</label>
        {{ Form::number('max_consecutive_days', old('max_consecutive_days'), [
            'class' => 'form-control' . ($errors->has('max_consecutive_days') ? ' is-invalid' : ''),
            'min' => '0',
            'placeholder' => 'Kosongkan = tidak dibatasi',
        ]) }}
        @if ($errors->has('max_consecutive_days'))
            <div class="invalid-feedback">{{ $errors->first('max_consecutive_days') }}</div>
        @endif
    </div>

    {{-- Min Working Days Before --}}
    <div class="col-md-6 mb-3">
        <label for="min_working_days_before" class="form-label">Min Hari Kerja Sebelumnya</label>
        {{ Form::number('min_working_days_before', old('min_working_days_before'), [
            'class' => 'form-control' . ($errors->has('min_working_days_before') ? ' is-invalid' : ''),
            'min' => '0',
            'placeholder' => 'Kosongkan = tidak dibatasi',
        ]) }}
        @if ($errors->has('min_working_days_before'))
            <div class="invalid-feedback">{{ $errors->first('min_working_days_before') }}</div>
        @endif
    </div>

    {{-- Is Paid --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Status Pembayaran</label>
        <div class="form-check mt-2">
            {{ Form::radio('is_paid', '1', old('is_paid', true), ['class' => 'form-check-input', 'id' => 'is_paid_1']) }}
            <label class="form-check-label" for="is_paid_1">Dibayar</label>
        </div>
        <div class="form-check">
            {{ Form::radio('is_paid', '0', old('is_paid') === '0', ['class' => 'form-check-input', 'id' => 'is_paid_0']) }}
            <label class="form-check-label" for="is_paid_0">Tidak Dibayar</label>
        </div>
        @if ($errors->has('is_paid'))
            <div class="text-danger small mt-1">{{ $errors->first('is_paid') }}</div>
        @endif
    </div>

    {{-- Requires Attachment --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Wajib Lampiran</label>
        <div class="form-check mt-2">
            {{ Form::radio('requires_attachment', '1', old('requires_attachment') === '1', ['class' => 'form-check-input', 'id' => 'req_attach_1']) }}
            <label class="form-check-label" for="req_attach_1">Ya</label>
        </div>
        <div class="form-check">
            {{ Form::radio('requires_attachment', '0', old('requires_attachment', false), ['class' => 'form-check-input', 'id' => 'req_attach_0']) }}
            <label class="form-check-label" for="req_attach_0">Tidak</label>
        </div>
        @if ($errors->has('requires_attachment'))
            <div class="text-danger small mt-1">{{ $errors->first('requires_attachment') }}</div>
        @endif
    </div>

    {{-- Is Active --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Status Aktif</label>
        <div class="form-check mt-2">
            {{ Form::radio('is_active', '1', old('is_active', true), ['class' => 'form-check-input', 'id' => 'is_active_1']) }}
            <label class="form-check-label" for="is_active_1">Aktif</label>
        </div>
        <div class="form-check">
            {{ Form::radio('is_active', '0', old('is_active') === '0', ['class' => 'form-check-input', 'id' => 'is_active_0']) }}
            <label class="form-check-label" for="is_active_0">Tidak Aktif</label>
        </div>
        @if ($errors->has('is_active'))
            <div class="text-danger small mt-1">{{ $errors->first('is_active') }}</div>
        @endif
    </div>
</div>
{{ Form::close() }}
