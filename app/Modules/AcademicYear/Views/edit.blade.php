{{ Form::model($academic - year, ['method' => 'PUT', 'route' => ['academic_year.update', $academic - year->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('AcademicYear::_form')
{{ Form::close() }}
<!-- /.row -->
