{{ Form::open(['method' => 'POST', 'route' => ['teacher.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}

<div class="row">
    {{-- Organisasi --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Organisasi <span class="text-danger">*</span></label>
        {{ Form::select('organization_id', ['' => 'Pilih Organisasi'], null, [
            'class' => 'form-select',
            'id' => 'organization_id',
            'required' => true,
        ]) }}
    </div>

    {{-- Sekolah --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Sekolah</label>
        {{ Form::select('school_id', ['' => 'Pilih Sekolah'], null, [
            'class' => 'form-select',
            'id' => 'school_id',
        ]) }}
    </div>

    {{-- NIP --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">NIP</label>
        {{ Form::text('nip', null, [
            'class' => 'form-control',
            'id' => 'nip',
            'maxlength' => 50,
            'placeholder' => 'Masukkan NIP',
        ]) }}
    </div>

    {{-- Nama --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
        {{ Form::text('name', null, [
            'class' => 'form-control',
            'id' => 'name',
            'maxlength' => 150,
            'required' => true,
            'placeholder' => 'Nama lengkap guru',
        ]) }}
    </div>

    {{-- Akun Pengguna --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Akun Pengguna</label>
        {{ Form::select('user_id', ['' => 'Tidak Dihubungkan'], null, [
            'class' => 'form-select',
            'id' => 'user_id',
        ]) }}
    </div>

    {{-- Spesialisasi --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Spesialisasi</label>
        {{ Form::text('specialization', null, [
            'class' => 'form-control',
            'id' => 'specialization',
            'maxlength' => 100,
            'placeholder' => 'Contoh: Matematika',
        ]) }}
    </div>

    {{-- Mata Pelajaran --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Mata Pelajaran</label>
        {{ Form::text('subject', null, [
            'class' => 'form-control',
            'id' => 'subject',
            'placeholder' => 'Contoh: Matematika, Fisika',
        ]) }}
    </div>

    {{-- Tanggal Bergabung --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Tanggal Bergabung</label>
        {{ Form::date('join_date', null, [
            'class' => 'form-control',
            'id' => 'join_date',
        ]) }}
    </div>

    {{-- Status --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Status</label>
        {{ Form::select(
            'status',
            [
                'ACTIVE' => 'Aktif',
                'RESIGNED' => 'Mengundurkan Diri',
                'RETIRED' => 'Pensiun',
            ],
            'ACTIVE',
            [
                'class' => 'form-select',
                'id' => 'status',
            ],
        ) }}
    </div>

    {{-- Aktif --}}
    <div class="col-md-6 mb-3 align-self-center">
        <div class="form-check form-switch">
            {{ Form::checkbox('is_active', 1, true, [
                'class' => 'form-check-input',
                'id' => 'is_active',
            ]) }}
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
    </div>
</div>
{{ Form::close() }}
