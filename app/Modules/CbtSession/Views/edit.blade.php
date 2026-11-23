{{ Form::model($session, ['method' => 'PUT', 'route' => ['cbt_session.update', $session->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('CbtSession::form')
{{ Form::close() }}
