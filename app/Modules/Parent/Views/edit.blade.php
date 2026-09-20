{{ Form::model($parent, ['method' => 'PUT', 'route' => ['parent.update', $parent->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('Parent::_form')
{{ Form::close() }}
