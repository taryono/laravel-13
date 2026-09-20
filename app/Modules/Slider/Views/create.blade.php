{{ Form::open(['method' => 'POST', 'route' => ['slider.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('Slider::form')
{{ Form::close() }}
