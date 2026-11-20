<div class="card border-0">
    <div class="card-header bg-transparent border-0 pb-0">
        <h5 class="card-title mb-0 fw-bold text-primary">Edit Payslip: {{ $payslip->payslip_number }}</h5>
        <small class="text-muted">Perbarui data slip gaji dan status pengiriman</small>
    </div>
    <div class="card-body">
        {{ Form::model($payslip, ['method' => 'PUT', 'route' => ['payslip.update', $payslip->id], 'class' => 'form-horizontal form-validation', 'id' => 'formPayslip', 'enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'data-validate' => 'true', 'novalidate' => true]) }}
        @include('Payslip::_form')
        {{ Form::close() }}
    </div>
</div>
