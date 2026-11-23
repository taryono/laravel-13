{{ Form::open(['method' => 'POST', 'route' => ['academic_year.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('AcademicYear::_form')
{{ Form::close() }}
