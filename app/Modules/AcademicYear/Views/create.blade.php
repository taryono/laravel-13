{{ Form::open(['method' => 'POST', 'route' => ['academic_year.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('AcademicYear::_form')
{{ Form::close() }}
