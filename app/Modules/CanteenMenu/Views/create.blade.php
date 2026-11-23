{{ Form::open(['method' => 'POST', 'route' => ['canteen_menu.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('CanteenMenu::form')
{{ Form::close() }}
