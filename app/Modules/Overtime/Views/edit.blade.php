{{ Form::model($overtime, ['method' => 'PUT', 'route' => ['overtime.update', $overtime->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}

{{ Form::close() }}
<!-- /.row -->
