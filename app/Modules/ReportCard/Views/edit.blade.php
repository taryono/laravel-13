{{ Form::model($report_card, ['method' => 'PUT', 'route' => ['report_card.update', $report_card->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}

{{ Form::close() }}
<!-- /.row -->
