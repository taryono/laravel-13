{{ Form::model($demoRequest, ['id' => 'formDemoRequest', 'method' => 'PUT', 'route' => ['demo_request.update', $demoRequest->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
<div class="row g-3">
    {{-- Nama Sekolah --}}
    <div class="col-md-6">
        <label for="school_name" class="form-label fw-semibold"><i class="fa-solid fa-school text-primary me-1"></i> Nama Sekolah / Institusi <span class="text-danger">*</span></label>
        {{ Form::text('school_name', null, ['class' => 'form-control', 'required']) }}
    </div>

    {{-- Nama Penanggung Jawab --}}
    <div class="col-md-6">
        <label for="person_name" class="form-label fw-semibold"><i class="fa-solid fa-user text-primary me-1"></i> Penanggung Jawab (PIC) <span class="text-danger">*</span></label>
        {{ Form::text('person_name', null, ['class' => 'form-control', 'required']) }}
    </div>

    {{-- Jabatan --}}
    <div class="col-md-6">
        <label for="position" class="form-label fw-semibold"><i class="fa-solid fa-id-badge text-primary me-1"></i> Jabatan PIC <span class="text-danger">*</span></label>
        {{ Form::text('position', null, ['class' => 'form-control', 'required']) }}
    </div>

    {{-- Pilihan Paket --}}
    <div class="col-md-6">
        <label for="package" class="form-label fw-semibold"><i class="fa-solid fa-box-open text-primary me-1"></i> Pilihan Paket <span class="text-danger">*</span></label>
        {{ Form::select('package', [
            'starter' => 'Starter (Hingga 300 Siswa)',
            'professional' => 'Professional (Hingga 1.000 Siswa)',
            'enterprise' => 'Enterprise (Kustomisasi Khusus)',
        ], null, ['class' => 'form-select', 'required']) }}
    </div>

    {{-- Email --}}
    <div class="col-md-6">
        <label for="email" class="form-label fw-semibold"><i class="fa-regular fa-envelope text-primary me-1"></i> Email <span class="text-danger">*</span></label>
        {{ Form::email('email', null, ['class' => 'form-control', 'required']) }}
    </div>

    {{-- Nomor Telepon / WA --}}
    <div class="col-md-6">
        <label for="phone" class="form-label fw-semibold"><i class="fa-brands fa-whatsapp text-success me-1"></i> Telepon / WhatsApp <span class="text-danger">*</span></label>
        {{ Form::text('phone', null, ['class' => 'form-control', 'required']) }}
    </div>

    {{-- Jadwal Demo --}}
    <div class="col-md-6">
        <label for="demo_datetime" class="form-label fw-semibold"><i class="fa-regular fa-calendar-days text-primary me-1"></i> Jadwal Presentasi Demo <span class="text-danger">*</span></label>
        {{ Form::input('datetime-local', 'demo_datetime', $demoRequest->demo_datetime ? \Carbon\Carbon::parse($demoRequest->demo_datetime)->format('Y-m-d\TH:i') : null, ['class' => 'form-control', 'required']) }}
    </div>

    {{-- Status Tindak Lanjut --}}
    <div class="col-md-6">
        <label for="status" class="form-label fw-semibold"><i class="fa-solid fa-signal text-primary me-1"></i> Status Tindak Lanjut <span class="text-danger">*</span></label>
        {{ Form::select('status', [
            'pending' => '🟡 Menunggu Konfirmasi (Pending)',
            'contacted' => '🔵 Sudah Dihubungi (Contacted)',
            'scheduled' => '🟢 Demo Terjadwal (Scheduled)',
            'completed' => '✅ Demo Selesai (Completed)',
            'cancelled' => '⚪ Dibatalkan (Cancelled)',
        ], null, ['class' => 'form-select', 'required']) }}
    </div>

    {{-- Pesan Kebutuhan Khusus --}}
    <div class="col-md-6">
        <label for="message" class="form-label fw-semibold"><i class="fa-regular fa-comment-dots text-primary me-1"></i> Catatan Kebutuhan Sekolah</label>
        {{ Form::textarea('message', null, ['class' => 'form-control', 'rows' => 3]) }}
    </div>

    {{-- Catatan Internal Admin / Sales --}}
    <div class="col-md-6">
        <label for="notes" class="form-label fw-semibold"><i class="fa-solid fa-notes-medical text-primary me-1"></i> Catatan Internal Admin / Sales</label>
        {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Tambahkan catatan follow up, respon sekolah, atau progres dealing...']) }}
    </div>
</div>
{{ Form::close() }}