{{ Form::open(['method' => 'POST', 'route' => ['fixed-asset.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
@include('Asset::form')
{{ Form::close() }}
