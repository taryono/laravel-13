{{ Form::open(['method' => 'POST', 'route' => ['student.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('Student::_form')
{{ Form::close() }}
