<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0">Edit {{ say('Academic Semester') }}</h4>
    </div>
    <div class="card-body">
        {{ Form::model($academic_semester, ['method' => 'PUT', 'route' => ['academic_semester.update', $academic_semester->id], 'class' => 'form-horizontal form-validation', 'id' => 'formAcademicSemester', 'enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'data-validate' => 'true', 'novalidate' => true]) }}
        @include('AcademicSemester::_form')
        {{ Form::close() }}
    </div>
</div>
