<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0">Tambah {{ say('Academic Semester') }}</h4>
    </div>
    <div class="card-body">
        {{ Form::open(['method' => 'POST', 'route' => ['academic_semester.store'], 'class' => 'form-horizontal', 'id' => 'formAcademicSemester', 'enctype' => 'multipart/form-data', 'autocomplete' => 'off']) }}
        @include('AcademicSemester::_form')
        {{ Form::close() }}
    </div>
</div>
