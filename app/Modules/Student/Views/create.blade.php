{{ Form::open(['method' => 'POST', 'route' => ['student.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('Student::_form')
{{ Form::close() }}
