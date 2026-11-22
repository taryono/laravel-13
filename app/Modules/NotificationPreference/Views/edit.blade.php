{{ Form::model($notification_preference, ['method' => 'PUT', 'route' => ['notification_preference.update', $notification_preference->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('NotificationPreference::form', ['notification_preference' => $notification_preference])
{{ Form::close() }}
