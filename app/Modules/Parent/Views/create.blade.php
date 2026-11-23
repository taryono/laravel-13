{{ Form::open(['method' => 'POST', 'route' => ['parent.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('Parent::_form')
{{ Form::close() }}
