{{ Form::open(['method' => 'POST', 'route' => ['assessment.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('Assessment::form')
{{ Form::close() }}
