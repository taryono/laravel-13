{{ Form::open(['method' => 'POST', 'route' => ['role.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('Role::form')
{{ Form::close() }}
