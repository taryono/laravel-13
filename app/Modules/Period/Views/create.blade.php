{{ Form::open(['method' => 'POST', 'route' => ['period.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('Period::_form')
{{ Form::close() }}
