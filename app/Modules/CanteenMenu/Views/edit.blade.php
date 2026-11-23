{{ Form::model($canteen_menu, ['method' => 'PUT', 'route' => ['canteen_menu.update', $canteen_menu->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('CanteenMenu::form')
{{ Form::close() }}
