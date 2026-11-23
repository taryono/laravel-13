{{ Form::model($report_card, ['method' => 'PUT', 'route' => ['report_card.update', $report_card->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}

{{ Form::close() }}
<!-- /.row -->
