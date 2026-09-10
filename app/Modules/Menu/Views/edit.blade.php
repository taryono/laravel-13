{{ Form::model($menu, ['method' => 'PUT', 'route' => ['menu.update', $menu->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}

{{ Form::close() }}
