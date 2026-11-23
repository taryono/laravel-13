{{ Form::open(['method' => 'POST', 'route' => ['promotion_batch.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('PromotionBatch::form')
{{ Form::close() }}
