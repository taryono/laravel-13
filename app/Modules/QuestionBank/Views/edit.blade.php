{{ Form::model($question - bank, ['method' => 'PUT', 'route' => ['question_bank.update', $question - bank->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('QuestionBank::form')
{{ Form::close() }}
