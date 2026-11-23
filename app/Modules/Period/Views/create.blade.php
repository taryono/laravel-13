{{ Form::open(['method' => 'POST', 'route' => ['period.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('Period::_form')
{{ Form::close() }}
