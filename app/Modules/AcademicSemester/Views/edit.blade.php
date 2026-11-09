<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0">Edit {{ say('Academic Semester') }}</h4>
    </div>
    <div class="card-body">
        {{ Form::model($academic_semester, ['method' => 'PUT', 'route' => ['academic_semester.update', $academic_semester->id], 'class' => 'form-horizontal', 'id' => 'formAcademicSemester', 'enctype' => 'multipart/form-data', 'autocomplete' => 'off']) }}
        @include('AcademicSemester::_form')
        {{ Form::close() }}
    </div>
</div>
