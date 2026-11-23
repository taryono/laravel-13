{{ Form::model($promotion_criteria, ['method' => 'PUT', 'route' => ['promotion_criteria.update', $promotion_criteria->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('PromotionCriteria::form', ['promotion_criteria' => $promotion_criteria])
{{ Form::close() }}
<!-- /.row -->
