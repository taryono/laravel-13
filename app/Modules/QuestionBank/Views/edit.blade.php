{{ Form::model($question_bank, ['method' => 'PUT', 'route' => ['question_bank.update', $question_bank->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@include('QuestionBank::form')
{{ Form::close() }}
