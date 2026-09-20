{{ Form::model($slider, ['method' => 'PUT', 'route' => ['slider.update', $slider->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('Slider::form')
{{ Form::close() }}
