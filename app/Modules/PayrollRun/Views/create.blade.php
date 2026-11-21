{{ Form::open(['method' => 'POST', 'route' => ['payroll_run.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('PayrollRun::form', ['payroll_period' => $organizations, 'organizations' => $organizations])
{{ Form::close() }}
