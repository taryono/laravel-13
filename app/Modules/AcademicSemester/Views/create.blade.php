<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0">Tambah {{ say('Academic Semester') }}</h4>
    </div>
    <div class="card-body">
        {{ Form::open(['method' => 'POST', 'route' => ['academic_semester.store'], 'class' => 'form-horizontal form-validation', 'id' => 'formAcademicSemester', 'enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'data-validate' => 'true', 'novalidate' => true]) }}
        @include('AcademicSemester::_form')
        {{ Form::close() }}
    </div>
</div>
