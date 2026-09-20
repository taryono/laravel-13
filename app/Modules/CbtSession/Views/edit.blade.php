{{ Form::model($session, ['method' => 'PUT', 'route' => ['cbt_session.update', $session->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('CbtSession::form')
{{ Form::close() }}
