{{ Form::model($role, ['method' => 'PUT', 'route' => ['role.update', $role->id], 'class' => 'form-horizontal form-validation']) }}
@include('Role::form', ['role' => $role])
{{ Form::close() }}
