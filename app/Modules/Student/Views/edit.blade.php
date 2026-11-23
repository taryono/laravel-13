{{ Form::model($student, ['method' => 'PUT', 'route' => ['student.update', $student->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('Student::_form')
{{ Form::close() }}
