{{ Form::model($parent, ['method' => 'PUT', 'route' => ['parent.update', $parent->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('Parent::_form')
{{ Form::close() }}
