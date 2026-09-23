{{ Form::model($academic_year, ['method' => 'PUT', 'route' => ['academic_year.update', $academic_year->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('AcademicYear::_form')
{{ Form::close() }}
<!-- /.row -->
