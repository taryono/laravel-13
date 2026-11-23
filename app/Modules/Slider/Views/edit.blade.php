{{ Form::model($slider, ['method' => 'PUT', 'route' => ['slider.update', $slider->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('Slider::form')
{{ Form::close() }}
