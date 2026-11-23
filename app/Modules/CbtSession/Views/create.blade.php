{{ Form::open(['method' => 'POST', 'route' => ['cbt_session.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('CbtSession::form')
{{ Form::close() }}
