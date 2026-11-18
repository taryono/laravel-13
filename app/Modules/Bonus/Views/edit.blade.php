{{ Form::model($bonus, ['method' => 'PUT', 'route' => ['bonus.update', $bonus?->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('Bonus::form', [
    'employees' => $employees,
    'periods' => $periods,
])
{{ Form::close() }}
