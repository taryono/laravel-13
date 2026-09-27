{{ Form::model($role, ['method' => 'PUT', 'route' => ['role.update', $role->id], 'class' => 'form-horizontal']) }}
@include('Role::form', ['role' => $role])
{{ Form::close() }}
