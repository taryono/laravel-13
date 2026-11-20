<div class="card border-0">
    <div class="card-header bg-transparent border-0 pb-0">
        <h5 class="card-title mb-0 fw-bold text-primary">Tambah Data Payslip Baru</h5>
        <small class="text-muted">Isi informasi slip gaji karyawan untuk periode terkait</small>
    </div>
    <div class="card-body">
        {{ Form::open(['method' => 'POST', 'route' => ['payslip.store'], 'class' => 'form-horizontal form-validation', 'id' => 'formPayslip', 'enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'data-validate' => 'true', 'novalidate' => true]) }}
        @include('Payslip::_form')
        {{ Form::close() }}
    </div>
</div>
