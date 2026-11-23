{{ Form::open(['method' => 'POST', 'route' => ['fixed-asset.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('Asset::form')
{{ Form::close() }}
