{{ Form::open(['method' => 'POST', 'route' => ['role.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('Role::form')
{{ Form::close() }}
