{{ Form::model($period, ['method' => 'PUT', 'route' => ['period.update', $period->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('Period::_form')
{{ Form::close() }}
<!-- /.row -->
