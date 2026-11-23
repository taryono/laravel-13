{{ Form::model($student_parent, ['method' => 'PUT', 'route' => ['student_parent.update', $student_parent->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('StudentParent::form')
{{ Form::close() }}
