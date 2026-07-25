@extends(layout())

@section('title', $title)
@section('content')

    @include("{$path}::list")
@stop
