{{ Form::open(['method' => 'POST', 'route' => ['slider.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('Slider::form')
{{ Form::close() }}
