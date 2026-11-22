{{ Form::model($notification_template, ['method' => 'PUT', 'route' => ['notification_template.update', $notification_template->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}

{{ Form::close() }}
