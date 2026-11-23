{{ Form::open(['method' => 'POST', 'route' => ['bonus.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('Bonus::form', [
    'employees' => $employees,
    'periods' => $periods,
])
{{ Form::close() }}
