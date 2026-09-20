{{ Form::open(['method' => 'POST', 'route' => ['parent.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('Parent::_form')
{{ Form::close() }}
