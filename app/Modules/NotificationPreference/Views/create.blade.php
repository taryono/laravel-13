{{ Form::open(['method' => 'POST', 'route' => ['notification_preference.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('NotificationPreference::form', ['notification_preference' => $notification_preference])
{{ Form::close() }}
