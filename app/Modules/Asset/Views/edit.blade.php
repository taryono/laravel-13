{{ Form::model($asset, ['method' => 'PUT', 'route' => ['asset.update', $asset->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('Asset::form', ['asset' => $asset])
{{ Form::close() }}
<!-- /.row -->
