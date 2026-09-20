{{ Form::model($student, ['method' => 'PUT', 'route' => ['student.update', $student->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('Student::_form')
{{ Form::close() }}
