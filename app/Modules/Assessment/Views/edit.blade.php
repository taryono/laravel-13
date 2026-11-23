{{ Form::model($assessment, ['method' => 'PUT', 'route' => ['assessment.update', $assessment->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('Assessment::form')
{{ Form::close() }}
