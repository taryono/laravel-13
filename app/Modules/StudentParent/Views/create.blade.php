{{ Form::open(['method' => 'POST', 'route' => ['student_parent.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('StudentParent::form')
{{ Form::close() }}
