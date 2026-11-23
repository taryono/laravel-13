{{ Form::open(['method' => 'POST', 'route' => ['payroll_period.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('PayrollPeriod::form', ['payrollPeriod' => new \Models\PayrollPeriod()])
{{ Form::close() }}
