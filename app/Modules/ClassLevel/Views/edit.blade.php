{{ Form::model($class_level, ['method' => 'PUT', 'route' => ['class_level.update', $class_level->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}

{{ Form::close() }}
