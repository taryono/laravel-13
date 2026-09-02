{{ Form::model($overtime, ['method' => 'PUT', 'route' => ['overtime.update', $overtime->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}

{{ Form::close() }}
<!-- /.row -->
