 {{ Form::model($studentClassroom, ['method' => 'PUT', 'route' => ['student_classroom.update', $studentClassroom->id], 'class' => 'form-horizontal']) }}

 <div class="mb-6">
     {{ Form::label('student_id', 'Nama Siswa', ['class' => 'form-label']) }}
     {{ Form::select('student_id', $students, null, [
         'class' => 'form-select' . ($errors->has('student_id') ? ' is-invalid' : ''),
         'required',
     ]) }}
     @error('student_id')
         <div class="invalid-feedback d-block">{{ $message }}</div>
     @enderror
 </div>

 <div class="mb-6">
     {{ Form::label('classroom_id', 'Kelas', ['class' => 'form-label']) }}
     {{ Form::select('classroom_id', $classrooms, null, [
         'class' => 'form-select' . ($errors->has('classroom_id') ? ' is-invalid' : ''),
         'required',
     ]) }}
     @error('classroom_id')
         <div class="invalid-feedback d-block">{{ $message }}</div>
     @enderror
 </div>

 <div class="mb-6">
     {{ Form::label('academic_year_id', 'Tahun Akademik', ['class' => 'form-label']) }}
     {{ Form::select('academic_year_id', $academicYears, null, [
         'class' => 'form-select' . ($errors->has('academic_year_id') ? ' is-invalid' : ''),
         'required',
     ]) }}
     @error('academic_year_id')
         <div class="invalid-feedback d-block">{{ $message }}</div>
     @enderror
 </div>

 <div class="mb-6">
     {{ Form::label('enrollment_date', 'Tanggal Pendaftaran', ['class' => 'form-label']) }}
     {{ Form::date('enrollment_date', null, [
         'class' => 'form-control' . ($errors->has('enrollment_date') ? ' is-invalid' : ''),
     ]) }}
     @error('enrollment_date')
         <div class="invalid-feedback d-block">{{ $message }}</div>
     @enderror
 </div>

 <div class="mb-6">
     {{ Form::label('status', 'Status', ['class' => 'form-label']) }}
     {{ Form::select('status', $statuses, null, [
         'class' => 'form-select' . ($errors->has('status') ? ' is-invalid' : ''),
     ]) }}
     @error('status')
         <div class="invalid-feedback d-block">{{ $message }}</div>
     @enderror
 </div>
 {{ Form::close() }}
