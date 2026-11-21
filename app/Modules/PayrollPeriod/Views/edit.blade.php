{{ Form::model($payroll_period, ['method' => 'PUT', 'route' => ['payroll_period.update', $payroll_run->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('PayrollPeriod::form', ['payrollPeriod' => new \Models\PayrollPeriod()])
{{ Form::close() }}
