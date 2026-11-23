{{ Form::open(['method' => 'POST', 'route' => ['promotion_criteria.store'], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('PromotionCriteria::form')
{{ Form::close() }}
