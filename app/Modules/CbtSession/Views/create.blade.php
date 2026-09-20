{{ Form::open(['method' => 'POST', 'route' => ['cbt_session.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('CbtSession::form')
{{ Form::close() }}
