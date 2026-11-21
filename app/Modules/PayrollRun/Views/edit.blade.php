{{ Form::model($payroll_run, ['method' => 'PUT', 'route' => ['payroll_run.update', $payroll_run->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('PayrollRun::form', ['payroll_period' => $organizations, 'organizations' => $organizations])
{{ Form::close() }}
